@extends('layouts.master')

@section('pageTitle', 'Roles & Permissions')

@section('styles')
<link href="{{ asset( 'assets/plugins/custom/datatables/datatables.bundle.css' ) }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <x-breadcrumb 
    heading='Role & Permissoin'
    :links="[
        ['label' => 'Roles & Permissions']
    ]" />
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container">
           
            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-8 fs-5 border-0 flex-wrap">
                @can('role-list')
                    <li class="nav-item">
                        <a class="nav-link border-active-info border-hover-info text-active-primary text-hover-primary fw-semibold px-2" data-bs-toggle="tab" href="#roles">
                            <i class="bi bi-person-fill-gear fs-2 me-3"></i>{{ __('Roles') }}
                        </a>
                    </li>
                @endcan
                @can('permission-group-list')
                    <li class="nav-item">
                        <a class="nav-link border-active-info border-hover-info text-active-primary text-hover-primary fw-semibold px-2" data-bs-toggle="tab" href="#permission-groups">
                            <i class="bi bi-person-lines-fill fs-2 me-3"></i>{{ __('Permission Groups') }}
                        </a>
                    </li>
                @endcan
                @can('permission-list')
                    <li class="nav-item">
                        <a class="nav-link border-active-info border-hover-info text-active-primary text-hover-primary fw-semibold px-2" data-bs-toggle="tab" href="#permissions">
                            <i class="bi bi-person-fill-lock fs-2 me-3"></i>{{ __('Permissions') }}
                        </a>
                    </li>
                @endcan
            </ul>
            <div class="tab-content">
                @can('role-list')
                    <div class="tab-pane fade" id="roles" role="tabpanel">
                        @include('admin.rolePermission.roles')
                    </div>
                @endcan
                @can('permission-group-list')
                    <div class="tab-pane fade" id="permission-groups" role="tabpanel">
                        @include('admin.rolePermission.permissionGroup')
                    </div>
                @endcan
                @can('permission-list')
                    <div class="tab-pane fade" id="permissions" role="tabpanel">
                        @include('admin.rolePermission.permission')
                    </div>
                @endcan
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
@endsection

@section('modals')
@include('admin.rolePermission.models.permissionGroup')
@include('admin.rolePermission.models.permission')
@endsection

@section('scripts')
<script>
    var loadingBlockUI = new KTBlockUI(document.querySelector('body'), {
        message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Please Wait...</div>',
    });
    KTUtil.onDOMContentLoaded(function () {
        $('.nav-tabs a.nav-link[data-bs-toggle="tab"]').on( 'shown.bs.tab', function (e) {
            var activeTab = $(e.target).attr('href');
            $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
            
            const settings = {
                activeTab: activeTab
            };

            sessionStorage.setItem('rolePermission', JSON.stringify(settings || {}));
        });
        var settings = JSON.parse(sessionStorage.getItem('rolePermission') || "{}" );
        if( Object.keys(settings).length != 0 && settings.hasOwnProperty('activeTab') ) {
            $('.nav-tabs a.nav-link[href="' + settings.activeTab + '"]').tab('show');
        } else {
            $('.nav-tabs a.nav-link:first').tab('show');
        }
    });
</script>
<script src="{{ asset( 'assets/plugins/custom/datatables/datatables.bundle.js' ) }}"></script>
@can('role-list')
    <script src="{{ asset( 'assets/js/visdomr/role-permission/role-index.js' ) }}"></script>
@endcan
@can('permission-group-list')
    <script src="{{ asset( 'assets/js/visdomr/role-permission/group-index.js' ) }}"></script>
@endcan
@can('permission-list')
    <script src="{{ asset( 'assets/js/visdomr/role-permission/permission-index.js' ) }}"></script>
@endcan
@endsection   

