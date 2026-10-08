@extends('layouts.admin.app')

@section('title', translate('Addon_Activation_Process'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3 mb-sm-20">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                {{ translate('Addon_Activation_Process') }}
            </h2>
        </div>

        @if(isset($addonData['deliveryman_app']))
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('admin.system-setup.addon-activation.activation') }}" method="post" class="form-advance-validation  form-advance-inputs-validation form-advance-file-validation non-ajax-form-validate" novalidate="novalidate">
                    @csrf
                    <input type="hidden" name="addon_name" value="deliveryman_app">
                    <input type="hidden" name="software_type" value="addon">
                    <input type="hidden" name="software_id" value="MzYwODUwNDE=">
                    <div class="view-details-container">
                        <div class="d-flex justify-content-between align-items-center gap-3">
                            <div>
                                <h3>
                                    {{ translate('Deliveryman_App') }}
                                </h3>
                                <p class="mb-1 fs-12">
                                    {{ translate("After_configuring_the_app_enable_the_status_and_set_up_the_required_information_to_activate") }}
                                </p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="javascript:"
                                   class="fs-12 fw-semibold d-flex align-items-end view-btn">{{ translate('View') }}
                                    <i class="fi fi-rr-arrow-small-down fs-16 trans3"></i></a>
                                <label class="switcher">
                                    <input
                                        class="switcher_input custom-modal-plugin"
                                        type="checkbox" value="1" name="status"
                                        {{ isset($addonData['deliveryman_app']['activation_status']) && $addonData['deliveryman_app']['activation_status'] == 1 ? 'checked' : '' }}
                                        data-modal-type="input-change-form"
                                        data-modal-form="#smtp-mail-config-form"
                                        data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/maintenance_mode-on.png') }}"
                                        data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/maintenance_mode-off.png') }}"
                                        data-on-title="{{ translate('want_to_Turn_ON_the_Deliveryman_App_addon').'?' }}"
                                        data-off-title="{{ translate('want_to_Turn_OFF_the_Deliveryman_App_addon').'?' }}"
                                        data-on-message="<p>{{ translate('enabling_Deliveryman_App_addon_status_will_allow_the_API_for_use') }}</p>"
                                        data-off-message="<p>{{ translate('disabling_Deliveryman_App_addon_status_will_disable_the_API_for_use') }}</p>">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                        </div>
                        <div class="view-details mt-3 mt-sm-4">
                            <p class="fs-12 mb-0">{{ translate('toggle_the_status_to_enable_or_disable_this_addon') }}</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif

    </div>
@endsection
