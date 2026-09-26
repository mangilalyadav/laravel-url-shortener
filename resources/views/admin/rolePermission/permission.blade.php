<!--begin::Card-->
<div class="card border-2">
    <!--begin::Card header-->
    <div class="card-header border-0 pt-6">
        <!--begin::Card title-->
        <div class="card-title">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-1">
                <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                <span class="svg-icon svg-icon-1 position-absolute ms-4">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                    </svg>
                </span>
                <!--end::Svg Icon-->
                <input type="text" data-kt-permission-table-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search permission" id="permission-index-search"/>
            </div>
            <!--end::Search-->
        </div>
        <!--begin::Card title-->
        <!--begin::Card toolbar-->
        <div class="card-toolbar">
            <!--begin::Toolbar-->
            <div class="d-flex justify-content-end" data-kt-permission-table-toolbar="base">
                @can('permission-create')
                    <!--begin::Add permission-->
    
                    <a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_permission">
                        <i class="ki-outline ki-plus fs-2"></i>Add Permission</a>
                    <!--end::Add permission-->
                @endcan
            </div>
            <!--end::Toolbar-->
            <!--begin::Group actions-->
            <div class="d-flex justify-content-end align-items-center d-none" data-kt-permission-table-toolbar="selected">
                <div class="fw-bold me-5">
                <span class="me-2" data-kt-permission-table-select="selected_count"></span>{{ __('Selected') }}</div>
                @can('permission-delete')
                    <button type="button" data-url="{{ route( 'admin.permissions.bulkDestroy' ) }}" class="btn btn-danger" data-kt-permission-table-select="delete_selected"><i class="la la-trash fs-2 me-2"></i>{{ __('Delete Selected') }}</button>
                @endcan
            </div>
            <!--end::Group actions-->
        </div>
        <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body py-4">
        <!--begin::Table-->
        <table id="kt_table_permissions" class="table align-middle table-row-dashed fs-6 gy-5" data-url="{{ route('admin.permissions.index') }}">
            <!--begin::Table head-->
            <thead>
                <!--begin::Table row-->
                <tr class="text-start text-primary fw-bold fs-7 text-uppercase gs-0">
                    <th class="w-10px pe-2">
                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                            <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_table_permissions .form-check-input" value="1" />
                        </div>
                    </th>
                    <th class="min-w-125px">{{ __('Name') }}</th>
                    <th class="min-w-125px">{{ __('Display Name') }}</th>
                    <th class="min-w-125px">{{ __('Group') }}</th>
                    <th class="text-end min-w-100px">{{ __('Actions') }}</th>
                </tr>
                <!--end::Table row-->
            </thead>
            <!--end::Table head-->
            <!--begin::Table body-->
            <tbody class="text-gray-600 fw-semibold">
            </tbody>
            <!--end::Table body-->
        </table>
        <!--end::Table-->
    </div>
    <!--end::Card body-->
</div>
<!--end::Card-->