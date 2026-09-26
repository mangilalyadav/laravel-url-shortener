<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<!--begin::Head-->
	<head><base href=""/>
		<meta charset="utf-8" />
		<title>@yield('pageTitle') - {{ config('app.name') }}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<meta name="robots" content="noindex, nofollow">

		<!--begin::Favicons-->
		<link rel="icon" type="image/png" href="{{ asset('favicon-96x96.png') }}" sizes="96x96" />
		<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" />
		<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
		<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}" />
		<meta name="apple-mobile-web-app-title" content="VisdomR" />
		<link rel="manifest" href="{{ asset('site.webmanifest') }}" />
		<!--end::Favicons-->
		
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		
		<!--begin::Vendor Stylesheets(used for this page only)-->
		<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
		<!--end::Vendor Stylesheets-->
		
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
        @vite(['resources/css/app.css'])
		<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->

        @yield('styles')
		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true" data-kt-app-aside-push-footer="true" class="app-default">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<!--begin::Header-->
				 @include('admin.includes.header')
				<!--begin::Wrapper-->
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<!--begin::Sidebar-->
					@include('admin.includes.sidebar')
					<!--end::Sidebar-->
					<!--begin::Main-->
					<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<!--begin::Content wrapper-->
						<div class="d-flex flex-column flex-column-fluid">
							@yield('content')
						</div>
						<!--end::Content wrapper-->
						<!--begin::Footer-->
						<div id="kt_app_footer" class="app-footer">
							<!--begin::Footer container-->
							<div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
								<!--begin::Copyright-->
								<div class="text-dark order-2 order-md-1">
									
								</div>
								<!--end::Copyright-->
								<!--begin::Menu-->
								<ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
									<li class="menu-item">
										<a href="{{ route('admin.dashboard') }}"  class="menu-link px-2">Home</a>
									</li>
								</ul>
								<!--end::Menu-->
							</div>
							<!--end::Footer container-->
						</div>
						<!--end::Footer-->
					</div>
					<!--end:::Main-->
					<!--begin::aside-->
					
					<!--end::aside-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
        @yield('modals')
		<!--end::App-->
		<!--begin::Drawers-->
		<!--begin::Activities drawer-->
		<div id="kt_activities" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="activities" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'lg': '900px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_activities_toggle" data-kt-drawer-close="#kt_activities_close">
			<div class="card shadow-none border-0 rounded-0">
				
				<!--begin::Body-->
				<div class="card-body position-relative" id="kt_activities_body">
					<!--begin::Content-->
					<div id="kt_activities_scroll" class="position-relative scroll-y me-n5 pe-5" data-kt-scroll="true" data-kt-scroll-height="auto" data-kt-scroll-wrappers="#kt_activities_body" data-kt-scroll-dependencies="#kt_activities_header, #kt_activities_footer" data-kt-scroll-offset="5px">
						
					</div>
					<!--end::Content-->
				</div>
				<!--end::Body-->
				<!--begin::Footer-->
				<!--end::Footer-->
			</div>
		</div>
		<!--end::Activities drawer-->
		<!--begin::Chat drawer-->
		<div id="kt_drawer_chat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_chat_toggle" data-kt-drawer-close="#kt_drawer_chat_close">
			<!--begin::Messenger-->
			<div class="card w-100 border-0 rounded-0" id="kt_drawer_chat_messenger">
				<!--begin::Card header-->
				
				<!--end::Card header-->
				<!--begin::Card body-->
				<div class="card-body" id="kt_drawer_chat_messenger_body">
					<!--begin::Messages-->
					<div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer" data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">
						
					</div>
					<!--end::Messages-->
				</div>
				<!--end::Card body-->
				<!--begin::Card footer-->
				
				<!--end::Card footer-->
			</div>
			<!--end::Messenger-->
		</div>
	
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-outline ki-arrow-up"></i>
		</div>
		<!--end::Scrolltop-->
		<!--begin::Modals-->
		
		<!--end::Modals-->


		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
        @vite(['resources/js/app.js'])
		<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
        <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
		<script>
			function debounce(func, delay) {
				let timer;
				return function () {
					clearTimeout(timer);
					timer = setTimeout(() => func.apply(this, arguments), delay);
				};
			}
			KTUtil.onDOMContentLoaded(function () {
				axios.interceptors.response.use(function (response) {
                    return response;
                }, function (error) {
                    console.log(error);
                    if( error.response.data.hasOwnProperty('error_on_page') && error.response.data.error_on_page === true ) {
                        return Promise.reject(error);
                    }

                    if(typeof error.response !== 'undefined') {
                        let dataTitle   = 'Error';
                        let dataMessage = '';
                        let dataErrors  = error.response.data.errors;

                        if(error.response.status === 400) {
                            dataTitle = 'Bad request';
                        } else if(error.response.status === 401) {
                            dataTitle = 'Unauthorized';
                        } else if(error.response.status === 403) {
                            dataTitle = 'Forbidden';
                        } else if(error.response.status === 404) {
                            dataTitle = 'Not Found';
                        } else if(error.response.status === 405) {
                            dataTitle = 'Method Not Allowed';
                        } else if(error.response.status === 422) {
                            dataTitle = 'Validation Error';
                        } else if(error.response.status >= 500) {
                            dataTitle = 'Server Error';
                        }

                        if( error.hasOwnProperty('response') && error.response.hasOwnProperty('data') ) {
                            if( dataErrors != null ) {
                                if( Object.keys(dataErrors).length >= 1 ) {
                                    dataMessage += '<div class="d-flex flex-column py-4">';
                                    for (const errorsKey in dataErrors) {
                                        if (!dataErrors.hasOwnProperty(errorsKey)) continue;
                                        dataMessage += '<li class="d-flex align-items-center py-2">';
                                        dataMessage += '<span class="bullet bg-danger me-5"></span>' + dataErrors[errorsKey];
                                        dataMessage += '</li>';
                                    }
                                    dataMessage += '</div>';
                                }
                            } else {
                                if( error.response.data.hasOwnProperty('message') && error.response.data.message.length > 0 ) {
                                    dataMessage += error.response.data.message;
                                }
                            }
                        }

                        if( dataMessage == '' ) {
                            dataMessage = 'Something went wrong! Please try agian.'
                        }

                        Swal.fire({
                            title            : dataTitle,
                            html             : dataMessage,
                            icon             : "error",
                            allowOutsideClick: false,
                            allowEscapeKey   : false,
                            buttonsStyling   : false,
                            confirmButtonText: "Ok",
                            customClass      : {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }

                    return Promise.reject(error);
                });
				jQuery.validator.setDefaults({
                    errorElement  : 'div',
                    errorClass    : 'is-invalid',
                    validClass    : 'is-valid',
                    focusInvalid  : true,
					errorPlacement: function (error, element) {
                        error.appendTo( element.parents('.fv-row') ).wrap( "<div class='fv-plugins-message-container invalid-feedback'></div>" );
                    },
                    highlight: function(element, errorClass, validClass) {
                        $(element).addClass(errorClass).removeClass(validClass);
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).removeClass(errorClass).addClass(validClass);
                    }
                });
			});
		</script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
        @yield('scripts')
	</body>
	<!--end::Body-->
</html>