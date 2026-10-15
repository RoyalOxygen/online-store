@extends('layouts.blank')

@section('content')
    <div class="text-center text-white mb-4">
        <h2>{{ "Software Installation" }}</h2>
        <h6 class="fw-normal">
            {{ "Fill in the details below and complete the installation in one step." }}
        </h6>
    </div>

    <div class="card mt-4 position-relative">
        <div class="p-4 mb-md-3 mx-xl-4 px-md-5">
            <div class="d-flex align-items-center column-gap-3 flex-wrap">
                <h5 class="fw-bold fs text-uppercase">{{ "Install" }}</h5>
                <h5 class="fw-normal">{{ "Application, Database & Admin" }}</h5>
            </div>
            <p class="mb-4">
                {{ "Everything needed to run the software is collected here. No terminal or additional step is required." }}
            </p>

            @if (session()->has('error'))
                <div class="row margin-top-20px">
                    <div class="col-md-12">
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('install.run') }}">
                @csrf

                <div class="bg-light p-4 rounded mb-4">
                    <h6 class="fw-bold text-uppercase fs m-0 letter-spacing --fs-14px mb-3">
                        {{ "Application" }}
                    </h6>
                    <div class="px-xl-2 pb-sm-3">
                        <div class="row gy-4">
                            <div class="col-md-12">
                                <div class="from-group">
                                    <label for="app_url" class="d-flex align-items-center gap-2 mb-2">
                                        {{ "Application URL" }}
                                    </label>
                                    <input type="url" id="app_url" class="form-control" name="app_url"
                                           value="{{ old('app_url', $defaultAppUrl) }}"
                                           required placeholder="Ex: http://localhost/online-store" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="company_name" class="d-flex align-items-center gap-2 mb-2">
                                        {{ "Business Name" }}
                                    </label>
                                    <input type="text" id="company_name" class="form-control" name="company_name"
                                           value="{{ old('company_name') }}" required placeholder="Ex: Online Store">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="currency_model" class="d-flex align-items-center gap-2 mb-2">
                                        {{ "Currency Model" }}
                                    </label>
                                    <select class="form-control form-select action-installation-currency-select"
                                            name="currency_model">
                                        <option value="single_currency" @selected(old('currency_model') == 'single_currency')>
                                            {{ "Single Currency" }}
                                        </option>
                                        <option value="multi_currency" @selected(old('currency_model') == 'multi_currency')>
                                            {{ "Multi Currency" }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-light p-4 rounded mb-4">
                    <h6 class="fw-bold text-uppercase fs m-0 letter-spacing --fs-14px mb-3">
                        {{ "Database Information" }}
                    </h6>
                    <div class="px-xl-2 pb-sm-3">
                        <div class="row gy-4">
                            <div class="col-md-8">
                                <div class="from-group">
                                    <label for="db_host" class="d-flex align-items-center gap-2 mb-2">
                                        {{ "Database Host" }}
                                    </label>
                                    <input type="text" id="db_host" class="form-control" name="DB_HOST"
                                           value="{{ old('DB_HOST', $defaultDbHost) }}" required
                                           placeholder="Ex: 127.0.0.1" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="from-group">
                                    <label for="db_port" class="d-flex align-items-center gap-2 mb-2">
                                        {{ "Port" }}
                                    </label>
                                    <input type="text" id="db_port" class="form-control" name="DB_PORT"
                                           value="{{ old('DB_PORT', $defaultDbPort) }}" placeholder="3306"
                                           autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="db_name" class="d-flex align-items-center gap-2 mb-2">
                                        {{ "Database Name" }}
                                    </label>
                                    <input type="text" id="db_name" class="form-control" name="DB_DATABASE"
                                           value="{{ old('DB_DATABASE', $defaultDbName) }}" required
                                           placeholder="Ex: online_store" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="db_user" class="d-flex align-items-center gap-2 mb-2">
                                        {{ "Database Username" }}
                                    </label>
                                    <input type="text" id="db_user" class="form-control" name="DB_USERNAME"
                                           value="{{ old('DB_USERNAME', $defaultDbUser) }}" required
                                           placeholder="Ex: root" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="db_pass" class="d-flex align-items-center gap-2 mb-2">
                                        {{ "Database Password" }}
                                    </label>
                                    <div class="input-inner-end-ele position-relative">
                                        <input type="password" id="db_pass" class="form-control" name="DB_PASSWORD"
                                               value="{{ old('DB_PASSWORD') }}" placeholder="Leave empty if none"
                                               autocomplete="off">
                                        <div class="togglePassword">
                                            <img alt="" class="svg eye"
                                                 src="{{ dynamicAsset(path: 'public/assets/installation/assets/img/svg-icons/eye.svg') }}">
                                            <img alt="" class="svg eye-off"
                                                 src="{{ dynamicAsset(path: 'public/assets/installation/assets/img/svg-icons/eye-off.svg') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-light p-4 rounded mb-4">
                    <h6 class="fw-bold text-uppercase fs m-0 letter-spacing --fs-14px mb-3">
                        {{ "Admin Account" }}
                    </h6>
                    <div class="px-xl-2 pb-sm-3">
                        <div class="row gy-4">
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="admin-name" class="d-flex align-items-center gap-2 mb-2">
                                        {{ "Admin Name" }}
                                    </label>
                                    <input type="text" id="admin-name" class="form-control" name="admin_name"
                                           value="{{ old('admin_name') }}" required placeholder="Ex: John Doe">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="email" class="d-flex align-items-center gap-2 mb-2">
                                        {{ "Admin Email" }}
                                    </label>
                                    <input type="email" id="email" class="form-control" name="admin_email"
                                           value="{{ old('admin_email') }}" required placeholder="Ex: admin@example.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="admin_phone" class="d-flex align-items-center gap-2 mb-2">
                                        {{ "Admin Phone" }}
                                    </label>
                                    <div class="number-input-wrap">
                                        <input type="tel" id="admin_phone" class="form-control" name="admin_phone"
                                               value="{{ old('admin_phone') }}" required placeholder="Ex: 9837530836">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="from-group">
                                    <label for="password" class="d-flex align-items-center gap-2 mb-2">
                                        {{ "Admin Password (At least 8 characters)" }}
                                    </label>
                                    <div class="input-inner-end-ele position-relative">
                                        <input type="password" autocomplete="new-password" id="admin_password"
                                               name="admin_password" required class="form-control"
                                               placeholder="Ex: 8+ character" minlength="8">
                                        <div class="togglePassword">
                                            <img alt="" class="svg eye"
                                                 src="{{ dynamicAsset(path: 'public/assets/installation/assets/img/svg-icons/eye.svg') }}">
                                            <img alt="" class="svg eye-off"
                                                 src="{{ dynamicAsset(path: 'public/assets/installation/assets/img/svg-icons/eye-off.svg') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-dark px-sm-5 action-installation-show-loader">
                        {{ "Complete Installation" }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
