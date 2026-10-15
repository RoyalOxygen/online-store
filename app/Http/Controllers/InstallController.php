<?php

namespace App\Http\Controllers;

use App\Traits\EmailTemplateTrait;
use App\Traits\InstallationTrail;
use App\Traits\SettingsTrait;
use App\Traits\UpdateClass;
use App\Traits\ActivationClass;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class InstallController extends Controller
{
    use ActivationClass, EmailTemplateTrait, SettingsTrait, UpdateClass, InstallationTrail;

    public function step0(): View
    {
        return view('installation.step0');
    }

    public function step1(): View
    {
        $permission['curl_enabled'] = function_exists('curl_version');
        $permission['db_file_write_perm'] = is_writable(base_path('.env'));
        $permission['routes_file_write_perm'] = is_writable(base_path('app/Providers/RouteServiceProvider.php'));
        return view('installation.step1', compact('permission'));
    }

    public function step2(): RedirectResponse
    {
        return redirect('step3');
    }

    public function step3(): View
    {
        return view('installation.step3');
    }

    public function step4(): View
    {
        return view('installation.step4');
    }

    public function step5(): View
    {
        if (DOMAIN_POINTED_DIRECTORY == 'public') {
            $themeLink = public_path('themes');
            if (!file_exists($themeLink) && !is_link($themeLink)) {
                @symlink(base_path('resources/themes'), $themeLink);
            }
            Artisan::call('storage:link');
        }

        try {
            $this->setEnvironmentValue(envKey: 'APP_URL', envValue: url('/'));
        } catch (Exception $exception) {
        }

        $this->updateRobotTexFile();
        Artisan::call('file:permission');
        Artisan::call('config:cache');
        Artisan::call('config:clear');
        return view('installation.step5');
    }

    public function updatePurchaseCode(Request $request): RedirectResponse
    {
        return redirect('step3');
    }

    public function updateSystemSettings(Request $request): View
    {
        $this->addSystemPrimaryData(request: $request);

        try {
            if (!Schema::hasTable('addon_settings')) {
                DB::unprepared(File::get(base_path('database/migrations/addon_settings.sql')));
            }

            if (!Schema::hasTable('payment_requests')) {
                DB::unprepared(File::get(base_path('database/migrations/payment_requests.sql')));
            }
        } catch (\Exception $exception) {
        }

        $previousRouteServiceProvider = base_path('app/Providers/RouteServiceProvider.php');
        $newRouteServiceProvider = base_path('app/Providers/RouteServiceProvider.txt');
        copy($newRouteServiceProvider, $previousRouteServiceProvider);
        return view('installation.step6');
    }

    function checkDatabaseConnection($db_host = "", $db_name = "", $db_user = "", $db_pass = "", $db_port = 3306): bool
    {
        try {
            if (function_exists('mysqli_connect')) {
                return (bool)(@mysqli_connect($db_host, $db_user, $db_pass, $db_name, (int)$db_port));
            }

            new \PDO("mysql:host={$db_host};port={$db_port};dbname={$db_name}", $db_user, $db_pass, [
                \PDO::ATTR_TIMEOUT => 5,
            ]);
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function databaseInstallation(Request $request): RedirectResponse
    {
        if (self::checkDatabaseConnection($request['DB_HOST'], $request['DB_DATABASE'], $request['DB_USERNAME'], $request['DB_PASSWORD'])) {
            $this->updateEnvironmentFile(request: $request);
            $this->cleanupEnvFileContext();
            $path = base_path('.env');
            if (file_exists($path)) {
                return redirect('step4');
            } else {
                session()->flash('error', 'Database error!');
                return redirect('step3');
            }
        } else {
            session()->flash('error', 'Database error!');
            return redirect('step3');
        }
    }

    public function importSQL(): RedirectResponse
    {
        try {
            $sql_path = base_path('installation/backup/database.sql');
            DB::unprepared(file_get_contents($sql_path));
            return redirect('step5');
        } catch (\Exception $exception) {
            session()->flash('error', 'Your database is not clean, do you want to clean database then import?');
            return back();
        }
    }

    public function forceImportSQL(): RedirectResponse
    {
        try {
            Artisan::call('db:wipe');
            $sql_path = base_path('installation/backup/database.sql');
            DB::unprepared(file_get_contents($sql_path));
            return redirect('step5');
        } catch (\Exception $exception) {
            session()->flash('error', 'Check your database permission!');
            return back();
        }
    }

    /**
     * Single-page installer form. Everything is collected here in the browser
     * so a fresh clone can be set up without any extra CLI/backend step.
     */
    public function wizard(): View|RedirectResponse
    {
        if (self::isInstalled()) {
            return redirect('/');
        }

        $request = request();

        return view('installation.wizard', [
            'defaultAppUrl' => $request->getSchemeAndHttpHost() . $request->getBasePath(),
            'defaultDbHost' => env('DB_HOST', '127.0.0.1'),
            'defaultDbPort' => env('DB_PORT', '3306'),
            'defaultDbName' => env('DB_DATABASE', ''),
            'defaultDbUser' => env('DB_USERNAME', ''),
        ]);
    }

    /**
     * Apply the wizard input: write .env, import the schema, create the super
     * admin and finish the installation in a single request.
     */
    public function install(Request $request): View|RedirectResponse
    {
        if (self::isInstalled()) {
            return redirect('/');
        }

        $data = $request->validate([
            'app_url' => 'required|url',
            'DB_HOST' => 'required|string',
            'DB_PORT' => 'nullable|numeric',
            'DB_DATABASE' => 'required|string',
            'DB_USERNAME' => 'required|string',
            'DB_PASSWORD' => 'nullable|string',
            'company_name' => 'required|string|max:255',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'admin_phone' => 'required|string|max:50',
            'admin_password' => 'required|string|min:8',
            'currency_model' => 'required|in:single_currency,multi_currency',
        ]);

        $data['app_url'] = rtrim($data['app_url'], '/');
        $data['DB_PORT'] = $data['DB_PORT'] ?: '3306';
        $data['DB_PASSWORD'] = $data['DB_PASSWORD'] ?? '';

        if (!self::checkDatabaseConnection($data['DB_HOST'], $data['DB_DATABASE'], $data['DB_USERNAME'], $data['DB_PASSWORD'], (int)$data['DB_PORT'])) {
            return back()->withInput()->withErrors([
                'DB_HOST' => translate('Invalid database credentials or host. Please check and try again.'),
            ]);
        }

        $this->updateEnvironmentFileFromWizard($data, $data['app_url']);

        try {
            config([
                'database.connections.mysql.host' => $data['DB_HOST'],
                'database.connections.mysql.port' => $data['DB_PORT'],
                'database.connections.mysql.database' => $data['DB_DATABASE'],
                'database.connections.mysql.username' => $data['DB_USERNAME'],
                'database.connections.mysql.password' => $data['DB_PASSWORD'],
            ]);
            DB::purge('mysql');
            DB::reconnect('mysql');

            $this->importDatabase();
            $this->addSystemPrimaryData($request);

            if (DOMAIN_POINTED_DIRECTORY == 'public') {
                $themeLink = public_path('themes');
                if (!file_exists($themeLink) && !is_link($themeLink)) {
                    @symlink(base_path('resources/themes'), $themeLink);
                }
                Artisan::call('storage:link');
            }

            $this->updateRobotTexFile();
            Artisan::call('file:permission');
            Artisan::call('config:clear');
            Artisan::call('optimize:clear');
        } catch (\Throwable $exception) {
            return back()->withInput()->withErrors([
                'DB_DATABASE' => translate('Installation failed') . ': ' . $exception->getMessage(),
            ]);
        }

        return view('installation.wizard-success', [
            'appUrl' => $data['app_url'],
            'adminLoginUrl' => $data['app_url'] . '/login/' . (getWebConfig(name: 'admin_login_url') ?: 'admin'),
        ]);
    }

    /**
     * Importer for the bundled schema. A non-clean database is wiped and
     * re-imported so a retried installation always ends with a valid schema.
     */
    protected function importDatabase(): void
    {
        $sqlPath = base_path('installation/backup/database.sql');
        if (!file_exists($sqlPath)) {
            throw new Exception('Database backup file not found: installation/backup/database.sql');
        }

        try {
            DB::unprepared(file_get_contents($sqlPath));
        } catch (\Throwable $exception) {
            Artisan::call('db:wipe');
            DB::unprepared(file_get_contents($sqlPath));
        }
    }

    protected static function isInstalled(): bool
    {
        return (bool) config('app.installed');
    }
}
