@extends('layouts.master')
@section('title')
    Detail Kasus
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card mt-n4 mx-n4 mb-n5">
                <div class="bg-warning-subtle">
                    <div class="card-body pb-4 mb-5">
                        <div class="row">
                            <div class="col-md">
                                <div class="row align-items-center">
                                    <div class="col-md-auto">
                                        <div class="avatar-md mb-md-0 mb-4">
                                            <div class="avatar-title bg-white rounded-circle">
                                                @if ($kasus->jenis == 'kasus')
                                                    <img src="{{ asset('storage/' . $kasus->kategori_kasu->simbol) }}"
                                                        alt="" class="avatar-sm" />
                                                @else
                                                    <img src="/sos.png" alt="" class="avatar-sm" />
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md">
                                        <h4 class="fw-semibold" id="ticket-title">{{ $kasus->judul }}</h4>
                                        <div class="hstack gap-3 flex-wrap">
                                            <div class="text-muted"><i class="las la-people-carry me-1"></i><span
                                                    id="ticket-client">
                                                    Oleh <span
                                                        class="fw-bold">{{ $kasus->user->name ?? 'Tidak Diketahui' }}</span>
                                                </span></div>
                                            <div class="vr"></div>
                                            <button type="button" class="btn btn-sm rounded-pill btn-success btn-label"><i
                                                    class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                                                {{ $kasus->status }}
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning btn-label rounded-pill"><i
                                                    class="ri-error-warning-line label-icon align-middle rounded-pill fs-16 me-2 "></i>
                                                {{ $kasus->tingkat_keparahan }}

                                            </button>

                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                            <!--end col-->
                            <div class="col-md-auto mt-md-0 mt-4">

                                @php
                                    $peran = auth()->user()->peran;
                                    $userId = auth()->user()->id;

                                    $canEdit = false;
                                    $canDelete = false;
                                    $canFeedback = false;

                                    // -------------- warga --------------
                                    if ($peran == 'WARGA') {
                                        if ($kasus->status == 'MENUNGGU') {
                                            $canEdit = true;
                                            $canDelete = true;
                                        }

                                        if ($kasus->status == 'SELESAI') {
                                            $canFeedback = true;
                                        }
                                    }

                                    // -------------- ADMIN --------------
                                    $canAssign = false;
                                    $canSwithStatus = false;

                                    if ($peran == 'ADMIN') {
                                        $canEdit = true;
                                        $canDelete = true;
                                        $canSwithStatus = true;

                                        if (
                                            $kasus->status == 'MENUNGGU' ||
                                            $kasus->status == 'DITUGASKAN' ||
                                            $kasus->status == 'DALAM_PROSES'
                                        ) {
                                            $canAssign = true;
                                        }
                                    }

                                    // -------------- POLISI --------------
                                    $canMarkAsCompletedIndividual = false;
                                    $canHandle = false;
                                    $canCancelHandle = false;

                                    if ($peran == 'POLISI') {
                                        if ($kasus->status != 'SELESAI' && $kasus->status != 'DITUTUP') {
                                            $penanganan = $kasus->anggota_penanganans
                                                ->where('user_id', '=', $userId)
                                                ->first();
                                            if ($penanganan) {
                                                if (!$penanganan->selesai) {
                                                    $canMarkAsCompletedIndividual = true;
                                                    $canCancelHandle = true;
                                                }

                                                if ($penanganan->aktif == 0) {
                                                    $canMarkAsCompletedIndividual = false;
                                                    $canCancelHandle = false;
                                                }
                                            } else {
                                                $canHandle = true;
                                            }
                                        }
                                    }

                                    if ($kasus->jenis == 'sos') {
                                        $canEdit = false;
                                    }
                                @endphp

                                <div class="hstack gap-1 flex-wrap justify-content-start ms-2">
                                    @if ($canAssign)
                                        <button data-bs-toggle="modal" data-bs-target="#tugaskan-modal"
                                            class="btn btn-primary waves-effect waves-light">
                                            <i class="ri-task-line align-bottom me-1"></i> Tugaskan
                                        </button>

                                        <div class="hstack gap-3 fs-16">
                                            <div id="tugaskan-modal" class="modal modal-xl fade" tabindex="-1"
                                                aria-labelledby="tugaskanModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-md">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="tugaskanModalLabel">
                                                                Tugakan Anggota
                                                            </h5>

                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form
                                                                action="{{ route('manajemenKasus.assign', ['id' => $kasus->id]) }}"
                                                                method="POST">
                                                                @csrf

                                                                <div class="mb-3">
                                                                    <label for="anggota[]"
                                                                        class="form-label text-start">Langsung Pilih Anggota
                                                                        Dari Peta Kasus</label>
                                                                    <div>
                                                                        <a href="{{ route('petaKasusAssign', ['id' => $kasus->id]) }}"
                                                                            class="btn btn-primary waves-effect waves-light"><i
                                                                                class="ri-map-pin-line"></i> Buka Peta</a>
                                                                    </div>
                                                                </div>

                                                                <!-- Separator -->
                                                                <hr class="my-4">
                                                                <!-- Optional: You can use custom styling like 'border-top: 2px solid #ccc;' -->

                                                                <div class="mb-3">
                                                                    <label for="anggota[]" class="form-label text-start">10
                                                                        Anggota Terdekat Yang Sedang Bertugas Dari Lokasi
                                                                        Kejadian</label>
                                                                    @if (count($listAnggotaPolisiSedangBertugas) > 0)
                                                                        <div class="form-check">
                                                                            @forelse ($listAnggotaPolisiSedangBertugas as $i => $anggota)
                                                                                <input class="form-check-input"
                                                                                    type="checkbox"
                                                                                    value="{{ $anggota->id }}"
                                                                                    id="anggota_{{ $anggota->id }}"
                                                                                    name="anggota[]">
                                                                                <label class="form-check-label"
                                                                                    for="anggota_{{ $anggota->id }}">
                                                                                    {{ $anggota->name }}
                                                                                    <span class="text-info">
                                                                                        {{ ' ± ' . $anggota->jarak . ' Meter' }}
                                                                                    </span>
                                                                                    Dari lokasi kejadian.
                                                                                </label><br>
                                                                            @empty
                                                                            @endforelse
                                                                        </div>
                                                                        <div class="mt-4">
                                                                            <button class="btn btn-success w-100"
                                                                                type="submit">Tugaskan</button>
                                                                        </div>
                                                                    @else
                                                                        <div>

                                                                            <span class="text-muted">
                                                                                Tidak ada anggota yang berjarak
                                                                                {{ $global_pengaturan_website->radius_notifikasi }}
                                                                                KM dari lokasi kejadian.
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                </div>


                                                            </form>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($canSwithStatus)
                                        <button data-bs-toggle="modal" data-bs-target="#ubah-status-kasus-modal"
                                            class="btn btn-secondary waves-effect waves-light">
                                            <i class="ri-refresh-line align-bottom me-1"></i> Ubah Status Kasus
                                        </button>

                                        <div class="hstack gap-3 fs-16">
                                            <div id="ubah-status-kasus-modal" class="modal fade" tabindex="-1"
                                                aria-labelledby="tugaskanModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-md">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="tugaskanModalLabel">
                                                                Ubah status kasus
                                                            </h5>

                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form
                                                                action="{{ route('manajemenKasus.switchStatus', ['id' => $kasus->id]) }}"
                                                                method="POST">
                                                                @csrf

                                                                <div class="mb-3">
                                                                    <label for="status"
                                                                        class="form-label text-start">Status</label>
                                                                    <select name="status" class="form-control">
                                                                        <option value="MENUNGGU"
                                                                            {{ $kasus->status == 'MENUNGGU' ? 'selected' : '' }}>
                                                                            Menunggu</option>
                                                                        <option value="DALAM_PROSES"
                                                                            {{ $kasus->status == 'DALAM_PROSES' ? 'selected' : '' }}>
                                                                            Dalam Proses</option>
                                                                        <option value="SELESAI"
                                                                            {{ $kasus->status == 'SELESAI' ? 'selected' : '' }}>
                                                                            Selesai</option>
                                                                        <option value="DITUTUP"
                                                                            {{ $kasus->status == 'DITUTUP' ? 'selected' : '' }}>
                                                                            Ditutup</option>
                                                                    </select>
                                                                </div>

                                                                <div class="mt-4">
                                                                    <button class="btn btn-success w-100"
                                                                        type="submit">Ubah</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                    @if ($canMarkAsCompletedIndividual)
                                        <button data-bs-toggle="modal" data-bs-target="#tandai-sebagai-selesai"
                                            class="btn btn-soft-secondary waves-effect waves-light">
                                            <i class="ri-check-double-line align-bottom me-1"></i>
                                            Tandai Sebagai Selesai
                                        </button>

                                        <div class="hstack gap-3 fs-16">
                                            <div id="tandai-sebagai-selesai" class="modal modal-lg fade" tabindex="-1"
                                                aria-labelledby="tandai-sebagai-selesai-label" aria-hidden="true">
                                                <div class="modal-dialog modal-md">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="tandai-sebagai-selesai-label">
                                                                Silahkan upload bukti pekerjaan terlebih dahulu.
                                                            </h5>

                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form
                                                                action="{{ route('manajemenKasus.storeBuktiPekerjaan', ['id' => $kasus->id]) }}"
                                                                method="POST" enctype="multipart/form-data">
                                                                @csrf

                                                                <!-- File Upload (Multiple) -->
                                                                <div class="mb-3">
                                                                    <label for="files" class="form-label">Unggah Bukti
                                                                        Pekerjaan</label>
                                                                    <input class="form-control" type="file"
                                                                        id="files" name="files[]" multiple
                                                                        accept="image/*,video/*,application/pdf">
                                                                    <small class="text-muted">Anda bisa mengunggah lebih
                                                                        dari satu file (gambar, video, PDF).</small>

                                                                    <!-- Preview List -->
                                                                    <ul id="file-list" class="list-group mt-2"></ul>
                                                                </div>

                                                                <!-- Submit Button -->
                                                                <div class="mt-4">
                                                                    <button class="btn btn-success w-100"
                                                                        type="submit">Kirim</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($canHandle)
                                        <form action="{{ route('manajemenKasus.handle', $kasus->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-soft-secondary waves-effect waves-light">
                                                <i class="ri-user-follow-line align-bottom me-1"></i>
                                                Tangani Kasus Ini
                                            </button>
                                        </form>
                                    @endif

                                    @if ($canCancelHandle)
                                        <button data-bs-toggle="modal" data-bs-target="#batalkan-menangani-kasus"
                                            class="btn btn-soft-danger waves-effect waves-light">
                                            <i class="ri-user-unfollow-line align-bottom me-1"></i>
                                            Batalkan Menangani Kasus Ini
                                        </button>

                                        <div class="hstack gap-3 fs-16">
                                            <div id="batalkan-menangani-kasus" class="modal modal-lg fade" tabindex="-1"
                                                aria-labelledby="batalkan-menangani-kasus-label" aria-hidden="true">
                                                <div class="modal-dialog modal-md">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="batalkan-menangani-kasus-label">
                                                                Batalkan menangani kasus ini.
                                                            </h5>

                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form
                                                                action="{{ route('manajemenKasus.unhandle', ['id' => $kasus->id]) }}"
                                                                method="POST">
                                                                @csrf

                                                                <!-- Input Keterangan -->
                                                                <div class="mb-3">
                                                                    <label for="keterangan"
                                                                        class="form-label fw-semibold">Keterangan</label>
                                                                    <textarea class="form-control" required id="keterangan" name="keterangan" rows="3" placeholder="Keterangan"></textarea>
                                                                </div>

                                                                <!-- Submit Button -->
                                                                <div class="mt-4">
                                                                    <button class="btn btn-success w-100"
                                                                        type="submit">Kirim</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($canFeedback)
                                        <button data-bs-toggle="modal" data-bs-target="#feedback"
                                            class="btn btn-info waves-effect waves-light">
                                            <i class="ri-feedback-line align-bottom me-1"></i> Kritik & Saran
                                        </button>

                                        <div class="hstack gap-3 fs-16">
                                            <div id="feedback" class="modal fade" tabindex="-1"
                                                aria-labelledby="tugaskanModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-md">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="tugaskanModalLabel">
                                                                Silahkan masukkan kritik dan saran
                                                            </h5>

                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form
                                                                action="{{ route('manajemenKasus.feedback', ['id' => $kasus->id]) }}"
                                                                method="POST">
                                                                @csrf

                                                                <div class="mb-3">
                                                                    <textarea class="form-control" id="feedback" name="feedback"
                                                                        placeholder="Salut untuk kepolisian yang bergerak cepat dalam menangani kasus ini! Semoga pelayanan seperti ini terus dipertahankan demi keamanan bersama. Terima kasih! 😄"
                                                                        rows="5">{{ $kasus->feedback ?? 'Salut untuk kepolisian yang bergerak cepat dalam menangani kasus ini! Semoga pelayanan seperti ini terus dipertahankan demi keamanan bersama. Terima kasih! 😄' }}</textarea>
                                                                </div>

                                                                <div class="mt-4">
                                                                    <button class="btn btn-success w-100"
                                                                        type="submit">Kirim</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($canEdit)
                                        <a href="{{ route('manajemenKasus.edit', $kasus->id) }}"
                                            class="btn btn-info waves-effect waves-light">
                                            <i class="ri-pencil-line align-bottom me-1"></i>
                                            Edit
                                        </a>
                                    @endif

                                    @if ($canDelete)
                                        <form id="delete-form-{{ $kasus->id }}"
                                            action="{{ route('manajemenKasus.destroy', $kasus->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <button type="button" class="btn btn-danger waves-effect waves-light"
                                            onclick="confirmDelete({{ $kasus->id }})">
                                            <i class="ri-delete-bin-line align-bottom me-1"></i>
                                            Hapus
                                        </button>

                                        <script>
                                            function confirmDelete(kasusId) {
                                                Swal.fire({
                                                    title: 'Apakah Anda yakin?',
                                                    text: "Kasus ini akan dihapus secara permanen!",
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#d33',
                                                    cancelButtonColor: '#6c757d',
                                                    confirmButtonText: 'Ya, hapus!',
                                                    cancelButtonText: 'Batal'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        document.getElementById(`delete-form-${kasusId}`).submit();
                                                    }
                                                });
                                            }
                                        </script>
                                    @endif

                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div><!-- end card body -->
                </div>
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    <div class="row">
        <div class="col-xxl-9">
            <div class="card">
                <div class="card-body p-4">
                    <h6 class="text-uppercase mb-3">Deskripsi Kejadian</h6>
                    <p class="text-muted">{{ $kasus->deskripsi ?? '-' }}</p>

                    <h6 class="text-uppercase mb-3">Alamat</h6>
                    <p class="text-muted">{{ $kasus->alamat ?? '-' }}</p>

                    <h6 class="text-uppercase mb-3">Lokasi Kejadian</h6>

                    <div class="d-flex gap-1 justify-items-center align-items-center flex-wrap">

                        @if ($kasus->jenis == 'kasus')
                            <a href="{{ route('manajemenKasus.rute', ['id' => $kasus->id]) }}"
                                class="btn btn-outline-secondary btn-load">
                                <span class="d-flex align-items-center">
                                    <span class="spinner-grow flex-shrink-0" role="status">
                                        <span class="visually-hidden">Lihat Lokasi</span>
                                    </span>
                                    <span class="flex-grow-1 ms-2">
                                        Rute ke Lokasi
                                    </span>
                                </span>
                            </a>
                            <a target="_blank"
                                href="https://www.google.com/maps/search/?api=1&query={{ $kasus->latitude }},{{ $kasus->longitude }}"
                                class="btn btn-danger waves-effect waves-light"><i class="ri-map-pin-line"></i>
                                Google Maps
                            </a>
                        @else
                            <a href="{{ route('manajemenKasus.ruteSos', ['id' => $kasus->id]) }}"
                                class="btn btn-outline-secondary btn-load">
                                <span class="d-flex align-items-center">
                                    <span class="spinner-grow flex-shrink-0" role="status">
                                        <span class="visually-hidden">Lihat Lokasi</span>
                                    </span>
                                    <span class="flex-grow-1 ms-2">
                                        Rute ke Lokasi
                                    </span>
                                </span>
                            </a>
                            <a target="_blank"
                                href="https://www.google.com/maps/search/?api=1&query={{ $kasus->user->latitude_terakhir }},{{ $kasus->user->longitude_terakhir }}"
                                class="btn btn-danger waves-effect waves-light"><i class="ri-map-pin-line"></i>
                                Google Maps
                            </a>
                        @endif

                        <!-- Tombol Chat -->
                        <a href="{{ route('manajemenKasus.chat', ['id' => $kasus->id]) }}"
                            class="btn btn-primary waves-effect waves-light position-relative">
                            <i class="ri-chat-3-line"></i>
                            @if ($totalChat > 0)
                                {{ $totalChat }}
                            @else
                            @endif
                            Chat
                        </a>





                    </div>
                </div>

            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Kasus</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-semibold">Pelapor</td>
                                    <td>
                                        @if ($kasus->user)
                                            <a href="{{ route('manajemenAnggota.edit', ['id' => $kasus->user->id]) }}">
                                                {{ $kasus->user->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak diketahui</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Hubungi Pelapor</td>
                                    <td>
                                        <ul class="list-inline d-flex gap-2 mb-0">
                                            <li class="list-inline-item" data-bs-toggle="tooltip" title="WhatsApp">
                                                <a href="https://api.whatsapp.com/send?phone={{ $kasus->user->no_whatsapp ?? '' }}&text={{ urlencode('Halo Bapak/Ibu yang kami hormati, ...') }}"
                                                    target="_blank">
                                                    <i class="ri-whatsapp-line fs-22 text-success"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip" title="Telepon">
                                                <a href="tel:{{ $kasus->user->no_whatsapp ?? '' }}">
                                                    <i class="ri-phone-line fs-22 text-warning"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip" title="Email">
                                                <a href="mailto:{{ $kasus->user->email ?? '' }}">
                                                    <i class="ri-mail-line fs-22 text-danger"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Kategori</td>
                                    <td>{{ $kasus->kategori_kasu->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Tim Penanganan</td>
                                    <td>
                                        @php
                                            $anggotaLain = $kasus->anggota_penanganans;
                                        @endphp

                                        <div class="avatar-group">
                                            @forelse ($anggotaLain as $anggota)
                                                <a href="javascript:void(0);" class="avatar-group-item"
                                                    data-bs-toggle="tooltip" title="{{ $anggota->user->name }}">
                                                    <img src="{{ \App\Http\Controllers\Helpers\ProfilePhoto::get($anggota->user->avatar, $anggota->user->name) }}"
                                                        class="rounded-circle avatar-xs" />
                                                </a>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Status</td>
                                    <td>{{ str_replace('_', ' ', $kasus->status) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Tingkat Keparahan</td>
                                    <td>
                                        <span class="badge bg-warning">{{ $kasus->tingkat_keparahan }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Dibuat Pada</td>
                                    <td>
                                        {{ $kasus->waktu_kejadian ? \Carbon\Carbon::parse($kasus->waktu_kejadian)->format('d M, Y H:i:s') : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Direspon Pada</td>
                                    <td>
                                        @if ($kasus->waktu_respon)
                                            {{ \Carbon\Carbon::parse($kasus->waktu_respon)->format('d M, Y H:i:s') }}
                                        @else
                                            <span class="text-muted">Belum di respon</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Diselesaikan Pada</td>
                                    <td>
                                        @if ($kasus->selesai_pada)
                                            {{ \Carbon\Carbon::parse($kasus->selesai_pada)->format('d M, Y H:i:s') }}
                                        @else
                                            <span class="text-muted">Belum di selesaikan</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
                <!--end card-body-->
            </div>
        </div>
        <!--end col-->
        <div class="col-xxl-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title fw-semibold mb-0">Lampiran</h6>
                </div>
                <div class="card-body">
                    @forelse($kasus->bukti_kasus as $bukti)
                        <div class="d-flex align-items-center border border-dashed p-2 rounded">

                            @php
                                $buktiC = new stdClass();
                                $buktiC->path = $bukti->path;
                                $lastPart = basename($buktiC->path);
                                $extension = pathinfo($buktiC->path, PATHINFO_EXTENSION);
                            @endphp

                            <div class="flex-shrink-0 avatar-sm">
                                <div class="avatar-title bg-light rounded text-primary">
                                    @if (Str::startsWith($bukti->mime, 'image/'))
                                        <i class="ri-image-line fs-20"></i>
                                    @elseif (Str::startsWith($bukti->mime, 'video/'))
                                        <i class="ri-video-line fs-20"></i>
                                    @elseif (Str::startsWith($bukti->mime, 'audio/'))
                                        <i class="ri-music-line fs-20"></i>
                                    @elseif (Str::contains($bukti->mime, 'pdf'))
                                        <i class="ri-file-pdf-line fs-20"></i>
                                    @elseif (Str::contains($bukti->mime, 'zip') || Str::contains($bukti->mime, 'compressed'))
                                        <i class="ri-file-zip-line fs-20"></i>
                                    @else
                                        <i class="ri-file-line fs-20"></i>
                                    @endif
                                </div>
                            </div>

                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">
                                    <a href="javascript:void(0);" class="text-body text-truncate d-block"
                                        style="max-width: 130px;">
                                        {{ $lastPart }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    {{ round($bukti->size / 1024, 2) }} MB
                                </small>
                            </div>

                            @php
                                $buktiModalId = 'bukti-modal-' . $bukti->id;
                            @endphp

                            <div class="hstack gap-3 fs-16">
                                <a href="{{ asset('storage/' . $bukti->path) }}" target="_blank" class="text-muted"><i
                                        class="ri-download-2-line"></i></a>
                                <span data-bs-toggle="modal" data-bs-target="{{ '#' . $buktiModalId }}">
                                    <i class="ri-eye-line"></i></span>
                                <div id="{{ $buktiModalId }}" class="modal fade" tabindex="-1"
                                    aria-labelledby="buktiModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="buktiModalLabel">
                                                    {{ $lastPart }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                {{-- Detect MIME type and show appropriate content --}}
                                                @if (Str::startsWith($bukti->mime, 'image/'))
                                                    <img src="{{ asset('storage/' . $bukti->path) }}"
                                                        alt="{{ $lastPart }}" class="img-fluid rounded">
                                                @elseif (Str::startsWith($bukti->mime, 'video/'))
                                                    <video controls class="w-100 rounded">
                                                        <source src="{{ asset('storage/' . $bukti->path) }}"
                                                            type="{{ $bukti->mime }}">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @elseif (Str::startsWith($bukti->mime, 'audio/'))
                                                    <audio controls class="w-100">
                                                        <source src="{{ asset('storage/' . $bukti->path) }}"
                                                            type="{{ $bukti->mime }}">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                @else
                                                    <p class="text-muted">File ini tidak dapat ditampilkan langsung.
                                                        Silakan unduh untuk melihat kontennya.</p>
                                                    <a href="{{ asset('storage/' . $bukti->path) }}" target="_blank"
                                                        class="btn btn-primary">
                                                        <i class="ri-download-2-line"></i> Unduh
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @empty
                        -
                    @endforelse
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title fw-semibold mb-0">Tim Penanganan</h6>
                </div>
                <div class="card-body">
                    @forelse($kasus->anggota_penanganans as $anggota)
                        <div class="d-flex align-items-center border border-dashed p-2 rounded mb-2">
                            @php
                                $profilePhoto = \App\Http\Controllers\Helpers\ProfilePhoto::get(
                                    $anggota->user->avatar,
                                    $anggota->user->name,
                                );
                            @endphp

                            <!-- Avatar -->
                            <div class="flex-shrink-0 avatar-sm">
                                <div class="avatar-title bg-light rounded text-primary">
                                    <img src="{{ $profilePhoto }}" class="rounded-md avatar-sm">
                                </div>
                            </div>

                            <!-- Informasi Anggota -->
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">
                                    <a href="javascript:void(0);" class="text-body text-truncate d-block"
                                        style="max-width: 130px;">
                                        {{ $anggota->user->name }}
                                    </a>
                                </h6>

                                <!-- Indikator Aktif/Tidak -->
                                <small class="badge {{ $anggota->aktif ? 'bg-success' : 'bg-danger' }}">
                                    {{ $anggota->aktif ? 'Aktif' : 'Dibatalkan' }}
                                </small>

                                <!-- Indikator Selesai/Belum -->
                                <small class="badge {{ $anggota->selesai ? 'bg-primary' : 'bg-warning' }}">
                                    {{ $anggota->selesai ? 'Selesai' : 'Dalam Proses' }}
                                </small>

                                @if ($anggota->aktif == 1)
                                    <!-- Indikator Selesai/Belum -->
                                    <small class="badge {{ $anggota->selesai ? 'bg-info' : 'bg-danger' }}">
                                        {{ $anggota->selesai ? 'Pekerjaan Telah Di Terima' : 'Menunggu Verifikasi Admin' }}
                                    </small>
                                @endif
                            </div>

                            @if (auth()->user()->peran == 'ADMIN' || auth()->user()->peran == 'POLISI')
                                <!-- Tombol Detail -->
                                <div class="ms-auto">
                                    <div class="d-flex flex-column gap-2">

                                        <button data-bs-toggle="modal"
                                            data-bs-target="{{ '#tugaskan-modal-' . $anggota->id }}"
                                            class="btn btn-sm btn-outline-primary">
                                            Detail
                                        </button>

                                        <div>

                                            @if (auth()->user()->peran == 'ADMIN')
                                                <!-- Tombol Hapus Anggota -->
                                                <form
                                                    action="{{ route('manajemenKasus.hapusAnggota', ['kasus_id' => $kasus->id, 'user_id' => $anggota->user_id]) }}"
                                                    method="POST" id="hapusAnggotaForm">
                                                    @csrf

                                                    <button type="button" class="btn btn-danger"
                                                        onclick="confirmHapusAnggota()">Hapus
                                                    </button>
                                                </form>
                                            @endif

                                        </div>

                                    </div>

                                    <!-- Konfirmasi Penghapusan -->
                                    <script>
                                        function confirmHapusAnggota() {
                                            if (confirm("Apakah Anda yakin ingin menghapus anggota ini?")) {
                                                document.getElementById("hapusAnggotaForm").submit();
                                            }
                                        }
                                    </script>

                                    <div class="hstack gap-3 fs-16">
                                        <div id="{{ 'tugaskan-modal-' . $anggota->id }}" class="modal modal-xl fade"
                                            tabindex="-1" aria-labelledby="{{ '#tugaskan-modal-label' . $anggota->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-md">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="{{ '#tugaskan-modal-label' . $anggota->id }}">
                                                            Detail Pekerjaan
                                                        </h5>

                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="card">
                                                            <div
                                                                class="card-header d-flex justify-content-between align-items-center">
                                                                <h6 class="card-title fw-semibold mb-0">Bukti Pekerjaan
                                                                </h6>
                                                                <!-- Tombol Reset Semua Bukti Pekerjaan -->
                                                                @if ($anggota->bukti_penyelesaian_kasus->isNotEmpty())
                                                                    <form
                                                                        action="{{ route('manajemenKasus.resetBuktiPekerjaan', ['kasus_id' => $kasus->id, 'user_id' => $anggota->user_id]) }}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua bukti pekerjaan?');">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-danger">
                                                                            <i class="ri-delete-bin-line"></i> Reset Bukti
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                            <div class="card-body">
                                                                @forelse($anggota->bukti_penyelesaian_kasus as $bukti)
                                                                    <div
                                                                        class="d-flex align-items-center border border-dashed p-2 rounded">

                                                                        @php
                                                                            $buktiC = new stdClass();
                                                                            $buktiC->path = $bukti->path;
                                                                            $lastPart = basename($buktiC->path);
                                                                            $extension = pathinfo(
                                                                                $buktiC->path,
                                                                                PATHINFO_EXTENSION,
                                                                            );
                                                                        @endphp

                                                                        <div class="flex-shrink-0 avatar-sm">
                                                                            <div
                                                                                class="avatar-title bg-light rounded text-primary">
                                                                                @if (Str::startsWith($bukti->mime, 'image/'))
                                                                                    <i class="ri-image-line fs-20"></i>
                                                                                @elseif (Str::startsWith($bukti->mime, 'video/'))
                                                                                    <i class="ri-video-line fs-20"></i>
                                                                                @elseif (Str::startsWith($bukti->mime, 'audio/'))
                                                                                    <i class="ri-music-line fs-20"></i>
                                                                                @elseif (Str::contains($bukti->mime, 'pdf'))
                                                                                    <i class="ri-file-pdf-line fs-20"></i>
                                                                                @elseif (Str::contains($bukti->mime, 'zip') || Str::contains($bukti->mime, 'compressed'))
                                                                                    <i class="ri-file-zip-line fs-20"></i>
                                                                                @else
                                                                                    <i class="ri-file-line fs-20"></i>
                                                                                @endif
                                                                            </div>
                                                                        </div>

                                                                        <div class="flex-grow-1 ms-3">
                                                                            <h6 class="mb-1">
                                                                                <a href="javascript:void(0);"
                                                                                    class="text-body text-truncate d-block"
                                                                                    style="max-width: 130px;">
                                                                                    {{ $lastPart }}
                                                                                </a>
                                                                            </h6>
                                                                            <small class="text-muted">
                                                                                {{ round($bukti->size / 1024, 2) }} MB
                                                                            </small>
                                                                        </div>

                                                                        @php
                                                                            $buktiModalId = 'bukti-modal-' . $bukti->id;
                                                                        @endphp

                                                                        <a href="{{ asset('storage/' . $bukti->path) }}"
                                                                            target="_blank" class="btn btn-primary">
                                                                            <i class="ri-download-2-line"></i> Unduh
                                                                        </a>

                                                                    </div>
                                                                @empty
                                                                    -
                                                                @endforelse
                                                            </div>
                                                        </div>


                                                        @if ($anggota->aktif == 0)
                                                            <div class="card">
                                                                <div
                                                                    class="card-header d-flex justify-content-between align-items-center">
                                                                    <h6 class="card-title fw-semibold mb-0">Alasan
                                                                        Dibatalkan
                                                                    </h6>
                                                                </div>
                                                                <div class="card-body">
                                                                    <p class="text-muted">
                                                                        {{ $anggota->alasan_dibatalkan ?? '-' }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <div class="card">
                                                            <div
                                                                class="card-header d-flex justify-content-between align-items-center">
                                                                <h6 class="card-title fw-semibold mb-0">Keterangan dari
                                                                    Admin
                                                                </h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <p class="text-muted">
                                                                    {{ $anggota->keterangan_admin ?? '-' }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        @if (auth()->user()->peran === 'ADMIN')
                                                            <div class="card mt-4">
                                                                <div class="card-header">
                                                                    <h6 class="card-title fw-semibold mb-0">Verifikasi
                                                                        Bukti
                                                                        Pekerjaan</h6>
                                                                </div>

                                                                <div class="card-body">
                                                                    <form
                                                                        action="{{ route('manajemenKasus.verifikasiBuktiPekerjaan', ['kasus_id' => $kasus->id, 'user_id' => $anggota->user_id]) }}"
                                                                        method="POST">
                                                                        @csrf

                                                                        <!-- Input Radio: Diterima / Tidak Diterima -->
                                                                        <div class="mb-3">
                                                                            <label class="form-label fw-semibold">Status
                                                                                Verifikasi</label>
                                                                            <div class="d-flex gap-3">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input"
                                                                                        type="radio"
                                                                                        name="status_verifikasi"
                                                                                        {{ $anggota->selesai == 1 ? 'checked' : '' }}
                                                                                        value="diterima"
                                                                                        id="verifikasi-diterima" required>
                                                                                    <label class="form-check-label"
                                                                                        for="verifikasi-diterima">Diterima</label>
                                                                                </div>
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input"
                                                                                        type="radio"
                                                                                        name="status_verifikasi"
                                                                                        value="tidak_diterima"
                                                                                        {{ $anggota->selesai == 0 ? 'checked' : '' }}
                                                                                        id="verifikasi-tidak-diterima"
                                                                                        required>
                                                                                    <label class="form-check-label"
                                                                                        for="verifikasi-tidak-diterima">Tidak
                                                                                        Diterima</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Input Keterangan -->
                                                                        <div class="mb-3">
                                                                            <label for="keterangan"
                                                                                class="form-label fw-semibold">Keterangan</label>
                                                                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Keterangan"></textarea>
                                                                        </div>

                                                                        <!-- Tombol Submit -->
                                                                        <div class="mt-3 text-end">
                                                                            <button type="submit"
                                                                                class="btn btn-success">
                                                                                <i class="ri-check-line"></i> Simpan
                                                                                Verifikasi
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @endif

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <span class="text-muted">Belum ada anggota penanganan</span>
                    @endforelse
                </div>

            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title fw-semibold mb-0">Kritik dan Saran</h6>
                </div>
                <div class="card-body">
                    @if ($kasus->feedback)
                        <blockquote class="blockquote">
                            <p class="mb-0 fst-italic text-muted">“{{ $kasus->feedback }}”</p>
                        </blockquote>
                    @else
                        <p class="text-muted mb-0">Belum ada kritik dan saran.</p>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title fw-semibold mb-0">Informasi Jarak</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0 text-muted">
                        Jarak Anda ke Lokasi Kejadian Kurang Lebih
                        <br>
                        <span class="fw-bold">{{ $jarakRelatif ?? 0 }} Meter</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        document.getElementById('files').addEventListener('change', function(event) {
            let fileList = document.getElementById('file-list');
            fileList.innerHTML = ''; // Clear existing list

            for (let file of event.target.files) {
                let listItem = document.createElement('li');
                listItem.className = 'list-group-item';
                listItem.textContent = file.name;
                fileList.appendChild(listItem);
            }
        });
    </script>
@endsection
