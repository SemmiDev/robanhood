@extends('layouts.master')
@section('title')
    Manajemen Kasus
@endsection

@section('content')
    @component('components.breadcrumb', [
        'title' => 'Manajemen Kasus',
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
                        <form method="GET" action="{{ route('manajemenKasus') }}" class="mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- Search Input -->
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="form-group">
                                                <input type="text" name="q" value="{{ request()->get('q') }}"
                                                    class="form-control" placeholder="Cari kasus...">
                                            </div>
                                        </div>

                                        <!-- Status Dropdown -->
                                        <div class="col-12 col-md-6 col-lg-2">
                                            <div class="form-group">
                                                <select class="form-select" name="status" data-choices
                                                    data-choices-search-false>
                                                    <option value="">Status Penanganan</option>
                                                    <option value="MENUNGGU"
                                                        {{ request()->get('status') == 'MENUNGGU' ? 'selected' : '' }}>
                                                        Menunggu
                                                    </option>
                                                    <option value="DALAM_PROSES"
                                                        {{ request()->get('status') == 'DALAM_PROSES' ? 'selected' : '' }}>
                                                        Dalam Proses
                                                    </option>
                                                    <option value="SELESAI"
                                                        {{ request()->get('status') == 'SELESAI' ? 'selected' : '' }}>
                                                        Selesai
                                                    </option>
                                                    <option value="DITUTUP"
                                                        {{ request()->get('status') == 'DITUTUP' ? 'selected' : '' }}>
                                                        Ditutup
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Severity Dropdown -->
                                        <div class="col-12 col-md-6 col-lg-2">
                                            <div class="form-group">
                                                <select class="form-select" name="keparahan" data-choices
                                                    data-choices-search-false>
                                                    <option value="">Tingkat Keparahan</option>
                                                    <option value="RINGAN"
                                                        {{ request()->get('keparahan') == 'RINGAN' ? 'selected' : '' }}>
                                                        Ringan
                                                    </option>
                                                    <option value="SEDANG"
                                                        {{ request()->get('keparahan') == 'SEDANG' ? 'selected' : '' }}>
                                                        Sedang
                                                    </option>
                                                    <option value="BERAT"
                                                        {{ request()->get('keparahan') == 'BERAT' ? 'selected' : '' }}>
                                                        Berat
                                                    </option>
                                                    <option value="LAINNYA"
                                                        {{ request()->get('keparahan') == 'LAINNYA' ? 'selected' : '' }}>
                                                        Lainnya
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Period Dropdown -->
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="form-group">
                                                <select class="form-select" name="periode" data-choices
                                                    data-choices-search-false>
                                                    <option value=""
                                                        {{ request()->get('periode') == 'all' ? 'selected' : '' }}>
                                                        Semua Kasus
                                                    </option>
                                                    <option value="today"
                                                        {{ request()->get('periode') == 'today' ? 'selected' : '' }}>
                                                        Hari Ini
                                                    </option>
                                                    <option value="yesterday"
                                                        {{ request()->get('periode') == 'yesterday' ? 'selected' : '' }}>
                                                        Kemarin
                                                    </option>
                                                    <option value="last_7_days"
                                                        {{ request()->get('periode') == 'last_7_days' ? 'selected' : '' }}>
                                                        7 Hari Terakhir
                                                    </option>
                                                    <option value="last_30_days"
                                                        {{ request()->get('periode') == 'last_30_days' ? 'selected' : '' }}>
                                                        30 Hari Terakhir
                                                    </option>
                                                    <option value="this_month"
                                                        {{ request()->get('periode') == 'this_month' ? 'selected' : '' }}>
                                                        Bulan Ini
                                                    </option>
                                                    <option value="last_year"
                                                        {{ request()->get('periode') == 'last_year' ? 'selected' : '' }}>
                                                        Tahun Lalu
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="col-12 col-lg-2">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="ri-filter-3-line me-1"></i>
                                                Terapkan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            @forelse ($listKasus as $kasus)
                                <div class="col-xxl-3 col-sm-6 project-card">
                                    <div class="card card-height-100">
                                        <div class="card-body">
                                            <div class="d-flex flex-column h-100">
                                                <div class="d-flex">

                                                    <div class="flex-grow-1">
                                                        <p class="text-muted mb-4">
                                                            {{ \Carbon\Carbon::parse($kasus->waktu_kejadian)->diffForHumans() }}
                                                        </p>
                                                    </div>

                                                    <div class="flex-shrink-0">
                                                        <div class="d-flex gap-1 align-items-center">
                                                            <div class="dropdown">
                                                                <button
                                                                    class="btn btn-link text-muted p-1 mt-n2 py-0 text-decoration-none fs-15"
                                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="true">
                                                                    <i data-feather="more-horizontal" class="icon-sm"></i>
                                                                </button>

                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('manajemenKasus.show', ['id' => $kasus->id]) }}"><i
                                                                            class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                                        Lihat</a>

                                                                    @if (auth()->user()->peran == 'ADMIN')
                                                                    @if ($kasus->jenis != 'sos')
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('manajemenKasus.edit', ['id' => $kasus->id]) }}">
                                                                        <i
                                                                            class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                                        Ubah</a>
                                                                @endif

                                                                        <div class="dropdown-divider"></div>
                                                                        <form
                                                                            action="{{ route('manajemenKasus.destroy', ['id' => $kasus->id]) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button class="dropdown-item" type="submit"><i
                                                                                    class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                                                Hapus</button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column mb-2">

                                                    @php
                                                        $bukti = null;
                                                        foreach ($kasus->bukti_kasus as $buktiKasus) {
                                                            if (Str::startsWith($buktiKasus->mime, 'image/')) {
                                                                $bukti = $buktiKasus;
                                                                break; // Hentikan loop setelah menemukan yang cocok
                                                            }
                                                        }
                                                    @endphp

                                                    <div class="w-100 position-relative">
                                                        <a href="{{ route('manajemenKasus.show', ['id' => $kasus->id]) }}"
                                                            class="w-100">

                                                            @if ($kasus->jenis == 'kasus')
                                                                <img src="{{ $bukti ? asset('storage/' . $bukti->path) : asset('storage/' . $kasus->kategori_kasu->simbol) }}"
                                                                    alt="" class="w-100 rounded"
                                                                    style="height: 300px; object-fit: cover;">
                                                            @else
                                                                <img src="/sos.png" alt="" class="w-100 rounded"
                                                                    style="height: 300px; object-fit: cover;">
                                                            @endif
                                                        </a>
                                                        <div
                                                            class="position-absolute top-0 left-0 bg-info-subtle fw-bold text-info p-2 m-2 rounded">
                                                            {{ $kasus->kategori_kasu->nama ?? 'SOS' }}
                                                        </div>
                                                    </div>

                                                    <div class="flex-grow-1 mt-3">
                                                        <h5 class="mb-1 fs-14">
                                                            <a href="{{ route('manajemenKasus.show', ['id' => $kasus->id]) }}"
                                                                class="text-body">
                                                                {{ Str::limit($kasus->judul, 50) }}
                                                                {{$kasus->jenis == 'sos' ? 'SOS': ''}}
                                                                <!-- Batasi judul hingga 50 karakter -->
                                                            </a>
                                                        </h5>
                                                        <p class="text-muted mb-3">
                                                            {{ Str::limit($kasus->deskripsi, 100) }}
                                                            {{$kasus->jenis == 'sos' ? 'SOS': ''}}
                                                            <!-- Batasi deskripsi hingga 100 karakter -->
                                                        </p>
                                                    </div>

                                                    <div class="d-flex align-items-center gap-3">
                                                        <div
                                                            class="badge bg-info-subtle fw-bold text-info px-3 py-2 rounded-pill">
                                                            <i class="ri-timer-line me-1"></i>
                                                             {{str_replace('_', ' ', $kasus->status)}}
                                                        </div>

                                                        <div
                                                            class="badge bg-danger-subtle fw-bold text-danger px-3 py-2 rounded-pill">
                                                            <i class="ri-error-warning-line me-1"></i>
                                                            {{ $kasus->tingkat_keparahan }}
                                                        </div>



                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- end card body -->
                                        <div class="card-footer bg-transparent border-top-dashed py-2">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <div class="d-flex">
                                                        <div class="avatar-group">
                                                            @forelse ($kasus->anggota_penanganans as $anggota)
                                                                <?php
                                                                $profilePhoto = \App\Http\Controllers\Helpers\ProfilePhoto::get($anggota->user->avatar, $anggota->user->name);
                                                                ?>

                                                                <a href="javascript: void(0);" class="avatar-group-item"
                                                                    data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                                    data-bs-placement="top"
                                                                    title="{{ $anggota->peran . ': ' . $anggota->user->name }}">
                                                                    <div class="avatar-xxs">
                                                                        <img src="{{ $profilePhoto }}" alt=""
                                                                            class="rounded-circle img-fluid">
                                                                    </div>
                                                                </a>
                                                            @empty
                                                                <span class="text-muted"></span>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="flex-shrink-0">
                                                    <div class="text-muted">
                                                        <i class="ri-calendar-event-fill me-1 align-bottom"></i>
                                                        <span
                                                            class="text-muted">{{ \Carbon\Carbon::parse($kasus->waktu_kejadian)->format('d M, Y') }}</span>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <!-- end card footer -->
                                    </div>
                                    <!-- end card -->
                                </div>
                            @empty
                                <div class="noresult">
                                    <div class="text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                            colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                                        </lord-icon>
                                        <h5 class="mt-2">Belum ada data</h5>
                                    </div>
                                </div>
                            @endforelse
                            {{ $listKasus->appends(request()->query())->links() }}
                        </div>
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
    <script src="{{ URL::asset('build/libs/rater-js/index.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/rating.init.js') }}"></script>
@endsection
