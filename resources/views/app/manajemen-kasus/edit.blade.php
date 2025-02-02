@extends('layouts.master')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/leaflet/leaflet.css') }}" rel="stylesheet" type="text/css" />
    <style>
        #map {
            height: 300px;
            width: 100%;
        }

        @media (min-width: 992px) {
            #map {
                height: 400px;
            }

            .form-container {
                max-width: 800px !important;
                margin: 0 auto;
            }

            .select2-container {
                width: 100% !important;
            }
        }
    </style>
@endsection

@section('title')
    Edit Laporan
@endsection

@section('content')
    <div class="auth-page-wrapper pt-lg-5 pt-3">
        <div class="auth-page-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="col-lg-12">
                                    <div class="text-center mb-3 text-white-50">
                                        <div>
                                            <a href="index" class="d-inline-block auth-logo">
                                                <img src="{{ asset('storage/' . $global_pengaturan_website->logo) }}"
                                                    alt="" height="100">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Edit Laporan</h5>
                                    <p class="text-muted">Silahkan lengkapi form dibawah</p>
                                </div>
                                <div class="p-2 mt-4 form-container">
                                    <form class="needs-validation" method="POST"
                                        action="{{ route('manajemenKasus.update', ['id' => $kasus->id]) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="row">
                                            <div class="col-12 col-lg-6 mb-3">
                                                <label for="judul" class="form-label">Judul Laporan<span
                                                        class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('judul') is-invalid @enderror" name="judul"
                                                    autofocus value="{{ old('judul', $kasus->judul) }}" id="judul"
                                                    placeholder="Kecelakaan Beruntun" required>
                                                @error('judul')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-lg-6 mb-3">
                                                <label for="alamat" class="form-label">Lokasi Kejadian</label>
                                                <textarea class="form-control" id="alamat" name="alamat" placeholder="Jalan sudirman" rows="1">{{ $kasus->alamat }}</textarea>
                                            </div>

                                        </div>

                                        <div class="mb-3">
                                            <label for="deskripsi" class="form-label">Deskripsi kejadian</label>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Kecelakaan beruntun di jalan sudirman ..."
                                                rows="3">{{ $kasus->deskripsi }}</textarea>
                                        </div>

                                        <div class="row">

                                            <div class="col-12 col-lg-6 mb-3">
                                                <label for="kategori_kasus_id" class="form-label">Jenis Kejadian<span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control kategori-select2" name="kategori_kasus_id"
                                                    required>
                                                    @foreach ($kategoriKasus as $kategori)
                                                        <option value="{{ $kategori->id }}"
                                                            {{ $kasus->kategori_kasus_id == $kategori->id ? 'selected' : '' }}>
                                                            {{ $kategori->nama }}</option>
                                                    @endforeach
                                                </select>
                                                @error('kategori_kasus_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-lg-6 mb-3">
                                                <label for="tingkat_keparahan" class="form-label">Tingkat Keparahan<span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control tingkat-keparahan-select2"
                                                    name="tingkat_keparahan">
                                                    <option value="RINGAN"
                                                        {{ $kasus->tingkat_keparahan == 'RINGAN' ? 'selected' : '' }}>
                                                        RINGAN
                                                    </option>
                                                    <option
                                                        value="SEDANG"{{ $kasus->tingkat_keparahan == 'SEDANG' ? 'selected' : '' }}>
                                                        SEDANG</option>
                                                    <option
                                                        value="BERAT"{{ $kasus->tingkat_keparahan == 'BERAT' ? 'selected' : '' }}>
                                                        BERAT</option>
                                                    <option
                                                        value="LAINNYA"{{ $kasus->tingkat_keparahan == 'LAINNYA' ? 'selected' : '' }}>
                                                        LAINNYA</option>
                                                </select>
                                            </div>
                                        </div>

                                        <input type="text" name="latitude" id="latitude" hidden>
                                        <input type="text" name="longitude" id="longitude" hidden>

                                        <div class="mb-3">
                                            <div id="map"></div>
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" type="submit">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> {{ $global_pengaturan_website->nama }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection


@section('script')
    <script src="{{ URL::asset('build/libs/leaflet/leaflet.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        let map;
        let marker;

        $(document).ready(function() {
            // Inisialisasi Select2
            $('.kategori-select2').select2();
            $('.tingkat-keparahan-select2').select2();

            // Inisialisasi peta
            initMap();
        });

        function initMap() {
            if (map) return; // Mencegah inisialisasi ganda

            // Gunakan nilai dari $kasus jika tersedia
            let kasusLat = {{ $kasus->latitude ?? -6.2 }};
            let kasusLng = {{ $kasus->longitude ?? 106.816666 }};

            // Set nilai input sesuai data yang tersimpan
            $('#latitude').val(kasusLat);
            $('#longitude').val(kasusLng);

            // Inisialisasi peta dengan attributionControl dinonaktifkan
            map = L.map('map', {
                attributionControl: false
            }).setView([kasusLat, kasusLng], 18);

            // Layer peta dasar (OpenStreetMap) tanpa attribution
            const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: ''
            });

            // Layer satelit (Google Satellite) tanpa attribution
            const satelliteLayer = L.tileLayer('https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                attribution: ''
            });

            // Tambahkan layer satelit sebagai default
            satelliteLayer.addTo(map);

            // Buat control untuk layer
            const baseMaps = {
                "Satelit": satelliteLayer,
                "Peta Biasa": osmLayer
            };

            // Tambahkan control layer ke peta
            L.control.layers(baseMaps).addTo(map);

            // Buat icon marker merah
            const redIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            // Tambahkan marker yang tidak bisa dipindahkan dengan icon merah
            marker = L.marker([kasusLat, kasusLng], {
                draggable: false,
                icon: redIcon
            }).addTo(map);
        }
    </script>
@endsection
