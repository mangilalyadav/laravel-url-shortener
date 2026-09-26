<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                {{ $heading }}</h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('admin.dashboard') }}"
                        class="text-muted text-hover-primary">{{ __('Home') }}</a>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item">
                    <i class="fas fa-angles-right fs-8"></i>
                </li>
                <!--end::Item-->
                @foreach ($links as $index => $link)
                	@if ($index === count($links) - 1)
                		<!--begin::Item-->
		                <li class="breadcrumb-item text-primary">{{ $link['label'] }}</li>
		                <!--end::Item-->
		            @else
		            	<!--begin::Item-->
		                <li class="breadcrumb-item text-muted">
		                	<a href="{{ $link['url'] }}" class="text-muted text-hover-primary">{{ $link['label'] }}</a>
		                </li>
		                <!--end::Item-->
		                <!--begin::Item-->
		                <li class="breadcrumb-item">
		                    <i class="fas fa-angles-right fs-8"></i>
		                </li>
		                <!--end::Item-->
                	@endif
                @endforeach
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
        @if(!empty($backUrl))
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ $backUrl }}" class="btn btn-primary back_btn align-self-center ps-7"><i class="la la-arrow-left fs-2 me-2"></i>{{ __('Back') }}</a>
            </div>
            <!--end::Actions-->
        @endif
    </div>
    <!--end::Toolbar container-->
</div>
<!--end::Toolbar-->