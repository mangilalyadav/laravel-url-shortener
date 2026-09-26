@extends('layouts.master')

@section('pageTitle', 'Clients')

@section('content')

    <x-breadcrumb
        heading="{{ __('Clients') }}"
        :links="[
            ['label' => 'Clients']
        ]"
    />

    <div id="kt_app_content" class="app-content flex-column-fluid">

        <div id="kt_app_content_container" class="app-container">

            <div class="card border-2">

                {{-- Card Header --}}
                <div class="card-header border-0 pt-6">

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

                {{-- Card Body --}}
                <div class="card-body py-4">

                    <div class="table-responsive">

                        <table class="table align-middle table-row-dashed fs-6 gy-4">

                            <thead>

                                <tr class="text-start text-primary fw-bold fs-7 text-uppercase">

                                    <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                id="select-all"
                                            />
                                        </div>
                                    </th>

                                    <th>
                                        {{ __('Client Name') }}
                                    </th>

                                    <th>
                                        {{ __('Email') }}
                                    </th>

                                    <th>
                                        {{ __('Created At') }}
                                    </th>

                                    <!-- <th class="text-end">
                                        {{ __('Actions') }}
                                    </th> -->

                                </tr>

                            </thead>

                            <tbody class="text-gray-600 fw-semibold">

                                @forelse($clients as $client)

                                    <tr>

                                        {{-- Checkbox --}}
                                        <td>

                                            <div class="form-check form-check-sm form-check-custom form-check-solid">

                                                <input
                                                    class="form-check-input client-checkbox"
                                                    type="checkbox"
                                                    name="ids[]"
                                                    value="{{ $client->id }}"
                                                />

                                            </div>

                                        </td>

                                        {{-- Client Name --}}
                                        <td>

                                            <span class="text-gray-800 fw-bold">
                                                {{ $client->name }}
                                            </span>

                                        </td>

                                        {{-- Email --}}
                                        <td>

                                            <span class="text-muted">
                                                {{ $client->email ?? '-' }}
                                            </span>

                                        </td>

                                        {{-- Created At --}}
                                        <td>

                                            @if($client->created_at)
                                                {{ $client->created_at->format('d M Y, h:i A') }}
                                            @else
                                                -
                                            @endif

                                        </td>

                                        {{-- Actions --}}
                                        <td class="text-end">

                                            @can('client-edit')

                                                <a
                                                    href="{{ route('admin.clients.edit', $client->id) }}"
                                                    class="btn btn-sm btn-light-primary btn-icon me-2"
                                                    title="{{ __('Edit') }}"
                                                >
                                                    <i class="la la-edit fs-2"></i>
                                                </a>

                                            @endcan

                                            @can('client-delete')

                                                <a
                                                    href="javascript:;"
                                                    data-url="{{ route('admin.clients.destroy', $client->id) }}"
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
                                            colspan="5"
                                            class="text-center py-10"
                                        >

                                            <div class="text-gray-500 fw-semibold fs-5">
                                                {{ __('No clients found.') }}
                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    {{-- Pagination --}}
                    @if($clients->hasPages())

                        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between mt-5">

                            <div>
                                {{ $clients->links() }}
                            </div>

                            <div class="mt-3 mt-sm-0">

                                <span class="text-muted">
                                    {{ __('Showing') }}
                                    {{ $clients->firstItem() }}
                                    -
                                    {{ $clients->lastItem() }}
                                    {{ __('of') }}
                                    {{ $clients->total() }}
                                </span>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

@endsection

@section('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const selectAll = document.getElementById('select-all');

        if (selectAll) {

            selectAll.addEventListener('change', function () {

                document.querySelectorAll('.client-checkbox')
                    .forEach(function (checkbox) {
                        checkbox.checked = selectAll.checked;
                    });

            });

        }

    });
</script>

@endsection
