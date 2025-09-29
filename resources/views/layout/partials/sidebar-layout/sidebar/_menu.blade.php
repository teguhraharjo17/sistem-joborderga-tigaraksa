<!--begin::sidebar menu-->
<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <!--begin::Menu wrapper-->
    <div id="kt_app_sidebar_menu_wrapper"
        class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
        data-kt-scroll="true"
        data-kt-scroll-activate="true"
        data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
        data-kt-scroll-wrappers="#kt_app_sidebar_menu"
        data-kt-scroll-offset="5px"
        data-kt-scroll-save-state="true">

        <!--begin::Menu-->
        <div class="menu menu-column menu-rounded menu-sub-indention px-3"
             id="#kt_app_sidebar_menu"
             data-kt-menu="true"
             data-kt-menu-expand="false">

            <!-- ==================== -->
            <!-- Judul: Dashboard -->
            <!-- ==================== -->
            <div class="menu-item pt-5">
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Dashboard</span>
                </div>
            </div>

            <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}"
                   href="{{ route('dashboard.index') }}">
                    <span class="menu-icon">{!! getIcon('element-11', 'fs-2') !!}</span>
                    <span class="menu-title">Dashboard</span>
                </a>
            </div>

            <!-- ==================== -->
            <!-- Judul: Pelapor -->
            <!-- ==================== -->
            <div class="menu-item pt-5">
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Pelapor</span>
                </div>
            </div>

            <!-- Form Laporan Kerusakan -->
            <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('formlaporan.index') ? 'active' : '' }}"
                   href="{{ route('formlaporan.index') }}">
                    <span class="menu-icon">{!! getIcon('add-item', 'fs-2') !!}</span>
                    <span class="menu-title">Form Laporan Kerusakan</span>
                </a>
            </div>

            <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('listlaporankerusakan.index') ? 'active' : '' }}"
                href="{{ route('listlaporankerusakan.index') }}">
                    <span class="menu-icon">{!! getIcon('wrench', 'fs-2') !!}</span>
                    <span class="menu-title">List Laporan Kerusakan</span>
                </a>
            </div>

            <!-- ==================== -->
            <!-- Judul: GA -->
            <!-- ==================== -->
            <div class="menu-item pt-5">
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">GA</span>
                </div>
            </div>

            <!-- Laporan Kerja -->
            <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('laporankerja.index') ? 'active' : '' }}" href="{{ route('laporankerja.index') }}">
                    <span class="menu-icon">{!! getIcon('document', 'fs-2') !!}</span>
                    <span class="menu-title">Laporan Kerja</span>
                </a>
            </div>

            <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('progresskerja.index') ? 'active' : '' }}"
                href="{{ route('progresskerja.index') }}">
                    <span class="menu-icon">{!! getIcon('chart-line', 'fs-2') !!}</span>
                    <span class="menu-title">Progress Kerja</span>
                </a>
            </div>

        </div>
        <!--end::Menu-->

    </div>
    <!--end::Menu wrapper-->
</div>
<!--end::sidebar menu-->
