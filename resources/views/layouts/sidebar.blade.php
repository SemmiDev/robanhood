<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid" style="z-index: 999">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">

                @if (auth()->user()->peran == 'WARGA')
                    <li class="menu-title"><span>@lang('translation.menu')</span></li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('root*') ? 'text-primary' : '' }}"
                            href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarDashboards">
                            <i class="bx bxs-dashboard"></i> <span>
                                Dashboard
                            </span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarDashboards">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('root') }}" class="nav-link">Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('realtimeSekitar') }}" class="nav-link">
                                        Lihat Sekitar Realtime</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (auth()->user()->peran == 'ADMIN')
                    <li class="menu-title"><span>@lang('translation.menu')</span></li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('root*') ? 'text-primary' : '' }}"
                            href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarDashboards">
                            <i class="bx bxs-dashboard"></i> <span>
                                Dashboard
                            </span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarDashboards">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('root') }}" class="nav-link">Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('realtimeManajemenKasus') }}" class="nav-link">Laporan Kasus
                                        Realtime</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('petaKasus') }}" class="nav-link">Peta Polisi dan Kasus Realtime</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (auth()->user()->peran == 'POLISI')
                    <li class="menu-title"><span>@lang('translation.menu')</span></li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('root*') ? 'text-primary' : '' }}"
                            href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarDashboards">
                            <i class="bx bxs-dashboard"></i> <span>
                                Dashboard
                            </span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarDashboards">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('root') }}" class="nav-link">Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('scanKasusTerdekat') }}" class="nav-link">Scan Kasus Terdekat Realtime</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (auth()->user()->peran == 'ADMIN' || auth()->user()->peran == 'POLISI')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('manajemenKasus*') ? 'text-primary' : '' }}"
                            href="{{ route('manajemenKasus') }}" role="button" aria-expanded="false"
                            aria-controls="sidebarLayouts">
                            <i class="bx bx-layer"></i> <span>Manajemen Kasus</span><span
                                class="badge badge-pill bg-danger">@lang('translation.hot')</span>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('notifikasi.*') ? 'text-primary' : '' }}"
                        href="{{ route('notifikasi.histori') }}" role="button" aria-expanded="false"
                        aria-controls="sidebarLayouts">
                        <i class="bx bx-bell"></i> <span>Notifikasi</span>
                        <span class="badge badge-pill bg-danger">@lang('translation.hot')</span>
                    </a>
                </li>

                @if (auth()->user()->peran == 'POLISI')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('profil*') ? 'text-primary' : '' }}"
                            href="{{ route('profil') }}" role="button" aria-expanded="false"
                            aria-controls="sidebarLayouts">
                            <i class="ri-history-line"></i> <span>Riwayat</span><span
                                class="badge badge-pill bg-danger">@lang('translation.hot')</span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->peran == 'ADMIN')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('leaderboard*') ? 'text-primary' : '' }}"
                            href="{{ route('leaderboard') }}" role="button" aria-expanded="false"
                            aria-controls="sidebarLayouts">
                            <i class="bx bx-star"></i> <span>Leaderboard Anggota</span><span
                                class="badge badge-pill bg-danger">@lang('translation.hot')</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('manajemenAnggota*') ? 'text-primary' : '' }}"
                            href="{{ route('manajemenAnggota') }}" role="button" aria-expanded="false"
                            aria-controls="sidebarApps">
                            <i class="bx bx-group"></i> <span>Manajemen Anggota</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('manajemenKategoriKasus*') ||
                        request()->routeIs('manajemenPengaturanWebsite*') ||
                        request()->routeIs('manajemenPangkat*') ||
                        request()->routeIs('manajemenUnit*')
                            ? 'text-primary'
                            : '' }}"
                            href="#sidebarPages" data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarPages">
                            <i class="bx bx-file"></i> <span>Manajemen Website</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarPages">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('manajemenKategoriKasus') }}"
                                        class="nav-link {{ request()->routeIs('manajemenKategoriKasus*') ? 'text-primary' : '' }}">Manajemen
                                        Kategori
                                        Kasus</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('manajemenPengaturanWebsite') }}"
                                        class="nav-link {{ request()->routeIs('manajemenPengaturanWebsite*') ? 'text-primary' : '' }}">Pengaturan
                                        Website</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('manajemenPangkat') }}"
                                        class="nav-link {{ request()->routeIs('manajemenPangkat*') ? 'text-primary' : '' }}">Manajemen
                                        Pangkat</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('manajemenUnit') }}"
                                        class="nav-link {{ request()->routeIs('manajemenUnit*') ? 'text-primary' : '' }}">Manajemen
                                        Unit /
                                        Satuan</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
