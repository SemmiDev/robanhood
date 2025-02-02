@extends('layouts.master')
@section('title')
    Manajemen Pengaturan Website
@endsection

@section('css')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb', [
        'title' => 'Pengaturan Website',
        'crumbs' => [
            'Dashboard' => route('root'),
        ],
    ])
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('manajemenPengaturanWebsite.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Website</label>
                            <span class="text-danger"> *</span>
                            <input autocomplete="off" list="nama-list" class="form-control" id="nama" name="nama"
                                placeholder="Kecelakaan" value="{{ $pengaturanWebsite->nama }}">
                        </div>
                        <div class="mb-3">
                            <label for="tagline" class="form-label">Tagline Website</label>
                            <span class="text-danger"> *</span>
                            <input autocomplete="off" class="form-control" id="tagline" name="tagline"
                                placeholder="Tanggap Laporan, Tuntas Penanganan" value="{{ $pengaturanWebsite->tagline }}">
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo Website</label>
                            <span class="text-danger"> *</span>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">

                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $pengaturanWebsite->logo) }}" alt="Logo Saat Ini"
                                    class="img-fluid mt-3" style="width: 10%;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Website</label>
                            <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3" placeholder="Masukkan deskripsi">{{ $pengaturanWebsite->deskripsi }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Radius Notifikasi Polisi (Dalam KM)</label>
                            <span class="text-danger"> *</span>
                            <input autocomplete="off" list="nama-list" class="form-control" id="radius_notifikasi" name="radius_notifikasi"
                                placeholder="1" value="{{ $pengaturanWebsite->radius_notifikasi }}">
                        </div>

                        <div class="mb-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="izinkan_warga_daftar"
                                    name="izinkan_warga_daftar" value="1"
                                    {{ $pengaturanWebsite->izinkan_warga_daftar ? 'checked' : '' }}>
                                <label class="form-check-label" for="izinkan_warga_daftar">
                                    Izinkan Pendaftaran Warga
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="izinkan_polisi_daftar"
                                    name="izinkan_polisi_daftar" value="1"
                                    {{ $pengaturanWebsite->izinkan_polisi_daftar ? 'checked' : '' }}>
                                <label class="form-check-label" for="izinkan_polisi_daftar">
                                    Izinkan Pendaftaran Polisi
                                </label>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
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
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
@endsection
