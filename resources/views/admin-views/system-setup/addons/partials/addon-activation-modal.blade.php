<div class="modal-header border-0 pb-0 d-flex justify-content-end">
    <button type="button" class="btn-close border-0 btn-circle bg-section2 shadow-none"
            data-bs-dismiss="modal" aria-label="Close">
    </button>
</div>
<div class="modal-body px-30 py-0 mb-30">
    <div class="mb-20 text-center">
        <img width="140"
             src="{{ getStorageImages(path: null, type: 'backend-basic',source: $path.'/public/addon.png') }}"
             alt="" class="dark-support"/>
    </div>
    <div class="text-center mb-30">
        <h2 class="mb-2">{{ $addonName }}</h2>
        <p class="mb-0">{{ translate('activate_this_addon') }}</p>
    </div>

    <form id="addon-list-activation-form" action="{{ route('admin.system-setup.addon-activation.activate-from-list') }}" method="post"
          class="form-advance-validation non-ajax-form-validate addon-list-activation-form" autocomplete="off" novalidate="novalidate">
        @csrf
        <input type="hidden" name="addon_name" value="{{ strtolower($fullData['module_name']) }}">
        <input type="hidden" name="software_type" value="addon">
        <input type="hidden" name="software_id" value="{{ base64_encode($fullData['software_id']) }}">
        <input type="hidden" name="path" value="{{ $path }}">

        <div class="d-flex justify-content-center gap-20">
            <button type="button" class="btn btn-secondary flex-grow-1" data-bs-dismiss="modal">
                {{ translate('cancel') }}
            </button>
            <button type="{{ getDemoModeFormButton(type: 'button') }}"
                    class="btn btn-primary flex-grow-1 addon-list-activation-submit {{ getDemoModeFormButton(type: 'class') }}">
                <span class="spinner-border spinner-border-sm me-2 d--none submit-spinner" role="status" aria-hidden="true"></span>
                <span class="submit-label">{{ translate('activate') }}</span>
            </button>
        </div>
    </form>
</div>
