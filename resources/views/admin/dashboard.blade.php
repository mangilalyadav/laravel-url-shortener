@extends('layouts.master')

@section('pageTitle', 'Dashboard')

@section('styles')
    <link
        href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}"
        rel="stylesheet"
        type="text/css"
    />
@endsection

@section('content')

<x-breadcrumb
    heading="{{ __('Dashboard') }}"
    :links="[
        ['label' => 'Dashboard']
    ]"
/>

<div id="kt_app_content" class="app-content flex-column-fluid">

    <div id="kt_app_content_container" class="app-container">


        {{-- for super admin dashboard --}}

        @role('superadmin')

            <div class="card mb-5 mb-xl-10">

                <div class="card-header border-0 pt-5">

                    <div class="card-title">
                        <h3 class="fw-bold">
                            {{ __('Clients') }}
                        </h3>
                    </div>

                    <div class="card-toolbar">

                        <a
                            href="{{ route('admin.clients.create') }}"
                            class="btn btn-primary"
                        >
                            <i class="ki-outline ki-plus fs-2"></i>
                            {{ __('Invite') }}
                        </a>

                    </div>

                </div>

                <div class="card-body pt-0">

                    <div class="table-responsive">

                        <table class="table align-middle table-row-dashed fs-6 gy-5">

                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase">

                                    <th>
                                        {{ __('Country') }}
                                    </th>

                                    <th>
                                        {{ __('Email') }}
                                    </th>

                                    <th class="text-end">
                                        {{ __('Actions') }}
                                    </th>

                                </tr>
                            </thead>

                            <tbody>

                                @forelse($clients as $client)

                                    <tr>

                                        <td>
                                            <span class="text-gray-800 fw-bold">
                                                {{ $client->name }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="text-muted">
                                                {{ $client->email }}
                                            </span>
                                        </td>

                                        <td class="text-end">

                                            <a
                                                href="{{ route('admin.clients.edit', $client->id) }}"
                                                class="btn btn-sm btn-light-primary"
                                            >
                                                {{ __('Edit') }}
                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td
                                            colspan="3"
                                            class="text-center text-muted py-10"
                                        >
                                            {{ __('No clients found.') }}
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    @if($clients->hasPages() || $clients->count() > 0)

                        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between mt-5">

                            <div>
                                {{ $clients->links() }}
                            </div>

                            <div class="mt-3 mt-sm-0">

                                <a
                                    href="{{ route('admin.clients.index') }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    {{ __('View All') }}
                                </a>

                            </div>

                        </div>

                    @endif

                </div>

            </div>
            


    {{-- for generated short url--}}


<div class="card mb-5 mb-xl-10">

    <div class="card-header border-0 pt-5">

        <div class="card-title">

            <h3 class="fw-bold">
                {{ __('All Generated Short URLs') }}
            </h3>

        </div>

        <div class="card-toolbar">

    <form
        method="GET"
        action="{{ route('admin.generated_urls.downloadPdf') }}"
        class="d-flex align-items-center gap-3"
    >

        <select
            name="filter"
            class="form-select form-select-solid"
            style="width: 180px;"
        >

            <option value="today">
                {{ __('Today') }}
            </option>

            <option value="last_week">
                {{ __('Last Week') }}
            </option>

            <option value="this_month">
                {{ __('This Month') }}
            </option>

            <option value="last_month">
                {{ __('Last Month') }}
            </option>

        </select>


        <button
            type="submit"
            class="btn btn-danger"
        >

            <i class="ki-outline ki-file-down fs-2"></i>

            {{ __('Download PDF') }}

        </button>

    </form>

</div>


    </div>

    <div class="card-body pt-0">

        <div class="table-responsive">

            <table class="table align-middle table-row-dashed fs-6 gy-5">

                <thead>

                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase">

                        <th>
                            #
                        </th>

                        <th>
                            {{ __('Short URL') }}
                        </th>

                        <th>
                            {{ __('Long URL') }}
                        </th>

                        <th>
                            {{ __('Created By') }}
                        </th>

                        <th>
                            {{ __('Company') }}
                        </th>

                        <th>
                            {{ __('Hits') }}
                        </th>

                        <th>
                            {{ __('Created At') }}
                        </th>

                        <th class="text-end">
                            {{ __('Actions') }}
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($urls as $url)

                        <tr>

                            <td>
                                {{ $urls->firstItem() + $loop->index }}
                            </td>

                            <td>

                                <a
                                    href="{{ route('short-url.resolve', $url->code) }}"
                                    target="_blank"
                                    class="text-primary fw-bold"
                                >
                                    {{ route('short-url.resolve', $url->code) }}
                                </a>

                            </td>

                            <td>

                                <div
                                    class="text-truncate"
                                    style="max-width: 300px;"
                                    title="{{ $url->long_url }}"
                                >
                                    {{ $url->long_url }}
                                </div>

                            </td>

                            <td>

                                @if($url->creator)

                                    <span class="text-gray-800 fw-bold">
                                        {{ $url->creator->name }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if($url->client)

                                    <span class="text-gray-800">
                                        {{ $url->client->name }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>

                            <td>

                                <span class="badge badge-light-primary">

                                    {{ number_format($url->hits ?? 0) }}

                                </span>

                            </td>

                            <td>

                                {{ $url->created_at
                                    ? $url->created_at->format('d M Y, h:i A')
                                    : '-'
                                }}

                            </td>

                            <td class="text-end">

                                @can('user-edit')

                                    <a
                                        href="{{ route('admin.generated_urls.edit', $url->id) }}"
                                        class="btn btn-sm btn-light-primary btn-icon me-2"
                                        title="{{ __('Edit') }}"
                                    >
                                        <i class="la la-edit fs-2"></i>
                                    </a>

                                @endcan

                                @can('user-delete')

                                    <a
                                        href="javascript:;"
                                        data-url="{{ route('admin.generated_urls.destroy', $url->id) }}"
                                        class="btn btn-sm btn-light-danger btn-icon"
                                        title="{{ __('Delete') }}"
                                        data-kt-country-table-filter="delete_row"
                                    >
                                        <i class="la la-trash fs-2"></i>
                                    </a>

                                @endcan

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center text-muted py-10"
                            >
                                {{ __('No generated URLs found.') }}
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($urls->hasPages() || $urls->count() > 0)

            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between mt-5">

                <div>
                    {{ $urls->links() }}
                </div>

                <div class="mt-3 mt-sm-0">

                    <span class="text-muted me-3">

                        {{ __('Showing') }}

                        {{ $urls->firstItem() }}

                        -

                        {{ $urls->lastItem() }}

                        {{ __('of') }}

                        {{ $urls->total() }}

                    </span>

                   

                </div>

            </div>

        @endif

    </div>

</div>


    {{-- for admin dashboard --}}
        @elsehasrole('admin')

    <div class="row g-5 g-xl-10">

       

        <div class="col-12">

            <div class="card border-2">

                <div class="card-header border-0 pt-6">

    <div class="card-title">

        <h3 class="fw-bold">
            {{ __('Generated Short URLs') }}
        </h3>

    </div>

    <div class="card-toolbar d-flex align-items-center gap-3">

        <form
            method="GET"
            action="{{ route('admin.generated_urls.downloadPdf') }}"
            class="d-flex align-items-center gap-2"
        >

            <select
                name="filter"
                class="form-select form-select-solid"
                style="width: 160px;"
            >

                <option value="today">
                    {{ __('Today') }}
                </option>

                <option value="last_week">
                    {{ __('Last Week') }}
                </option>

                <option value="this_month">
                    {{ __('This Month') }}
                </option>

                <option value="last_month">
                    {{ __('Last Month') }}
                </option>

            </select>

            <button
                type="submit"
                class="btn btn-danger"
            >

                <i class="ki-outline ki-file-down fs-2"></i>

                {{ __('Download PDF') }}

            </button>

        </form>

        @can('user-create')

            <a
                href="{{ route('admin.generated_urls.create') }}"
                class="btn btn-primary"
            >

                <i class="ki-outline ki-plus fs-2"></i>

                {{ __('Generate Short URL') }}

            </a>

        @endcan

    </div>

</div>

                <div class="card-body py-4">

                    <div class="table-responsive">

                        <table class="table align-middle table-row-dashed fs-6 gy-4">

                            <thead>

                                <tr class="text-start text-primary fw-bold fs-7 text-uppercase">

                                    <th>#</th>

                                    <th>
                                        {{ __('Short URL') }}
                                    </th>

                                    <th>
                                        {{ __('Long URL') }}
                                    </th>

                                    <th>
                                        {{ __('Created By') }}
                                    </th>

                                    <th>
                                        {{ __('Hits') }}
                                    </th>

                                    <th>
                                        {{ __('Created At') }}
                                    </th>

                                    <th class="text-end">
                                        {{ __('Actions') }}
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="text-gray-600 fw-semibold">

                                @forelse($urls as $url)

                                    <tr>

                                        <td>
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>

                                            <a
                                                href="{{ route('short-url.resolve', $url->code) }}"
                                                target="_blank"
                                                class="text-primary fw-bold"
                                            >
                                                {{ route('short-url.resolve', $url->code) }}
                                            </a>

                                        </td>

                                        <td>

                                            <div
                                                class="text-truncate"
                                                style="max-width: 350px;"
                                                title="{{ $url->long_url }}"
                                            >
                                                {{ $url->long_url }}
                                            </div>

                                        </td>

                                        <td>

                                            @if($url->creator)
                                                {{ $url->creator->name }}
                                            @else
                                                -
                                            @endif

                                        </td>

                                        <td>

                                            <span class="badge badge-light-primary">
                                                {{ number_format($url->hits) }}
                                            </span>

                                        </td>

                                        <td>
                                            {{ $url->created_at->format('d M Y, h:i A') }}
                                        </td>

                                        <td class="text-end">

                                            @can('user-edit')

                                                <a
                                                    href="{{ route('admin.generated_urls.edit', $url->id) }}"
                                                    class="btn btn-sm btn-light-primary btn-icon me-2"
                                                    title="{{ __('Edit') }}"
                                                >
                                                    <i class="la la-edit fs-2"></i>
                                                </a>

                                            @endcan

                                            @can('user-delete')

                                                <a
                                                    href="javascript:;"
                                                    data-url="{{ route('admin.generated_urls.destroy', $url->id) }}"
                                                    class="btn btn-sm btn-light-danger btn-icon"
                                                    title="{{ __('Delete') }}"
                                                    data-kt-country-table-filter="delete_row"
                                                >
                                                    <i class="la la-trash fs-2"></i>
                                                </a>

                                            @endcan

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="7"
                                            class="text-center py-10"
                                        >
                                            <div class="text-gray-500 fw-semibold fs-5">
                                                {{ __('No short URLs found.') }}
                                            </div>
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    @if($urls instanceof \Illuminate\Pagination\LengthAwarePaginator && $urls->count() > 0)

                        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between mt-5">

                            <div>
                                {{ $urls->links() }}
                            </div>

                            <div class="mt-3 mt-sm-0">

                                <a
                                    href="{{ route('admin.generated_urls.index') }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    {{ __('View All') }}
                                </a>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>







 {{-- for team membrs dashboard --}}


<div class="col-12 mt-5">

    <div class="card border-2">

        <div class="card-header border-0 pt-6">

            <div class="card-title">

                <h3 class="fw-bold">
                    {{ __('Team Members') }}
                </h3>

            </div>

            <div class="card-toolbar">

                @can('user-create')

                    <a
                        href="{{ route('admin.users.create') }}"
                        class="btn btn-primary"
                    >
                        <i class="ki-outline ki-plus fs-2"></i>
                        {{ __('Invite New Team Member') }}
                    </a>

                @endcan

            </div>

        </div>

        <div class="card-body py-4">

            <div class="table-responsive">

                <table class="table align-middle table-row-dashed fs-6 gy-4">

                    <thead>

                        <tr class="text-start text-primary fw-bold fs-7 text-uppercase">

                            <th>
                                #
                            </th>

                            <th>
                                {{ __('Name') }}
                            </th>

                            <th>
                                {{ __('Email') }}
                            </th>

                            <th>
                                {{ __('Role') }}
                            </th>

                            <th>
                                {{ __('Total Generated URLs') }}
                            </th>

                            <th>
                                {{ __('Total Hits') }}
                            </th>


                            <th class="text-end">
                                {{ __('Actions') }}
                            </th>

                        </tr>

                    </thead>

                    <tbody class="text-gray-600 fw-semibold">

                        @forelse($teamMembers as $member)

                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <span class="text-gray-800 fw-bold">
                                        {{ $member->name }}
                                    </span>
                                </td>

                                <td>
                                    <span class="text-muted">
                                        {{ $member->email }}
                                    </span>
                                </td>

                                <td>

                                    @if($member->roles->count())

                                        @foreach($member->roles as $role)

                                            <span class="badge badge-light-primary me-1">
                                                {{ $role->display_name ?? $role->name }}
                                            </span>

                                        @endforeach

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>

<td>

    <span class="badge badge-light-primary">
        {{ number_format($member->total_generated_urls ?? 0) }}
    </span>

</td>

<td>

    <span class="badge badge-light-success">
        {{ number_format($member->total_url_hits ?? 0) }}
    </span>

</td>



                                <td class="text-end">

                                    @can('user-edit')

                                        <a
                                            href="{{ route('admin.users.edit', $member->id) }}"
                                            class="btn btn-sm btn-light-primary btn-icon me-2"
                                            title="{{ __('Edit') }}"
                                        >
                                            <i class="la la-edit fs-2"></i>
                                        </a>

                                    @endcan

                                    @can('user-delete')

                                        <a
                                            href="javascript:;"
                                            data-url="{{ route('admin.users.destroy', $member->id) }}"
                                            class="btn btn-sm btn-light-danger btn-icon"
                                            title="{{ __('Delete') }}"
                                            data-kt-country-table-filter="delete_row"
                                        >
                                            <i class="la la-trash fs-2"></i>
                                        </a>

                                    @endcan

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-10"
                                >

                                    <div class="text-gray-500 fw-semibold fs-5">
                                        {{ __('No team members found.') }}
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if($teamMembers instanceof \Illuminate\Pagination\LengthAwarePaginator && $teamMembers->count() > 0)

                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between mt-5">

                    <div>
                        {{ $teamMembers->links() }}
                    </div>

                    <div class="mt-3 mt-sm-0">

                        <a
                            href="{{ route('admin.users.index') }}"
                            class="btn btn-sm btn-primary"
                        >
                            {{ __('View All') }}
                        </a>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>



    </div>

     {{-- for member role user  dashboard --}}


        @elsehasrole('member')

    <div class="row g-5 g-xl-10">
       

        <div class="col-12">

            <div class="card border-2">

                <div class="card-header border-0 pt-6">

                    <div class="card-title">

                        <h3 class="fw-bold">
                            {{ __('Generated Short URLs') }}
                        </h3>

                    </div>

                    <div class="card-toolbar">

                    <form
        method="GET"
        action="{{ route('admin.generated_urls.downloadPdf') }}"
        class="d-flex align-items-center gap-3"
    >

        <select
            name="filter"
            class="form-select form-select-solid"
            style="width: 180px;"
        >

            <option value="today">
                {{ __('Today') }}
            </option>

            <option value="last_week">
                {{ __('Last Week') }}
            </option>

            <option value="this_month">
                {{ __('This Month') }}
            </option>

            <option value="last_month">
                {{ __('Last Month') }}
            </option>

        </select>


        <button
            type="submit"
            class="btn btn-danger"
        >

            <i class="ki-outline ki-file-down fs-2"></i>

            {{ __('Download PDF') }}

        </button>

    </form>

                        @can('user-create')

                            <a
                                href="{{ route('admin.generated_urls.create') }}"
                                class="btn btn-primary"
                            >
                                <i class="ki-outline ki-plus fs-2"></i>
                                {{ __('Generate Short URL') }}
                            </a>

                        @endcan

                    </div>

                </div>


                <div class="card-body py-4">

                    <div class="table-responsive">

                        <table class="table align-middle table-row-dashed fs-6 gy-4">

                            <thead>

                                <tr class="text-start text-primary fw-bold fs-7 text-uppercase">

                                    <th>
                                        #
                                    </th>

                                    <th>
                                        {{ __('Short URL') }}
                                    </th>

                                    <th>
                                        {{ __('Long URL') }}
                                    </th>

                                    <th>
                                        {{ __('Created By') }}
                                    </th>

                                    <th>
                                        {{ __('Hits') }}
                                    </th>

                                    <th>
                                        {{ __('Created At') }}
                                    </th>

                                    <th class="text-end">
                                        {{ __('Actions') }}
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="text-gray-600 fw-semibold">

                                @forelse($urls as $url)

                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>

                                            <a
                                                href="{{ route('short-url.resolve', $url->code) }}"
                                                target="_blank"
                                                class="text-primary fw-bold"
                                            >
                                                {{ route('short-url.resolve', $url->code) }}
                                            </a>

                                        </td>

                                        <td>

                                            <div
                                                class="text-truncate"
                                                style="max-width: 350px;"
                                                title="{{ $url->long_url }}"
                                            >
                                                {{ $url->long_url }}
                                            </div>

                                        </td>

                                        <td>

                                            @if($url->creator)

                                                {{ $url->creator->name }}

                                            @else

                                                <span class="text-muted">
                                                    -
                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            <span class="badge badge-light-primary">
                                                {{ number_format($url->hits) }}
                                            </span>

                                        </td>
                                        <td>

                                            {{ $url->created_at->format('d M Y, h:i A') }}

                                        </td>

                                        <td class="text-end">

                                            @can('user-edit')

                                                <a
                                                    href="{{ route('admin.generated_urls.edit', $url->id) }}"
                                                    class="btn btn-sm btn-light-primary btn-icon me-2"
                                                    title="{{ __('Edit') }}"
                                                >
                                                    <i class="la la-edit fs-2"></i>
                                                </a>

                                            @endcan


                                            @can('user-delete')

                                                <a
                                                    href="javascript:;"
                                                    data-url="{{ route('admin.generated_urls.destroy', $url->id) }}"
                                                    class="btn btn-sm btn-light-danger btn-icon"
                                                    title="{{ __('Delete') }}"
                                                    data-kt-country-table-filter="delete_row"
                                                >
                                                    <i class="la la-trash fs-2"></i>
                                                </a>

                                            @endcan

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="7"
                                            class="text-center py-10"
                                        >

                                            <div class="text-gray-500 fw-semibold fs-5">
                                                {{ __('No short URLs found.') }}
                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    @if($urls instanceof \Illuminate\Pagination\LengthAwarePaginator && $urls->hasPages())

                        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between mt-5">

                            <div>
                                {{ $urls->links() }}
                            </div>

                            <div class="mt-3 mt-sm-0">

                                <a
                                    href="{{ route('admin.generated_urls.index') }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    {{ __('View All') }}
                                </a>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>
    
        {{-- for upcoming roles dashboard --}}

        @else

            <div class="card">

                <div class="card-body text-center py-15">

                    <h2 class="fw-bold text-gray-800">
                        {{ __('Welcome') }}
                    </h2>

                    <p class="text-muted">
                        {{ __('Your account does not have a dashboard assigned yet.') }}
                    </p>

                </div>

            </div>

        @endrole


    </div>

</div>

@endsection
