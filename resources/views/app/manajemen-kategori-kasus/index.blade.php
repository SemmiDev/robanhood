@extends('layouts.master')
@section('title')
    Manajemen Kategori Kasus
@endsection

@section('css')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb', [
        'title' => 'Manajemen Kategori Kasus',
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
                            <div class="col-sm-auto">
                                <div>
                                    <a href="{{ route('manajemenKategoriKasus.create') }}"
                                        class="btn btn-success add-btn"><i class="ri-add-line align-bottom me-1"></i>
                                        Tambah</a>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <form method="GET" action="{{ route('manajemenKategoriKasus') }}"
                                        class="search-box ms-2" onsubmit="return searchFormSubmit()">
                                        <input type="text" name="q" value="{{ request()->get('q') }}"
                                            class="form-control search" placeholder="Pencarian..."
                                            onkeypress="if(event.key === 'Enter') { searchFormSubmit(); }">
                                        <i class="ri-search-line search-icon"></i>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-card mt-3 mb-1">
                            <table class="table align-middle table-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 50px;">
                                            No
                                        </th>
                                        <th>Nama Kategori</th>
                                        <th>Deskripsi Kategori</th>
                                        <th>Pesan Pengingat</th>
                                        <th>Simbol</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                    @forelse ($listKategoriKasus as $kategoriKasus)
                                        <tr data-id="{{ $kategoriKasus->id }}">
                                            <th scope="row">
                                                {{ ($listKategoriKasus->currentPage() - 1) * $listKategoriKasus->perPage() + $loop->iteration }}
                                            </th>
                                            <td class="nama">{{ $kategoriKasus->nama }}</td>
                                            <td class="deskripsi">{{ $kategoriKasus->deskripsi }}</td>
                                            <td class="pengingat">{{ $kategoriKasus->pengingat }}</td>
                                            <td class="logo">
                                                @if ($kategoriKasus->simbol)
                                                    <img src="{{ asset('storage/' . $kategoriKasus->simbol) }}"
                                                        alt="Logo" class="img-fluid" style="max-width: 50px;">
                                                @else
                                                    Belum ada simbol
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <div class="edit">
                                                        <a href="{{ route('manajemenKategoriKasus.show', ['id' => $kategoriKasus->id]) }}"
                                                            class="btn btn-sm btn-success edit-item-btn">
                                                            <i class="ri-pencil-line align-bottom me-1"></i>
                                                            Ubah</a>
                                                    </div>
                                                    <div class="sa-warning">
                                                        <button class="btn btn-sm btn-danger remove-item-btn"
                                                            data-bs-toggle="modal" data-bs-target="#deleteRecordModal">
                                                            <i class="ri-delete-bin-line align-bottom me-1"></i>
                                                            Hapus</button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <div class="noresult">
                                            <div class="text-center">
                                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                                    colors="primary:#121331,secondary:#08a88a"
                                                    style="width:75px;height:75px">
                                                </lord-icon>
                                                <h5 class="mt-2">Belum ada data</h5>
                                            </div>
                                        </div>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{ $listKategoriKasus->appends(request()->query())->links() }}
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
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/rater-js/index.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/rating.init.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @foreach ($listKategoriKasus as $kategoriKasus)
                raterJs({
                    element: document.querySelector('#rating-{{ $kategoriKasus->id }}'),
                    max: {{ $kategoriKasus->poin_dasar }}, // Menentukan jumlah maksimal bintang berdasarkan poin
                    readOnly: true,
                    rating: {{ $kategoriKasus->poin_dasar }}, // Langsung gunakan poin_dasar sebagai rating
                });
            @endforeach
        });
    </script>

    <script>
        document.querySelectorAll('.remove-item-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.closest('tr').dataset.id;

                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    customClass: {
                        confirmButton: 'btn btn-primary me-2',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false,
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal",
                    buttonsStyling: false,
                    showCloseButton: true
                }).then((result) => {
                    if (result.value) {
                        fetch(`/manajemen-kategori-kasus/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Terhapus!',
                                        text: 'Data berhasil dihapus.',
                                        icon: 'success'
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error! ',
                                    text: 'Terjadi kesalahan saat menghapus data.',
                                    icon: 'error'
                                });
                            });
                    }
                });
            });
        });
    </script>
@endsection
