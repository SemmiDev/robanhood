@extends('layouts.master')
@section('title')
    @lang('translation.settings')
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="{{ URL::asset('build/images/profile-bg.jpg') }}" class="profile-wid-img" alt="">
            <div class="overlay-content">
                <div class="text-end p-3">
                    <div class="p-0 ms-auto rounded-circle profile-photo-edit">
                        <input id="profile-foreground-img-file-input" type="file"
                            class="profile-foreground-img-file-input">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-3">
            <div class="card mt-n5">
                <div class="card-body p-4">
                    <form class="text-center" method="POST" action="{{ route('profil.updateFotoProfil', ['id' => $user->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                            @php
                                $urlFoto = \App\Http\Controllers\Helpers\ProfilePhoto::get($user->avatar, $user->name);
                            @endphp

                            <img src="{{ $urlFoto }}" class="rounded-circle avatar-xl img-thumbnail user-profile-image"
                                alt="user-profile-image">
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                <input id="profile-img-file-input" type="file" class="profile-img-file-input"
                                    name="avatar" onchange="updateProfilePhoto(this)">
                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <h5 class="fs-16 mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-0">{{ $user->peran }}</p>


                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>

                    </form>
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link text-body active" data-bs-toggle="tab" href="#personalDetails"
                                role="tab">
                                <i class="fas fa-home"></i>
                                Biodata Diri
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-body" data-bs-toggle="tab" href="#changePassword" role="tab">
                                <i class="far fa-user"></i>
                                Ubah Kata Sandi
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form action="{{ route('profil.update', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Informasi Akun -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Informasi Akun</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="emailInput" class="form-label">Email Address</label>
                                                    <input type="email" class="form-control" id="emailInput" disabled
                                                        placeholder="Masukkan email anda" value="{{ $user->email }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informasi Pribadi -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Informasi Pribadi</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Nama lengkap</label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        placeholder="Masukkan nama anda" value="{{ $user->name }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="nik" class="form-label">NIK</label>
                                                    <input type="text" class="form-control" id="nik"
                                                        name="nik" placeholder="Masukkan NIK anda"
                                                        value="{{ $user->nik }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="no_whatsapp" class="form-label">No Whatsapp</label>
                                                    <input type="text" name="no_whatsapp" class="form-control"
                                                        id="no_whatsapp" placeholder="Masukkan nomor whatsapp"
                                                        value="{{ $user->no_whatsapp }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="no_telepon" class="form-label">No Telepon</label>
                                                    <input type="text" class="form-control" id="no_telepon"
                                                        name="no_telepon" placeholder="Masukkan nomor telpon"
                                                        value="{{ $user->no_telepon }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dokumen Pendukung -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Dokumen Pendukung SOS</h5>
                                        <p class="text-muted small mb-0">Upload dokumen berikut untuk mengaktifkan fitur
                                            SOS</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="foto_ktp" class="form-label">Foto KTP</label>
                                                    <input type="file" class="form-control" id="foto_ktp"
                                                        name="foto_ktp" accept="image/*">
                                                    <div class="form-text">Format: JPG, PNG. Maksimal 2MB</div>
                                                    <!-- Preview Foto KTP -->
                                                    <img id="ktp_preview" src="{{ asset('storage/' . $user->foto_ktp) }}"
                                                        alt="Preview Foto KTP" class="rounded shadow p-2"
                                                        style="width: 200px; object-fit: cover; height: 150px; margin-top: 10px;">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="foto_kk" class="form-label">Foto KK</label>
                                                    <input type="file" class="form-control" id="foto_kk"
                                                        name="foto_kk" accept="image/*">
                                                    <div class="form-text">Format: JPG, PNG. Maksimal 2MB</div>
                                                    <!-- Preview Foto KK -->
                                                    <img id="kk_preview" src="{{ asset('storage/' . $user->foto_kk) }}"
                                                        class="rounded shadow p-2" alt="Preview Foto KK"
                                                        style="width: 200px; object-fit: cover; height: 150px; margin-top: 10px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                @if ($user->peran == 'POLISI' || $user->peran == 'ADMIN')
                                    <!-- Informasi Kepegawaian -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Informasi Kepegawaian</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="nrp" class="form-label">NRP</label>
                                                        <input type="text" class="form-control" id="nrp"
                                                            placeholder="Masukkan NRP" name="nrp"
                                                            value="{{ $user->profil_polisis->first()->nrp }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="jabatan" class="form-label">Jabatan</label>
                                                        <input type="text" class="form-control" id="jabatan"
                                                            name="jabatan" placeholder="Masukkan jabatan"
                                                            value="{{ $user->profil_polisis->first()->jabatan }}">
                                                    </div>
                                                </div>


                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="unit_id" class="form-label">Unit</label>
                                                        <select class="form-select unit-select2" name="unit_id"
                                                            id="unit_id">
                                                            @foreach ($unitPolisi as $unit)
                                                                @php
                                                                    $idUnit =
                                                                        $user->profil_polisis->first()->unit_id ?? '';
                                                                @endphp
                                                                <option value="{{ $unit->id }}"
                                                                    {{ $idUnit == $unit->id ? 'selected' : '' }}>
                                                                    {{ $unit->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">

                                                    <div class="mb-3">
                                                        <label for="pangkat_id" class="form-label">Pangkat</label>
                                                        <select class="form-select pangkat-select2" name="pangkat_id"
                                                            id="pangkat_id">
                                                            @foreach ($pangkatPolisi as $pangkat)
                                                                @php
                                                                    $idPangkat =
                                                                        $user->profil_polisis->first()->pangkat_id ??
                                                                        'tidak ada';
                                                                @endphp
                                                                <option value="{{ $pangkat->id }}"
                                                                    {{ $idPangkat == $pangkat->id ? 'selected' : '' }}>
                                                                    {{ $pangkat->nama }} ({{ $pangkat->grup ?? '-' }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="spesialisasi" class="form-label">Spesialisasi</label>
                                                        <input type="text" class="form-control" id="spesialisasi"
                                                            name="spesialisasi" placeholder="Masukkan spesialisasi"
                                                            value="{{ $user->profil_polisis->first()->spesialisasi }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Data Pribadi Tambahan -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Data Pribadi Tambahan</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                                        <input type="text" class="form-control" id="tempat_lahir"
                                                            name="tempat_lahir" placeholder="Masukkan tempat lahir"
                                                            value="{{ $user->profil_polisis->first()->tempat_lahir }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="tanggal_lahir" class="form-label">Tanggal
                                                            Lahir</label>
                                                        <input type="date" class="form-control" id="tanggal_lahir"
                                                            name="tanggal_lahir" placeholder="Masukkan tanggal lahir"
                                                            value="{{ $user->profil_polisis->first()->tanggal_lahir ? $user->profil_polisis->first()->tanggal_lahir->format('Y-m-d') : '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="jenis_kelamin" class="form-label">Jenis
                                                            Kelamin</label>
                                                        <select class="form-select" id="jenis_kelamin"
                                                            name="jenis_kelamin">
                                                            <option value="L"
                                                                {{ $user->profil_polisis->first()->jenis_kelamin == 'L' ? 'selected' : '' }}>
                                                                Laki-laki</option>
                                                            <option value="P"
                                                                {{ $user->profil_polisis->first()->jenis_kelamin == 'P' ? 'selected' : '' }}>
                                                                Perempuan</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="golongan_darah" class="form-label">Golongan
                                                            Darah</label>
                                                        <input type="text" class="form-control" id="golongan_darah"
                                                            name="golongan_darah" placeholder="Masukkan golongan darah"
                                                            value="{{ $user->profil_polisis->first()->golongan_darah }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="agama" class="form-label">Agama</label>
                                                        <select class="form-select" id="agama" name="agama">
                                                            <option value="Islam"
                                                                {{ $user->profil_polisis->first()->agama == 'Islam' ? 'selected' : '' }}>
                                                                Islam</option>
                                                            <option value="Kristen"
                                                                {{ $user->profil_polisis->first()->agama == 'Kristen' ? 'selected' : '' }}>
                                                                Kristen</option>
                                                            <option value="Katolik"
                                                                {{ $user->profil_polisis->first()->agama == 'Katolik' ? 'selected' : '' }}>
                                                                Katolik</option>
                                                            <option value="Hindu"
                                                                {{ $user->profil_polisis->first()->agama == 'Hindu' ? 'selected' : '' }}>
                                                                Hindu</option>
                                                            <option value="Buddha"
                                                                {{ $user->profil_polisis->first()->agama == 'Buddha' ? 'selected' : '' }}>
                                                                Buddha</option>
                                                            <option value="Konghucu"
                                                                {{ $user->profil_polisis->first()->agama == 'Konghucu' ? 'selected' : '' }}>
                                                                Konghucu</option>
                                                            <option value="Lainnya"
                                                                {{ $user->profil_polisis->first()->agama == 'Lainnya' ? 'selected' : '' }}>
                                                                Lainnya</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="status_pernikahan" class="form-label">Status
                                                            Pernikahan</label>
                                                        <select class="form-select" id="status_pernikahan"
                                                            name="status_pernikahan">
                                                            <option value="LAJANG"
                                                                {{ $user->profil_polisis->first()->status_pernikahan == 'LAJANG' ? 'selected' : '' }}>
                                                                Lajang</option>
                                                            <option value="MENIKAH"
                                                                {{ $user->profil_polisis->first()->status_pernikahan == 'MENIKAH' ? 'selected' : '' }}>
                                                                Menikah</option>
                                                            <option value="DUDA/JANDA"
                                                                {{ $user->profil_polisis->first()->status_pernikahan == 'DUDA/JANDA' ? 'selected' : '' }}>
                                                                Duda/Janda</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="text-end">
                                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="changePassword" role="tabpanel">
                            <form action="{{ route('profil.updatePassword') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row g-2">
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div class="position-relative">
                                            <label for="password" class="form-label">Kata Sandi Baru *</label>
                                            <input type="text" class="form-control pe-5" id="password" name="password" placeholder="Masukkan kata sandi baru">
                                            <i id="togglePassword" class="bi bi-eye-slash position-absolute" style="top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div class="position-relative">
                                            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi*</label>
                                            <input type="text" class="form-control pe-5" id="password_confirmation" placeholder="Masukkan kata sandi baru sekali lagi" name="password_confirmation">
                                            <i id="togglePasswordConfirmation" class="bi bi-eye-slash position-absolute" style="top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                        <!--end tab-pane-->
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection

@section('script')
    <script src="{{ URL::asset('build/js/pages/profile-setting.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        let map;
        let marker;

        $(document).ready(function() {
            // Inisialisasi Select2
            $('.unit-select2').select2();
            $('.pangkat-select2').select2();
        });
    </script>
@endsection
