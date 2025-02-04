<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">

                <div class="navbar-brand-box horizontal-logo">
                    <a href="{{ route('root') }}" class="logo logo-dark">
                        <span class="logo-sm">
                            <img style="margin-right: 4px"
                                src="{{ asset('storage/' . $global_pengaturan_website->logo) }}" alt=""
                                height="45">
                            <span class="display-4"
                                style="font-size: 1.2rem; margin-right: 10px">{{ $global_pengaturan_website->nama }}</span>
                            <span class="text-sm text-muted"
                                style="font-size: 0.8rem; font-style:italic">{{ $global_pengaturan_website->deskripsi }}</span>
                        </span>
                        <span class="logo-lg">
                            <img style="margin-right: 4px"
                                src="{{ asset('storage/' . $global_pengaturan_website->logo) }}" alt=""
                                height="45">
                            <span class="fw-bold text-info"
                                style="font-size: 1.2rem; margin-right: 10px">{{ $global_pengaturan_website->nama }}</span>
                            <span class="text-sm text-muted"
                                style="font-size: 0.8rem; font-style:italic">{{ $global_pengaturan_website->deskripsi }}</span>
                        </span>
                    </a>

                    <a href="{{ route('root') }}" class="logo logo-light">
                        <span class="logo-sm">
                            <img style="margin-right: 4px"
                                src="{{ asset('storage/' . $global_pengaturan_website->logo) }}" alt=""
                                height="45">
                            <span class="fw-bold text-info"
                                style="font-size: 1.2rem; margin-right: 7px">{{ $global_pengaturan_website->nama }}</span>
                            <span class="text-sm text-muted"
                                style="font-size: 0.8rem; font-style:italic">{{ $global_pengaturan_website->deskripsi }}</span>
                        </span>
                        <span class="logo-lg">
                            <img style="margin-right: 4px"
                                src="{{ asset('storage/' . $global_pengaturan_website->logo) }}" alt=""
                                height="45">
                            <span class="fw-bold text-info"
                                style="font-size: 1.2rem; margin-right: 7px">{{ $global_pengaturan_website->nama }}</span>
                            <span class="text-sm text-muted"
                                style="font-size: 0.8rem; font-style:italic">{{ $global_pengaturan_website->deskripsi }}</span>
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                    id="topnav-hamburger-icon">
                    <span class="d-flex">
                        <img src="{{ asset('storage/' . $global_pengaturan_website->logo) }}" alt=""
                            height="40">
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <span class="fw-bold text-info text-xs"
                                style="font-size: 0.8rem;">{{ $global_pengaturan_website->nama }}</span>
                            <div class="text-sm text-muted" style="font-size: 0.5rem; font-style:italic">
                                {{ $global_pengaturan_website->deskripsi }}</div>
                        </div>
                    </span>
                </button>
            </div>

            <div class="d-flex align-items-center">
                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                        data-toggle="fullscreen">
                        <i class='bx bx-fullscreen fs-22'></i>
                    </button>
                </div>

                <div class="ms-1 header-item">
                    <button type="button"
                        class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                        <i class='bx bx-moon fs-22'></i>
                    </button>
                </div>

                @if (auth()->user()->peran == 'POLISI')
                    <div class="ms-1 header-item">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle">
                            <i class='bx bx-star fs-22'></i>
                            <span
                                class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-info">
                                {{ auth()->user()->total_poin }}<span class="visually-hidden">unread
                                    messages</span></span>
                        </button>
                    </div>
                @endif

                <div class="dropdown topbar-head-dropdown ms-1 header-item" id="notificationDropdown">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                        id="page-header-notifications-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                        aria-haspopup="true" aria-expanded="false">
                        <i class='bx bx-bell fs-22'></i>
                        <span
                            class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">0<span
                                class="visually-hidden"></span></span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                        aria-labelledby="page-header-notifications-dropdown">
                        <div class="dropdown-head bg-primary bg-pattern rounded-top">
                            <div class="p-3 d-flex gap-2 justify-content-between align-items-start border-bottom">
                                <h6 class="m-0 fs-16 fw-semibold text-white">Notifikasi</h6>
                                <div class="d-flex gap-2">
                                    <a href="{{route('notifikasi.histori')}}" class="btn btn-sm btn-soft-secondary">
                                        <i class="bx bx-list-ul"></i> Lihat Semua
                                    </a>
                                </div>
                            </div>


                            <div class="px-2 pt-2">
                                <ul class="nav nav-tabs dropdown-tabs nav-tabs-custom" data-dropdown-tabs="true"
                                    id="notificationItemsTab" role="tablist">
                                    <li class="nav-item waves-effect waves-light">
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="tab-content position-relative" id="notificationItemsTabContent">
                            <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                                <div data-simplebar class="pe-2" style="max-height: 300px; overflow-y: auto;">
                                    {{-- generating in js --}}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>



                <div class="dropdown ms-sm-3 header-item">
                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            @php
                                $profilePhoto = \App\Http\Controllers\Helpers\ProfilePhoto::get(
                                    auth()->user()->avatar,
                                    auth()->user()->name,
                                );
                            @endphp
                            <img class="rounded-circle header-profile-user" src="{{ $profilePhoto }}"
                                alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span
                                    class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ Auth::user()->name }}</span>
                                <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">
                                    {{ auth()->user()->peran }}
                                </span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <a class="dropdown-item" href="{{ route('profil') }}"><i
                                class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Profil Anda</span></a>

                        @if (auth()->user()->peran == 'POLISI')
                            <a class="dropdown-item" href="#"><i
                                    class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span
                                    class="align-middle">Total Poin :
                                    <b>{{ auth()->user()->total_poin }}</b></span></a>
                        @endif

                        <a class="dropdown-item " href="javascript:void();"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                class="bx bx-power-off font-size-16 align-middle me-1"></i> <span
                                key="t-logout">@lang('translation.logout')</span></a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
