@extends('layouts.auth')

@section('pageTitle', 'Login')

@section('content')
<!--begin::Authentication - Sign-in -->
<!--begin::Wrapper-->
 <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
    <!--begin::Form-->
    <form method="POST" class="form w-100" novalidate="novalidate" id="kt_sign_in_form"  action="{{ route('admin.auth.login') }}">
        @csrf
        <!--begin::Heading-->
        <div class="text-center mb-11">
            <!--begin::Title-->
            <h1 class="text-dark fw-bolder mb-3">Sign In</h1>
            <!--end::Title-->
        </div>
        <!--begin::Heading-->
            <!-- Display error message if login fails -->
        @error('email')
        <div class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-10">
            <!--begin::Icon-->
            <i class="bi bi-fingerprint text-danger fs-2hx me-4 mb-5 mb-sm-0"></i>
            <!--end::Icon-->
            <!--begin::Content-->
            <div class="d-flex flex-column pe-0 pe-sm-8">
                <h5 class="mb-1 text-danger">{{ __('Invalid Credentials') }}</h5>
            <div class="fv-plugins-message-container invalid-feedback">
                <span>{{ $message }}</span>
            </div>
            </div>
            <!--end::Content-->
            <!--begin::Close-->
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="bi bi-x fs-1 text-danger"></i>
            </button>
            <!--end::Close-->
        </div>
        @enderror

        @error('unauthorize')
        <div class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-10">
            <!--begin::Icon-->
            <i class="bi bi-fingerprint text-danger fs-2hx me-4 mb-5 mb-sm-0"></i>
            <!--end::Icon-->
            <!--begin::Content-->
            <div class="d-flex flex-column pe-0 pe-sm-8">
                <h5 class="mb-1 text-danger">{{ __('Invalid Role') }}</h5>
            <div class="fv-plugins-message-container invalid-feedback">
                <span>{{ $message }}</span>
            </div>
            </div>
            <!--end::Content-->
            <!--begin::Close-->
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="bi bi-x fs-1 text-danger"></i>
            </button>
            <!--end::Close-->
        </div>
        @enderror

        @error('inactive')
        <div class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-10">
            <!--begin::Icon-->
            <i class="bi bi-fingerprint text-danger fs-2hx me-4 mb-5 mb-sm-0"></i>
            <!--end::Icon-->
            {{-- --}}
            <!--begin::Content-->
            <div class="d-flex flex-column pe-0 pe-sm-8">
                <h5 class="mb-1 text-danger">{{ __('Invalid Role') }}</h5>
            <div class="fv-plugins-message-container invalid-feedback">
                <span>{{ $message }}</span>
            </div>
            </div>
            <!--end::Content-->
            <!--begin::Close-->
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="bi bi-x fs-1 text-danger"></i>
            </button>
            <!--end::Close-->
        </div>
        @enderror

        <!--begin::Input group=-->
        <div class="fv-row mb-8">
            <!--begin::Email-->
            <input type="text" placeholder="Email" name="email" autocomplete="off" class="form-control bg-transparent @error('email') is-invalid @enderror" value="{{ old('email') }}" />
            <!--end::Email-->
            @error('email')
            {{-- <div class="fv-plugins-message-container invalid-feedback">
                <div data-field="email">{{ $message }}</div>
            </div> --}}
            @enderror
        </div>
        <!--end::Input group=-->
        <div class="fv-row mb-3" data-kt-password-meter="true">
            <!--begin::Password-->
            <div class="position-relative mb-3">
                <input type="password" placeholder="Password" name="password" autocomplete="off" class="form-control bg-transparent @error('password') is-invalid @enderror" />
                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-4"
                data-kt-password-meter-control="visibility">
                <i class="bi bi-eye-slash fs-2"></i>

                <i class="bi bi-eye fs-2 d-none"></i>
            </span>
            </div>
            <!--end::Password-->
        </div>
        <!--end::Input group=-->
        <!--begin::Wrapper-->
        <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
            <div></div>
            <!--begin::Link-->
            <a href="#">Forgot Password ?</a>
            <!--end::Link-->
        </div>
        <!--end::Wrapper-->
        <!--begin::Submit button-->
        <div class="d-grid mb-10">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                <!--begin::Indicator label-->
                <span class="indicator-label">Sign In</span>
                <!--end::Indicator label-->
                <!--begin::Indicator progress-->
                <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                <!--end::Indicator progress-->
            </button>
        </div>
        <!--end::Submit button-->
    </form>
    <!--end::Form-->
</div>
<!--end::Wrapper-->
<!--end::Authentication - Sign-in-->
@endsection

@section('scripts')
<script src="{{ asset( 'assets/js/visdomr/auth/signin.js' ) }}"></script>
@endsection