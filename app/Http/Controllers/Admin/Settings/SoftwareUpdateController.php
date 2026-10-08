<?php

namespace App\Http\Controllers\Admin\Settings;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SoftwareUpdateRequest;
use App\Traits\ActivationClass;
use App\Traits\SettingsTrait;
use App\Traits\UpdateClass;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SoftwareUpdateController extends Controller
{
    use ActivationClass;
    use UpdateClass;
    use SettingsTrait;

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View Index function is the starting point of a controller
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, ?string $type = null): View
    {
        return view('admin-views.system-setup.software-update');
    }

    public function update(SoftwareUpdateRequest $request): RedirectResponse|JsonResponse
    {
        if (env('APP_MODE', 'dev') == 'demo') {
            return response()->json([
                'status' => false,
                'message' => translate('Uploading_ZIP_files_is_currently_unavailable_in_demo_mode')
            ]);
        }

        ToastMagic::error(translate('software_update_from_zip_is_disabled'));
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => translate('software_update_from_zip_is_disabled'),
            ]);
        }

        return back();
    }
}
