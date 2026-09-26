@extends('layouts.master')

@section('pageTitle', 'Edit Role')

@section('content')

<x-breadcrumb 
    heading="{{ __('Edit Role') }}" 
    :links="[
        ['label' => 'Roles', 'url' => route('admin.roles.index')],
        ['label' => 'Edit']
    ]" 
    backUrl="{{ route('admin.roles.index') }}"
/>

<div id="kt_app_content" class="app-content flex-column-fluid">

    <div id="kt_app_content_container" class="app-container">

        <div class="card mb-5 mb-xl-10 border-2">

            {{-- Card Header --}}
            <div class="card-header border-0">

                <div class="card-title m-0">
                    <h3 class="fw-bold m-0 text-primary">
                        {{ __('Edit Role') }}
                    </h3>
                </div>

            </div>

            {{-- Card Body --}}
            <div class="card-body border-top p-9">

                <form
                    id="kt_role_form"
                    class="form"
                    method="POST"
                    action="{{ route('admin.roles.update', $role->id) }}"
                    data-action="update"
                >
                    @csrf
                    @method('PUT')

                    {{-- Role Details --}}
                    <div class="fs-4 pb-3">
                        <div class="fw-bold">
                            {{ __('Role Details') }}
                        </div>
                    </div>

                    <div class="separator mb-5"></div>

                    <div class="row mb-10">

                        {{-- Role Name --}}
                        <div class="col-lg-6">

                            <div class="fv-row">

                                <label class="fs-5 fw-bold form-label mb-2">
                                    <span class="required">
                                        {{ __('Role Name') }}
                                    </span>
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    placeholder="{{ __('Enter a role name') }}"
                                    value="{{ old('name', $role->name) }}"
                                    required
                                />

                                @error('name')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                        {{-- Display Name --}}
                        <div class="col-lg-6">

                            <div class="fv-row">

                                <label class="fs-5 fw-bold form-label mb-2">
                                    <span class="required">
                                        {{ __('Display Name') }}
                                    </span>
                                </label>

                                <input
                                    type="text"
                                    name="display_name"
                                    class="form-control"
                                    placeholder="{{ __('Enter a role display name') }}"
                                    value="{{ old('display_name', $role->display_name) }}"
                                    required
                                />

                                @error('display_name')
                                    <div class="text-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                    {{-- Permissions --}}
                    <div class="fv-row">

                        <label class="fs-5 fw-bold form-label mb-5">
                            {{ __('Role Permissions') }}
                        </label>

                        <div class="table-responsive">

                            <table class="table align-middle table-row-dashed fs-6 gy-5">

                                <tbody class="text-gray-600 fw-semibold">

                                    {{-- Select All --}}
                                    <tr>

                                        <td class="text-gray-800">
                                            {{ __('Administrator Access') }}

                                            <i
                                                class="fas fa-exclamation-circle ms-1 fs-7"
                                                data-bs-toggle="tooltip"
                                                title="Allows full access to the system"
                                            ></i>
                                        </td>

                                        <td>

                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-9">

                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    value="1"
                                                    id="kt_roles_select_all"
                                                >

                                                <span class="form-check-label">
                                                    {{ __('Select all') }}
                                                </span>

                                            </label>

                                        </td>

                                    </tr>

                                    {{-- Permission Groups --}}
                                    @foreach ($permissionGroups as $permissionGroup)

                                        <tr class="permission_group_row">

                                            <td class="text-gray-800">
                                                {{ $permissionGroup->name }}
                                            </td>

                                            <td>

                                                <div class="d-flex flex-wrap">

                                                    @foreach ($permissionGroup->permissions as $permission)

                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 py-2">

                                                            <input
                                                                class="form-check-input permission-checkbox"
                                                                type="checkbox"
                                                                value="{{ $permission->id }}"
                                                                name="permission[]"
                                                                {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}
                                                            >

                                                            <span class="form-check-label">
                                                                {{ $permission->display_name }}
                                                            </span>

                                                        </label>

                                                    @endforeach

                                                </div>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end mt-10">

                        <a
                            href="{{ route('admin.roles.index') }}"
                            class="btn btn-light me-3"
                        >
                            {{ __('Cancel') }}
                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary"
                            id="kt_role_submit"
                            data-kt-indicator="off"
                        >

                            <span class="indicator-label">
                                {{ __('Update Role') }}
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

<script src="{{ asset('assets/js/visdomr/role-permission/role-form.js') }}"></script>

@endsection
