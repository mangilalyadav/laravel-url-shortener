@can('permission-create')
    <div class="modal fade" tabindex="-1" id="kt_modal_create_permission">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form class="form kt_permission_form" method="post" action="{{ route('admin.permissions.store') }}" data-action="store">
                    @csrf
                    <div class="modal-header">
                        <h3 class="modal-title">{{ __('Add Permission') }}</h3>
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <span class="svg-icon svg-icon-1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                                </svg>
                            </span>
                        </div>
                        <!--end::Close-->
                    </div>
                    <div class="modal-body">
                        <!--begin::Input group-->
                        <div class="fv-row mb-5">
                            <!--begin::Label-->
                            <label class="fs-5 fw-bold form-label mb-2">
                                <span class="required">{{ __('Permission Name') }}</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="name" class="form-control required" placeholder="{{ __('Enter permission name') }}">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-5">
                            <!--begin::Label-->
                            <label class="fs-5 fw-bold form-label mb-2">
                                <span class="required">{{ __('Display Name') }}</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="display_name" class="form-control required" placeholder="{{ __('Enter display name') }}">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-5">
                            <!--begin::Label-->
                            <label class="fs-5 fw-bold form-label mb-2">
                                <span class="required">{{ __('Permission Group') }}</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select class="form-select required" name="group_id" data-control="select2" data-placeholder="{{ __('Select a group') }}" data-allow-clear="true" data-dropdown-parent="#kt_modal_create_permission" required>
                                <option></option>
                                @foreach ($permissionGroups as $permissionGroup)
                                    <option value="{{ $permissionGroup->id }}">{{ $permissionGroup->name }}</option>
                                @endforeach
                            </select>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="modal-footer flex-center">
                        <button type="submit" class="btn btn-lg btn-info w-sm-200px w-100" data-kt-indicator="off">
                            <span class="indicator-label">
                                {{ __('Submit') }}
                            </span>
                            <span class="indicator-progress">
                                {{ __('Please wait...') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        <button type="button" class="btn btn-lg btn-primary w-sm-200px w-100" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
@can('permission-edit')
    <div class="modal fade" tabindex="-1" id="kt_modal_edit_permission">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form class="form kt_permission_form" method="post" action="#" data-action="update">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h3 class="modal-title">{{ __('Edit Permission') }}</h3>
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <span class="svg-icon svg-icon-1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                                </svg>
                            </span>
                        </div>
                        <!--end::Close-->
                    </div>
                    <div class="modal-body">
                        <!--begin::Input group-->
                        <div class="fv-row mb-5">
                            <!--begin::Label-->
                            <label class="fs-5 fw-bold form-label mb-2">
                                <span class="required">{{ __('Permission Name') }}</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="name" class="form-control required" placeholder="{{ __('Enter permission name') }}">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-5">
                            <!--begin::Label-->
                            <label class="fs-5 fw-bold form-label mb-2">
                                <span class="required">{{ __('Display Name') }}</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="display_name" class="form-control required" placeholder="{{ __('Enter display name') }}">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-5">
                            <!--begin::Label-->
                            <label class="fs-5 fw-bold form-label mb-2">
                                <span class="required">{{ __('Permission Group') }}</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select class="form-select required" name="group_id" data-control="select2" data-placeholder="{{ __('Select a group') }}" data-allow-clear="true" data-dropdown-parent="#kt_modal_edit_permission" required>
                                <option></option>
                                @foreach ($permissionGroups as $permissionGroup)
                                    <option value="{{ $permissionGroup->id }}">{{ $permissionGroup->name }}</option>
                                @endforeach
                            </select>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="modal-footer flex-center">
                        <button type="submit" class="btn btn-lg btn-info w-sm-200px w-100" data-kt-indicator="off">
                            <span class="indicator-label">
                                {{ __('Submit') }}
                            </span>
                            <span class="indicator-progress">
                                {{ __('Please wait...') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        <button type="button" class="btn btn-lg btn-primary w-sm-200px w-100" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan