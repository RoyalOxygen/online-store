<?php

namespace App\Http\Controllers;

use App\Utils\Helpers;
use App\Traits\ActivationClass;
use App\Traits\UpdateClass;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\URL;

class UpdateController extends Controller
{
    use ActivationClass;
    use UpdateClass;

    public function index(): View
    {
        return view('update.update-software');
    }

    public function updateSoftware(Request $request): Redirector|RedirectResponse
    {
        Helpers::setEnvironmentValue('SOFTWARE_VERSION', SOFTWARE_VERSION);
        Helpers::setEnvironmentValue('APP_MODE', 'live');
        Helpers::setEnvironmentValue('SESSION_LIFETIME', '60');

        $appUrl = URL::to('/');
        $hostDomain = parse_url($appUrl, PHP_URL_HOST) ?: '';
        $publicIp = $request->server('SERVER_ADDR') ?: (filter_var($hostDomain, FILTER_VALIDATE_IP) ? $hostDomain : '');

        Helpers::setEnvironmentValue('APP_HOST_DOMAIN', $hostDomain);
        Helpers::setEnvironmentValue('APP_HOST_BASE_DOMAIN', Helpers::getHostBaseDomain($hostDomain));
        Helpers::setEnvironmentValue('APP_PUBLIC_IP', $publicIp);

        Artisan::call('migrate', ['--force' => true]);
        $previousRouteServiceProvider = base_path('app/Providers/RouteServiceProvider.php');
        $newRouteServiceProvider = base_path('app/Providers/RouteServiceProvider.txt');
        copy($newRouteServiceProvider, $previousRouteServiceProvider);

        if (DOMAIN_POINTED_DIRECTORY == 'public') {
            $themeLink = public_path('themes');
            if (!file_exists($themeLink) && !is_link($themeLink)) {
                @symlink(base_path('resources/themes'), $themeLink);
            }
            Artisan::call('storage:link');
        }

        Artisan::call('optimize:clear');
        $this->getProcessAllVersionsUpdates();
        $this->cleanupEnvFileContext();
        return redirect(url('/'));
    }
}
