@extends('layouts.master')
@section('title')
    Manajemen Pangkat
@endsection

@section('css')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb', [
        'title' => 'Edit Pangkat',
        'crumbs' => [
            'Dashboard' => route('root'),
            'Manajemen Pangkat' => route('manajemenPangkat'),
        ],
    ])
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('manajemenPangkat.update', $pangkat->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="grup" class="form-label">Nama Grup</label>
                            <span class="text-danger"> *</span>
                            <input autofocus autocomplete="off" list="grup-list" class="form-control" id="grup"
                                name="grup" value="{{ old('grup', $pangkat->grup) }}" placeholder="Perwira Tinggi (Pati)"
                                required>
                            <datalist id="grup-list">
                                @foreach ($listGrupPangkatPolisi as $grup)
                                    <option value="{{ $grup->grup }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Pangkat</label>
                            <span class="text-danger"> *</span>
                            <input autocomplete="off" list="nama-list" class="form-control" id="nama" name="nama"
                                value="{{ old('nama', $pangkat->nama) }}"
                                placeholder="Inspektur Jenderal Polisi (Irjen Pol)">
                            <datalist id="nama-list">
                                @foreach ($listNamaPangkatPolisi as $nama)
                                    <option value="{{ $nama->nama }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3" placeholder="Masukkan deskripsi">{{ old('deskripsi', $pangkat->deskripsi) }}</textarea>
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
