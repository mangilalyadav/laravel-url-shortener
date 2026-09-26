@extends('layouts.master')

@section('pageTitle', 'Short URLs')

@section('content')

    <x-breadcrumb
        heading="{{ __('Generated Short URLs') }}"
        :links="[
            ['label' => 'Short URLs']
        ]"
    />

    <div id="kt_app_content" class="app-content flex-column-fluid">

        <div id="kt_app_content_container" class="app-container">

            <div class="card border-2">

                {{-- Card Header --}}
                <div class="card-header border-0 pt-6">

                    <div class="card-title">
                        <h3 class="fw-bold">
                            {{ __('Generated Short URLs') }}
                        </h3>
                    </div>

                    <div class="card-toolbar">

                        {{-- Create button only for users who have permission --}}
                        @can('user-create')
                            <a
                                href="{{ route('admin.generated_urls.create') }}"
                                class="btn btn-primary"
                            >
                                <i class="ki-outline ki-plus fs-2"></i>
                                Generate Short URL
                            </a>
                        @endcan

                    </div>

                </div>

                {{-- Card Body --}}
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

                            <tbody class="text-gray-600 fw-semibold">

                                @forelse($urls as $url)

                                    <tr>

                                        {{-- ID --}}
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>

                                        {{-- Short URL --}}
                                        <td>

                                            <a
                                                href="{{ route('short-url.resolve', $url->code) }}"
                                                target="_blank"
                                                class="text-primary fw-bold"
                                            >
                                                {{ route('short-url.resolve', $url->code) }}
                                            </a>

                                        </td>

                                        {{-- Long URL --}}
                                        <td>

                                            <div
                                                class="text-truncate"
                                                style="max-width: 300px;"
                                                title="{{ $url->long_url }}"
                                            >
                                                {{ $url->long_url }}
                                            </div>

                                        </td>

                                        {{-- Created By --}}
                                        <td>

                                            @if($url->creator)
                                                {{ $url->creator->name }}
                                            @else
                                                -
                                            @endif

                                        </td>

                                        {{-- Company --}}
                                        <td>

                                            @if($url->client)
                                                {{ $url->client->name }}
                                            @else
                                                -
                                            @endif

                                        </td>

                                        {{-- Hits --}}
                                        <td>

                                            <span class="badge badge-light-primary">
                                                {{ number_format($url->hits) }}
                                            </span>

                                        </td>

                                        {{-- Created At --}}
                                        <td>

                                            {{ $url->created_at->format('d M Y, h:i A') }}

                                        </td>

                                        {{-- Actions --}}
                                        <td class="text-end">

                                            @can('user-edit')

                                                <a
                                                    href="{{ route('admin.generated_urls.edit', $url->id) }}"
                                                    class="btn btn-sm btn-light-primary btn-icon me-2"
                                                    title="Edit"
                                                >
                                                    <i class="la la-edit fs-2"></i>
                                                </a>

                                            @endcan



                                                    <a
                                                        href="javascript:;"
                                                        data-url="{{ route('admin.generated_urls.destroy', $url->id) }}"
                                                        class="btn btn-sm btn-light-danger btn-icon"
                                                        title="Delete"
                                                        data-kt-country-table-filter="delete_row"
                                                    >
                                                        <i class="la la-trash fs-2"></i>
                                                    </a>


                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="8"
                                            class="text-center py-10"
                                        >

                                            <div class="text-gray-500 fw-semibold fs-5">
                                                No short URLs found.
                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- Pagination --}}
                    @if($urls->hasPages())

                        <div class="mt-5">

                            {{ $urls->links() }}

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

@endsection
