@extends('layouts.master-without-nav')
@section('title')
    Masuk
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
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Selamat Datang</h5>
                                    <p class="text-muted">Silahkan masuk untuk melanjutkan</p>
                                </div>
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <div class="p-2 mt-4">
                                    <form action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" autofocus
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email', '') }}" id="username" name="email"
                                                placeholder="Masukkan alamat email" required>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <div class="float-end">
                                                <a href="{{ route('password.update') }}" class="text-muted">Lupa kata
                                                    sandi?</a>
                                            </div>
                                            <label class="form-label" for="password-input">Kata Sandi <span
                                                    class="text-danger">*</span></label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password"
                                                    class="form-control pe-5 password-input @error('password') is-invalid @enderror"
                                                    name="password" placeholder="Masukkan kata sandi" id="password-input"
                                                    value="" required>
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                    type="button" id="password-addon"><i
                                                        class="ri-eye-fill align-middle"></i></button>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="auth-remember-check">
                                            <label class="form-check-label" for="auth-remember-check">Ingat saya</label>
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" type="submit">Masuk</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Download Section -->


                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">Belum punya akun? <a href="{{ route('register') }}"
                                    class="fw-semibold text-primary text-decoration-none"> Daftar </a> </p>
                        </div>

                        {{-- <div class="container mt-3 mx-auto text-center">
                            <a href="https://raw.githubusercontent.com/SemmiDev/robanhood/main/Robanhood.apk" class="underline text-info">
                                <i class="ri-download-2-line"></i>
                                Download Aplikasi Android
                            </a>
                        </div> --}}

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
                                </script>
                                {{ $global_pengaturan_website->nama }}
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/particles.js/particles.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/particles.app.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/password-addon.init.js') }}"></script>

    <script>
        Swal.fire({
            title: 'Pemberitahuan',
            text: "Silahkan login terlebih dahulu",
            icon: 'warning',
            confirmButtonText: 'OK'
        });
    </script>
@endsection
