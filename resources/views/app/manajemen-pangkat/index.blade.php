@extends('layouts.master')
@section('title')
    Manajemen Pangkat
@endsection

@section('css')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb', [
        'title' => 'Manajemen Pangkat',
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
                                    <a href="{{ route('manajemenPangkat.create') }}" class="btn btn-success add-btn"><i
                                            class="ri-add-line align-bottom me-1"></i> Tambah</a>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <form method="GET" action="{{ route('manajemenPangkat') }}" class="search-box ms-2"
                                        onsubmit="return searchFormSubmit()">
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
                                        <th>Kategori Pangkat</th>
                                        <th>Nama Pangkat</th>
                                        <th>Deskripsi Pangkat</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                    @forelse ($listPangkatPolisi as $pangkatPolisi)
                                        <tr data-id="{{ $pangkatPolisi->id }}">
                                            <th scope="row">
                                                {{ ($listPangkatPolisi->currentPage() - 1) * $listPangkatPolisi->perPage() + $loop->iteration }}
                                            </th>
                                            <td class="grup">{{ $pangkatPolisi->grup }}</td>
                                            <td class="nama">{{ $pangkatPolisi->nama }}</td>
                                            <td class="deskripsi">{{ $pangkatPolisi->deskripsi }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <div class="edit">
                                                        <a href="{{route('manajemenPangkat.show', ['id'=> $pangkatPolisi->id])}}" class="btn btn-sm btn-success edit-item-btn">
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
                        {{ $listPangkatPolisi->appends(request()->query())->links() }}
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
    <script>
        document.querySelectorAll('.remove-item-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.closest('tr').dataset.id; // Add data-id="{{ $pangkatPolisi->id }}" to TR

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
                        fetch(`/manajemen-pangkat/${id}`, {
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
