<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Wrapper-->
    <div id="kt_app_sidebar_wrapper" class="app-sidebar-wrapper">
        <div class="hover-scroll-y my-5 my-lg-2 mx-4" data-kt-scroll="true"
            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_header" data-kt-scroll-wrappers="#kt_app_sidebar_wrapper"
            data-kt-scroll-offset="5px">
            <!--begin::Sidebar menu-->
            <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5">
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <span class="menu-icon">
                            <i class="ki-outline ki-home-2 fs-2"></i>
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                @role('superadmin')
                <div class="menu-item">

                    <a href="{{ route('admin.roles.index') }}"
                        class="menu-link {{ request()->routeIs('admin.rolePermission', 'admin.roles.*') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="bi bi-person-lock fs-2"></i>
                        </span>

                        <span class="menu-title">
                            Role & Permission
                        </span>
                    </a>

                </div>
                @endrole

                @role(['superadmin', 'admin'])
                <div class="menu-item ">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}"
                        href="{{ route('admin.clients.index') }}">
                        <span class="menu-icon">
                            <i class="bi bi-people fs-2"></i>
                        </span>
                        <span class="menu-title">Company(Client)</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                @endrole

                <!-- its_palakk04 -->
                @role(['superadmin', 'admin'])
                <div class="menu-item ">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                        href="{{ route('admin.users.index') }}">
                        <span class="menu-icon">
                            <i class="bi bi-people fs-2"></i>
                        </span>
                        <span class="menu-title">Users(Team Members)</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                @endrole


                @role(['admin', 'member'])
                <div class="menu-item ">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->routeIs('admin.generated_urls.*') ? 'active' : '' }}"
                        href="{{ route('admin.generated_urls.index') }}">
                        <span class="menu-icon">
                            <i class="bi bi-people fs-2"></i>
                        </span>
                        <span class="menu-title">Generated Shot URls</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                @endrole
            </div>
            <!--end::Sidebar menu-->
            <!--begin::Teames-->
        </div>
    </div>
    <!--end::Wrapper-->
</div>