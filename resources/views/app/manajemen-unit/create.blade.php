@extends('layouts.master')
@section('title')
    Manajemen Unit / Satuan
@endsection

@section('css')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb', [
        'title' => 'Tambah Unit / Satuan',
        'crumbs' => [
            'Dashboard' => route('root'),
            'Manajemen Unit / Satuan' => route('manajemenUnit'),
        ],
    ])
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('manajemenUnit.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Unit / Satuan</label>
                            <span class="text-danger"> *</span>
                            <input autocomplete="off" list="nama-list" class="form-control" id="nama" name="nama"
                                placeholder="Inspektur Jenderal Polisi (Irjen Pol)">
                            <datalist id="nama-list">
                                @foreach ($listNamaUnitPolisi as $nama)
                                    <option value="{{ $nama->nama }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3" placeholder="Masukkan deskripsi"></textarea>
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
