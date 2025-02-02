@extends('layouts.master')
@section('title')
    Manajemen Anggota
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb', [
        'title' => 'Manajemen Anggota',
        'crumbs' => [
            'Dashboard' => route('root'),
        ],
    ])
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="listjs-table" id="customerList">
                        <div class="row g-4 mb-3">
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <form method="GET" action="{{ route('manajemenAnggota') }}"
                                        class="d-flex flex-wrap gap-1">
                                        <div class="mb-2" style="width: 200px;">
                                            <select class="select2-order-by form-control form-select" name="order_by">
                                                <option value="">Urutkan Berdasarkan</option>
                                                <option value="name"
                                                    {{ request()->get('order_by') == 'name' ? 'selected' : '' }}>Nama
                                                </option>
                                                <option value="total_poin"
                                                    {{ request()->get('order_by') == 'total_poin' ? 'selected' : '' }}>Total
                                                    Poin</option>
                                            </select>
                                        </div>

                                        <div class="mb-2"style="width: 150px;">
                                            <select class="select2-order-type form-control form-select" name="order_type">
                                                <option value="">Tipe Urutan</option>
                                                <option value="asc"
                                                    {{ request()->get('order_type') == 'asc' ? 'selected' : '' }}>Menaik
                                                </option>
                                                <option value="desc"
                                                    {{ request()->get('order_type') == 'desc' ? 'selected' : '' }}>Menurun
                                                </option>
                                            </select>
                                        </div>

                                        <div class="mb-2" style="width: 150px;">
                                            <select class="select2-status-aktif form-control form-select" name="status"
                                                style="width: 100%;">
                                                <option value="">Pilih Status</option>
                                                <option value="1"
                                                    {{ request()->get('status') == '1' ? 'selected' : '' }}>Aktif</option>
                                                <option value="0"
                                                    {{ request()->get('status') == '0' ? 'selected' : '' }}>Tidak Aktif
                                                    (Diblokir)
                                                </option>
                                            </select>
                                        </div>

                                        <div class="mb-2" style="width: 220px;">
                                            <select class="select2-status-aktif form-control form-select" name="acc"
                                                style="width: 100%;">
                                                <option value="">Pilih Konfirmasi Anggota</option>
                                                <option value="1" {{ request()->get('acc') == '1' ? 'selected' : '' }}>
                                                    Telah Di Setujui</option>
                                                <option value="0" {{ request()->get('acc') == '0' ? 'selected' : '' }}>
                                                    Belum Di Setujui
                                                </option>
                                            </select>
                                        </div>

                                        <div class="mb-2" style="width: 150px;">
                                            <select class="select2-peran form-control form-select" name="peran"
                                                style="width: 100%;">
                                                <option value="">Pilih Peran</option>
                                                <option value="ADMIN"
                                                    {{ request()->get('peran') == 'ADMIN' ? 'selected' : '' }}>Admin
                                                </option>
                                                <option value="POLISI"
                                                    {{ request()->get('peran') == 'POLISI' ? 'selected' : '' }}>Polisi
                                                </option>
                                                <option value="WARGA"
                                                    {{ request()->get('peran') == 'WARGA' ? 'selected' : '' }}>Warga
                                                </option>
                                            </select>
                                        </div>

                                        <div class="mb-2 search-box">
                                            <input type="text" name="q" value="{{ request()->get('q') }}"
                                                class="form-control search" placeholder="Pencarian...">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>

                                        <button type="submit" class="mb-2 btn btn-primary">Terapkan</button>
                                        <a href="{{ route('manajemenAnggota') }}" class="mb-2 btn btn-secondary">
                                            Bersihkan</a>
                                    </form>

                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-hover table-card mt-3 mb-1">
                            <table class="table align-middle table-nowrap mb-0" id="customerTable">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 50px;">
                                            No
                                        </th>
                                        <th data-sort="name" scope="col">Nama</th>
                                        <th data-sort="company_name" scope="col">Peran
                                        </th>
                                        <th data-sort="company_name" scope="col">Status Akun
                                        </th>
                                        <th data-sort="company_name" scope="col">Status Pendaftaran
                                        </th>
                                        <th data-sort="company_name" scope="col">Terakhir Login
                                        </th>
                                        <th data-sort="phone" scope="col">Total Poin</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    @forelse ($users as $user)
                                        <tr>
                                            <th scope="row">
                                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                            </th>
                                            <td class="name">
                                                <div class="d-flex gap-2 align-items-center">
                                                    <div class="flex-shrink-0">
                                                        @php
                                                            $profilePhoto = \App\Http\Controllers\Helpers\ProfilePhoto::get(
                                                                $user->avatar,
                                                                $user->name,
                                                            );
                                                        @endphp
                                                        <img src="{{ $profilePhoto }}" alt=""
                                                            class="avatar-xs rounded-circle">
                                                    </div>
                                                    <div class="d-flex flex-column ml-2 gap-2">
                                                        <div class="flex-grow-1 d-flex flex-column ms-2 fs-16 name">
                                                            <a
                                                                href="{{ route('manajemenAnggota.show', ['id' => $user->id]) }}">
                                                                {{ $user->name }}
                                                            </a>
                                                            @if ($user->peran == 'POLISI')

                                                            <div>
                                                                <span class="text-muted" style="font-size: 10px">
                                                                    {{ str_replace('_', ' ', $user->status) }}
                                                                </span>
                                                            </div>
                                                            @endif
                                                        </div>

                                                        @if ($user->peran != 'WARGA')
                                                            <div>
                                                                <div class="badge bg-primary-subtle fs-12 text-primary">
                                                                    {{ $user->profil_polisis->first()->pangkat_polisi->nama ?? '-' }}
                                                                </div>
                                                                <div class="badge bg-info-subtle fs-12 text-info">
                                                                    {{ $user->profil_polisis->first()->unit_polisi->nama ?? '-' }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="name">
                                                {{ $user->peran ?? '-' }}
                                            </td>
                                            <td class="name">
                                                @if (auth()->user()->id != $user->id)
                                                    <form action="{{ route('update.aktifAnggota') }}" method="POST"
                                                        id="update-status-form-{{ $user->id }}">
                                                        @csrf
                                                        <input type="hidden" name="user_id"
                                                            value="{{ $user->id }}">
                                                        <select name="aktif"
                                                            class="select2-status-akun form-select form-control"
                                                            onchange="submitForm({{ $user->id }})">
                                                            <option value="1"
                                                                {{ $user->aktif == 1 ? 'selected' : '' }}>Aktif</option>
                                                            <option value="0"
                                                                {{ $user->aktif == 0 ? 'selected' : '' }}>Tidak Aktif
                                                                (Blokir)
                                                            </option>
                                                        </select>
                                                    </form>
                                                @else
                                                @endif
                                            </td>


                                            <td class="name">
                                                @if (auth()->user()->id != $user->id)
                                                    <form action="{{ route('update.status') }}" method="POST"
                                                        id="status-form">
                                                        @csrf
                                                        <input type="hidden" name="user_id"
                                                            value="{{ $user->id }}">
                                                        <select name="acc" id="acc-status"
                                                            {{ $user->acc == 1 ? 'disabled' : '' }}
                                                            class="select2-status-pendaftaran form-select form-control"
                                                            onchange="this.form.submit()">
                                                            <option value="0"
                                                                {{ $user->acc == 0 ? 'selected' : '' }}>Belum Di Setujui
                                                            </option>
                                                            <option value="1"
                                                                {{ $user->acc == 1 ? 'selected' : '' }}>Sudah Di Setujui
                                                            </option>
                                                        </select>
                                                    </form>
                                                @else
                                                @endif
                                            </td>

                                            <td class="company_name">
                                                <span class="text-muted">
                                                    {{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('d M, Y H:i') : '-' }}
                                                </span>

                                            </td>

                                            <td class="company_name">
                                                @if ($user->peran == 'POLISI')
                                                    <span class="badge bg-info-subtle fs-12 text-info">
                                                        {{ $user->total_poin ?? '0' }}
                                                    </span>
                                                @endif
                                            </td>


                                            <td>
                                                <ul class="list-inline hstack gap-2 mb-0">
                                                    <li class="list-inline-item edit" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top" title="Whatsapp">
                                                        <a href="https://api.whatsapp.com/send?phone={{ $user->no_whatsapp }}"
                                                            target="_blank" class="text-muted d-inline-block">
                                                            <i class="ri-whatsapp-line fs-22 text-success"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item call" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top" title="Telepon">
                                                        <a href="tel:{{ $user->no_whatsapp }}"
                                                            class="text-muted d-inline-block">
                                                            <i class="ri-phone-line fs-22 text-warning"></i>
                                                        </a>
                                                    </li>

                                                    <li class="list-inline-item email" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top" title="Email">
                                                        <a href="mailto:{{ $user->email }}"
                                                            class="text-muted d-inline-block">
                                                            <i class="ri-mail-line fs-22 text-danger"></i>
                                                        </a>
                                                    </li>

                                                    <li class="list-inline-item details" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Profil Anggota">
                                                        <a href="{{ route('manajemenAnggota.show', ['id' => $user->id]) }}"
                                                            class="text-muted d-inline-block">
                                                            <i class="ri-eye-fill fs-22 text-info"></i>
                                                        </a>
                                                    </li>

                                                </ul>
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($users->isEmpty())
                            <div class="noresult">
                                <div class="text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                        colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                                    </lord-icon>
                                    <h5 class="mt-2">Belum ada data</h5>
                                </div>
                            </div>
                        @endif

                        {{ $users->appends(request()->query())->links() }}
                    </div>
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.getElementById("acc-status").addEventListener("change", function() {
            this.form.submit();
        });

        function submitForm(userId) {
            document.getElementById('update-status-form-' + userId).submit();
        }
    </script>
@endsection
