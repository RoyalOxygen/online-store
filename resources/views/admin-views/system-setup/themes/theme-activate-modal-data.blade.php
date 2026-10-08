<div class="modal-header border-0 pb-0 d-flex justify-content-end">
    <button type="button" class="btn-close border-0 btn-circle bg-section2 shadow-none"
        data-bs-dismiss="modal" aria-label="Close">
    </button>
</div>
<div class="modal-body px-30 py-0 mb-30">
    <div class="mb-20 text-center">
        <img width="140"
             src="{{ getStorageImages(path: getWebConfig('company_web_logo'), type: 'backend-basic') }}"
             alt="" class="dark-support"/>
    </div>
    <div class="text-center mb-30">
        <h2 class="mb-2">{{ ucfirst(str_replace('_', ' ', $themeInfo['name'])) }}</h2>
        <p class="mb-0">{{ translate('activate_this_theme') }}</p>
    </div>
    <form action="{{ route('admin.system-setup.theme.activation') }}" method="post">
        @csrf
        <div class="d-flex justify-content-center gap-20">
            <input type="hidden" value="{{ $theme }}" name="theme">
            <button type="button" class="btn btn-secondary flex-grow-1" data-bs-dismiss="modal">{{ translate('cancel') }}</button>
            <button type="submit" class="btn btn-primary flex-grow-1">{{ translate('activate') }}</button>
        </div>
    </form>
</div>
