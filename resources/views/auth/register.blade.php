@extends('layouts.master-without-nav')
@section('title')
    Daftar
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection


@section('content')
    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="/" class="d-inline-block auth-logo">
                                    <img src="{{ asset('storage/' . $global_pengaturan_website->logo) }}" alt=""
                                        height="100">
                                </a>
                            </div>
                            <h4 class="text-white mt-4">{{ $global_pengaturan_website->nama }}</h4>
                            <p class="mt-1 fs-15 fw-medium">
                                {{ $global_pengaturan_website->deskripsi }}
                            </p>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <div class="card mt-4">

                            <div class="card-body">

                                @if (count($allowRegisteredRole) > 0)
                                    <div class="text-center mt-2">
                                        <h5 class="text-primary">Buat akun baru</h5>
                                        <p class="text-muted">Silahkan lengkapi form dibawah</p>
                                    </div>

                                    <div class="p-2 mt-4">
                                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <!-- Nama Lengkap -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="name" class="form-label">Nama Lengkap <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" autofocus
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" value="{{ old('name') }}" id="name"
                                                        placeholder="Masukkan nama lengkap" required>
                                                    @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <!-- NIK -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="nik" class="form-label">NIK <span
                                                            class="text-danger">*</span></label>
                                                    <input type="number"
                                                        class="form-control @error('nik') is-invalid @enderror"
                                                        name="nik" value="{{ old('nik') }}" id="nik"
                                                        placeholder="Masukkan NIK" required>
                                                    @error('nik')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- No HP -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="no_hp" class="form-label">Nomor HP <span
                                                            class="text-danger">*</span></label>
                                                    <input type="number"
                                                        class="form-control @error('no_hp') is-invalid @enderror"
                                                        name="no_hp" value="{{ old('no_hp') }}" id="no_hp"
                                                        placeholder="Masukkan Nomor HP" required>
                                                    @error('no_hp')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="peran">Daftar Sebagai<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control select2-daftar-sebagai" data-trigger
                                                        name="peran" id="peran" required>
                                                        @foreach ($allowRegisteredRole as $role => $label)
                                                            <option value="{{ $role }}">{{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Email -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="email" class="form-label">Email <span
                                                            class="text-danger">*</span></label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ old('email') }}" id="email"
                                                        placeholder="Masukkan alamat email" required>
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="userpassword" class="form-label">Kata Sandi <span
                                                            class="text-danger">*</span></label>
                                                    <input type="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        name="password" id="userpassword"
                                                        placeholder="Masukkan kata sandi" required>
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <button class="btn btn-success w-100" type="submit">Daftar</button>
                                            </div>
                                        </form>

                                    </div>
                                @else
                                    <div class="alert alert-danger">
                                        Mohon maaf, Admin telah menutup proses pendaftaran. Silahkan coba lagi
                                        nanti ya 🙂
                                    </div>
                                @endif
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">Sudah punya akun? <a href="{{ route('login') }}"
                                    class="fw-semibold text-primary text-decoration-none"> Masuk </a> </p>
                        </div>

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> {{$global_pengaturan_website->nama}}
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->
@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/particles.js/particles.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/particles.app.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/form-validation.init.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select2-daftar-sebagai').select2();
        })
    </script>
@endsection
