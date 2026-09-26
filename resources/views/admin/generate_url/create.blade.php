@extends('layouts.master')

@section('pageTitle', 'Generate Url')

@section('content')

    <x-breadcrumb heading="{{ __('Generate Url') }}" :links="[
            ['label' => 'Clients', 'url' => route('admin.clients.index')],
            ['label' => 'Invite']
        ]" backUrl="{{ route('admin.generated_urls.index') }}" />

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container">

            <div class="card mb-5 mb-xl-10 border-2">

                <div class="card-header border-0">
                    <div class="card-title m-0">
                        <h3 class="fw-bold m-0">
                            {{ __('Generate Url') }}
                        </h3>
                    </div>
                </div>

                <div class="card-body border-top p-9">

                    <form id="kt_country_form" class="form" method="POST"
                        action="{{ route('admin.generated_urls.store') }}">
                        @csrf

                        <div class="row mb-5">

                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <label class="fs-5 fw-bold form-label mb-2">
                                    <span class="required">
                                        {{ __('Long Url') }}
                                    </span>
                                </label>

                                <input type="text" name="long_url" placeholder="{{ __('Long Url') }}" class="form-control"
                                    minlength="3" maxlength="2048" value="{{ old('long_url') }}" required>

                                @error('name')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>


                        </div>

                        <div class="d-flex justify-content-end">

                            <a href="{{ route('admin.generated_urls.index') }}" class="btn btn-light me-3">
                                {{ __('Cancel') }}
                            </a>

                            <button type="submit" id="kt_country_submit" class="btn btn-primary">
                                <span class="indicator-label">
                                    {{ __('Generate Url') }}
                                </span>

                                <span class="indicator-progress">
                                    {{ __('Please wait...') }}
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>

@endsection

@section('scripts')

    <script src="{{ asset('assets/js/visdomr/country/form.js') }}"></script>

@endsection