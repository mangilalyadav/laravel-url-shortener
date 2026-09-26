<div id="kt_app_header" class="app-header d-flex flex-column flex-stack">
    <!--begin::Header main-->
    <div class="d-flex flex-stack flex-grow-1">
        <div class="app-header-logo d-flex align-items-center justify-content-center" id="kt_app_header_logo">
            <!--begin::Sidebar mobile toggle-->
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px ms-3 me-2 d-flex d-lg-none" id="kt_app_sidebar_mobile_toggle">
                <i class="ki-outline ki-abstract-14 fs-2"></i>
            </div>
            <!--end::Sidebar mobile toggle-->
            <!--begin::Logo-->
            <a href="{{ route('admin.dashboard') }}" class="app-sidebar-logo">
                {{-- <img alt="Logo" src="{{ asset('assets/media/logos/demo39.svg') }}" class="h-25px theme-light-show" /> --}}
                <img alt="{{ config('app.name') }}" src="{{ asset('assets/media/logos/image.png') }}" class="h-60px mw-300px app-sidebar-logo-default" />
            </a>
            <!--end::Logo-->
        </div>
        <!--begin::Navbar-->
        <div class="app-navbar flex-grow-1 justify-content-end" id="kt_app_header_navbar">
           
            <!--begin::User menu-->
            <div class="app-navbar-item ms-2 ms-lg-6" id="kt_header_user_menu_toggle">
                <!--begin::Menu wrapper-->
                <div class=" d-flex align-items-center  px-md-2 w-md-auto cursor-pointer cursor-pointer symbol symbol-circle symbol-30px symbol-lg-45px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                    <img alt="{{ auth()->user()->name }}" src="{{ asset('assets/media/logos/60111.jpg') }}"/>

                    <span class="text-dark fw-semibold fs-5 d-none d-sm-flex px-1">{{ auth()->user()->name }}</span>
                        <span class="ms-2 rotate-180">
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                            <span class="svg-icon svg-icon-3">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                        fill="currentColor"></path>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                </div>

                <!--begin::User account menu-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <!--begin::Avatar-->
                            {{-- <div class="symbol symbol-50px me-5">
                                <img alt="Logo" src="assets/media/avatars/300-2.jpg" />
                            </div> --}}
                            <div class="symbol bg-light symbol-50px me-5">
                                <img alt="{{ auth()->user()->full_name }}" src="{{ asset('assets/media/logos/60111.jpg') }}" />
                            </div>
                            <!--end::Avatar-->
                            <!--begin::Username-->
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5"> {{ auth()->user()->full_name }}
                                <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">{{ auth()->user()->roles->first()->name }}</span></div>
                                {{-- <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">max@kt.com</a> --}}
                                <a href="mailto:{{ auth()->user()->email }}"
                                    class="fw-bold text-muted text-hover-primary fs-7">{{ auth()->user()->email }}</a>
                            </div>
                            <!--end::Username-->
                        </div>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu separator-->
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->
                    <!--begin::Menu item-->
                    <!-- <div class="menu-item  my-1">
                        <a href="{{ route('admin.editProfile') }}" class="menu-link px-5">Profile Update</a>
                    </div> -->
                    
                    <!--end::Menu item-->
                    
                    <!--begin::Menu separator-->
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->
                    <!--begin::Menu item-->
                    {{-- <div class="menu-item px-5">
                        <a href="{{ route('admin.logout') }}" class="menu-link px-5">
                            <span class="menu-icon" data-kt-element="icon">
                                <i class="bi bi-box-arrow-right fs-2"></i>
                            </span>
                            <span class="menu-title">{{ __('Logout') }}</span>
                        </a>
                    </div> --}}
                    <div class="menu-item px-5">
                        <!-- Form for logout -->
                        <form action="{{ route('admin.logout') }}" method="POST" class="">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none p-0">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="bi bi-box-arrow-right fs-2"></i>
                                </span>
                                <span class="menu-title">{{ __('Logout') }}</span>
                            </button>
                        </form>
                    </div>
                    
                    <!--end::Menu item-->
                </div>
                <!--end::User account menu-->
                <!--end::Menu wrapper-->
            </div>
            <!--end::User menu-->
            <!--begin::Action-->
            <div class="app-navbar-item ms-2 ms-lg-6 me-lg-6">
            
            </div>
            <!--end::Action-->
           
        </div>
        <!--end::Navbar-->
    </div>
    <!--end::Header main-->
    <!--begin::Separator-->
    <div class="app-header-separator"></div>
    <!--end::Separator-->
</div>
<!--end::Header-->