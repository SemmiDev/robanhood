@extends('layouts.master')
@section('title')
    Manajemen Kategori Kasus
@endsection

@section('css')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb', [
        'title' => 'Tambah Kategori Kasus',
        'crumbs' => [
            'Dashboard' => route('root'),
            'Manajemen Kategori Kasus' => route('manajemenKategoriKasus'),
        ],
    ])
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('manajemenKategoriKasus.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Kategori Kasus</label>
                            <span class="text-danger"> *</span>
                            <input autocomplete="off" list="nama-list" class="form-control" id="nama" name="nama"
                                placeholder="Kecelakaan">
                        </div>
                        <div class="mb-3">
                            <label for="simbol" class="form-label">Simbol</label>
                            <span class="text-danger"> *</span>
                            <input type="file" class="form-control" id="simbol" name="simbol" accept="image/*">
                            <p class="mt-2">Temukan simbol yang sesuai di <a href="https://www.flaticon.com/search?word=warning" target="_blank">sini</a>.</p>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3" placeholder="Masukkan deskripsi"></textarea>
                        </div> <div class="mb-3">
                            <label for="pengingat" class="form-label">Pesan Pengingat</label>
                            <textarea class="form-control" name="pengingat" id="pengingat" rows="3" placeholder="Masukkan pesan pengingat"></textarea>
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
