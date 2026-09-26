@extends('layouts.master')

@section('pageTitle', 'Team Members')

@section('styles')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <x-breadcrumb 
    heading="{{ __('Team Members').( (auth()->user()->client?->name)) }}" 
    
    :links="[
        ['label' => 'Team Members']
    ]" />

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container">
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
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                        rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path
                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <input type="text" data-kt-country-table-filter="search"
                                class="form-control form-control-solid w-250px ps-14" placeholder="Search country"
                                id="country-index-search" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-country-table-toolbar="base">
                            <!--begin::Filter-->
                            {{-- <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_filter_user">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                                <span class="svg-icon svg-icon-2 me-0">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </button> --}}
                            <!--end::Filter-->
                            <!--begin::Refresh-->
                            <button type="button" id="kt_refresh_countries" class="btn btn-refresh btn-light-primary me-3">
                                <i class="bi bi-arrow-repeat fs-2x p-0 border-0"></i>
                            </button>
                            <!--end::Refresh-->
                                <!--begin::Add invite-->
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                 <i class="ki-outline ki-plus fs-2"></i>Invite New Team Member</a>
                                <!--end::Add invite-->
                        </div>
                        <!--end::Toolbar-->
                        <!--begin::Group actions-->
                        <div class="d-flex justify-content-end align-items-center d-none"
                            data-kt-country-table-toolbar="selected">
                            <div class="fw-bold me-5">
                                <span class="me-2" data-kt-country-table-select="selected_count"></span>{{ __('Selected') }}
                            </div>
                            @can('country-delete')
                                <button type="button" data-url="{{ route('admin.users.bulkDestroy') }}" class="btn btn-danger" data-kt-country-table-select="delete_selected">
                                    <i class="la la-trash fs-2 me-2"></i>{{ __('Delete Selected') }}
                                </button>
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
                    <table id="kt_table_country" class="table align-middle table-row-dashed fs-6 gy-3"
                        data-url="{{ route('admin.users.index') }}">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-primary fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" data-kt-check="true"
                                            data-kt-check-target="#kt_table_country .form-check-input" value="1" />
                                    </div>
                                </th>
                                <th class="min-w-125px">{{ __('Name') }}</th>
                                <th class="min-w-125px">{{ __('Email') }}</th>
                                <th class="min-w-125px">{{ __('Role') }}</th>
                                <th class="min-w-125px">{{ __('Total Urls ') }}</th>
                                <th class="min-w-125px">{{ __('Total Hits') }}</th>

                                <!-- <th class="text-end min-w-100px">{{ __('Actions') }}</th> -->
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
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
@endsection

@section('modals')
    <div class="modal fade" tabindex="-1" id="kt_modal_filter_user">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form class="form" method="post" action="#" data-kt-user-table-filter="form">
                    @csrf
                    <div class="modal-header">
                        <h3 class="modal-title">{{ __('Filter Options') }}</h3>
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-light-primary ms-2" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span class="svg-icon svg-icon-1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                        transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                        transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                                </svg>
                            </span>
                        </div>
                        <!--end::Close-->
                    </div>
                    <div class="modal-body">
                        <!--begin::Input group-->
                        <div class="fv-row mb-5">
                            <!--begin::Label-->
                            <label class="fs-5 fw-bold form-label mb-2">{{ __('Status:') }}</label>
                            <!--end::Label-->
                            <!--begin::Options-->
                            <div class="d-flex">
                                <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                                    <input class="form-check-input datatable-input" type="checkbox" name="status[]"
                                        value="Active" data-index="5">
                                    <span class="form-check-label text-dark fw-semibold fs-6">Active</span>
                                </label>
                                <label class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input datatable-input" type="checkbox" name="status[]"
                                        value="Inactive" data-index="5">
                                    <span class="form-check-label text-dark fw-semibold fs-6">Inactive</span>
                                </label>
                            </div>
                            <!--end::Options-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="modal-footer flex-center">
                        <button type="submit" class="btn btn-lg btn-info w-sm-200px w-100"
                            data-kt-country-table-filter="filter">
                            {{ __('Apply') }}
                        </button>
                        <button type="reset" class="btn btn-lg btn-primary w-sm-200px w-100" data-bs-dismiss="modal"
                            data-kt-country-table-filter="reset">{{ __('Reset') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var loadingBlockUI = new KTBlockUI(document.querySelector('body'), {
            message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Please Wait...</div>',
        });
    </script>

    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/visdomr/country/index.js') }}"></script>

@endsection
