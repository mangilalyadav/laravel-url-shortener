@extends('layouts.master')

@section('pageTitle', 'Invite New')

@section('content')

<x-breadcrumb 
    heading="{{ __('Invite New') }}" 
    :links="[
        ['label' => 'Clients', 'url' => route('admin.clients.index')],
        ['label' => 'Invite']
    ]" 
    backUrl="{{ route('admin.clients.index') }}"
/>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container">

        <div class="card mb-5 mb-xl-10 border-2">

            <div class="card-header border-0">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">
                        {{ __('Invite New') }}
                    </h3>
                </div>
            </div>

            <div class="card-body border-top p-9">

                <form
                    id="kt_country_form"
                    class="form"
                    method="POST"
                    action="{{ route('admin.clients.store') }}"
                >
                    @csrf

                    <div class="row mb-5">

                        {{-- Admin User --}}
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <label class="fs-5 fw-bold form-label mb-2">
                                <span class="required">
                                    {{ __('Admin User') }}
                                </span>
                            </label>

                            <select
                                name="admin_id"
                                class="form-select"
                                data-control="select2"
                                data-placeholder="{{ __('Select Admin User') }}"
                                required
                            >
                                <option value="">
                                    {{ __('Select Admin User') }}
                                </option>

                                @foreach($admins as $admin)
                                    <option
                                        value="{{ $admin->id }}"
                                        {{ old('admin_id') == $admin->id ? 'selected' : '' }}
                                    >
                                        {{ $admin->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('admin_id')
                                <div class="text-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Name --}}
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <label class="fs-5 fw-bold form-label mb-2">
                                <span class="required">
                                    {{ __('Name') }}
                                </span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                placeholder="{{ __('Name') }}"
                                class="form-control"
                                minlength="3"
                                maxlength="50"
                                value="{{ old('name') }}"
                                required
                            >

                            @error('name')
                                <div class="text-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-sm-6 col-md-6 col-lg-6 mt-5">
                            <label class="fs-5 fw-bold form-label mb-2">
                                <span class="required">
                                    {{ __('Email') }}
                                </span>
                            </label>

                            <input
                                type="email"
                                name="email"
                                placeholder="{{ __('Email') }}"
                                class="form-control"
                                maxlength="100"
                                value="{{ old('email') }}"
                                required
                            >

                            @error('email')
                                <div class="text-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>

                    <div class="d-flex justify-content-end">

                        <a
                            href="{{ route('admin.clients.index') }}"
                            class="btn btn-light me-3"
                        >
                            {{ __('Cancel') }}
                        </a>

                        <button
                            type="submit"
                            id="kt_country_submit"
                            class="btn btn-primary"
                        >
                            <span class="indicator-label">
                                {{ __('Send Invitation') }}
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
