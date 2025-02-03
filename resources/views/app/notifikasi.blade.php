@extends('layouts.master')
@section('title')
    Notifikasi
@endsection
@section('css')
    <style>
        .list-group-item {
            transition: background-color 0.3s;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="container mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0">Notifikasi</h3>

                    <!-- Tombol Tandai Semua Dibaca -->
                    <a href="{{ route('notifikasi.markAsRead') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-check-double"></i> Tandai Semua Dibaca
                    </a>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        @forelse ($groupedNotifikasi as $kategori => $notifikasis)
                            @if (count($notifikasis) > 0)
                                <div class="bg-light px-3 py-2 fw-bold text-secondary">
                                    {{ $kategori }}
                                </div>

                                <ul class="list-group list-group-flush">
                                    @foreach ($notifikasis as $notifikasi)
                                        <a href="{{ $notifikasi->link }}" class="text-decoration-none text-dark">
                                            <li
                                                class="list-group-item d-flex align-items-center py-3
                                                {{ !$notifikasi->read ? 'bg-warning-subtle' : '' }}">

                                                <!-- Icon Notifikasi -->
                                                <div class="me-3">
                                                    <i class="{{ $notifikasi->icon }} fs-4"></i>
                                                </div>

                                                <!-- Detail Notifikasi -->
                                                <div class="flex-grow-1">
                                                    <p class="mb-1 fw-semibold">
                                                        {{ $notifikasi->pesan }}
                                                    </p>
                                                    <small
                                                        class="text-muted">{{ $notifikasi->created_at->diffForHumans() }}</small>
                                                </div>

                                                <!-- Status Belum Dibaca -->
                                                @if (!$notifikasi->read)
                                                    <span class="badge bg-danger ms-2">Baru</span>
                                                @endif
                                            </li>
                                        </a>
                                    @endforeach
                                </ul>
                            @endif
                        @empty
                        <span class="text-muted">Belum ada notifikasi.</span>
                        @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                <div class="text-center mt-4">
                    {{ $listNotifikasi->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="{{ URL::asset('build/libs/rater-js/index.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/rating.init.js') }}"></script>
@endsection
