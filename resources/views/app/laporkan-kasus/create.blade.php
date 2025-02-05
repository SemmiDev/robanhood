@extends('layouts.master')

@section('css')
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
    Buat Laporan
@endsection

@section('content')
    <div class="auth-page-wrapper">
        <div class="auth-page-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="col-lg-12">
                                    <div class="text-center mb-3 text-white-50">
                                        <div>
                                            <a href="{{ route('root') }}" class="d-inline-block auth-logo">
                                                <img src="{{ asset('storage/' . $global_pengaturan_website->logo) }}"
                                                    alt="" height="100">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Buat Laporan</h5>
                                    <p class="text-muted">Silahkan lengkapi form dibawah</p>
                                </div>
                                <div class="p-2 mt-4 form-container">
                                    <form class="needs-validation" method="POST"
                                        action="{{ route('laporkanKasus.store') }}" enctype="multipart/form-data">
                                        @csrf

                                        <div class="row">
                                            <div class="col-12 col-lg-6 mb-3">
                                                <label for="judul" class="form-label">Judul Laporan<span
                                                        class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('judul') is-invalid @enderror" name="judul"
                                                    autofocus value="{{ old('judul') }}" id="judul"
                                                    placeholder="Kecelakaan Beruntun" required>
                                                @error('judul')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                                <div class="col-12 col-lg-6 mb-3">
                                                    <label for="alamat" class="form-label">Lokasi Kejadian</label>
                                                    <textarea class="form-control" id="alamat" name="alamat" placeholder="Jalan sudirman" rows="1"></textarea>
                                                </div>

                                        </div>

                                        <div class="mb-3">
                                            <label for="deskripsi" class="form-label">Deskripsi kejadian</label>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Kecelakaan beruntun di jalan sudirman ..."
                                                rows="3"></textarea>
                                        </div>

                                        <div class="row">

                                            <input type="number" name="kategori_kasus_id" value="{{request()->get('kategori_kasus_id', 1)}}" hidden>


                                            {{-- <div class="col-12 col-lg-6 mb-3">
                                                <label for="kategori_kasus_id" class="form-label">Jenis Kejadian<span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control kategori-select2" name="kategori_kasus_id"
                                                    required>
                                                    @foreach ($kategoriKasus as $kategori)
                                                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                                    @endforeach
                                                </select>
                                                @error('kategori_kasus_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div> --}}

                                            <div class="col-12 col-lg-12 mb-3">
                                                <label for="tingkat_keparahan" class="form-label">Tingkat Keparahan<span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control form-select"
                                                    name="tingkat_keparahan">
                                                    <option value="RINGAN">RINGAN</option>
                                                    <option value="SEDANG">SEDANG</option>
                                                    <option value="BERAT">BERAT</option>
                                                    <option value="LAINNYA">LAINNYA</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="bukti" class="form-label">Lampiran<span
                                                    class="text-danger">*</span></label>
                                            <input type="file" class="form-control" name="bukti[]" id="inputGroupFile01"
                                                multiple>
                                        </div>

                                        <input type="text" name="latitude" id="latitude" hidden>
                                        <input type="text" name="longitude" id="longitude" hidden>

                                        <div class="mb-3">
                                            <div id="map"></div>
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" type="submit">Kirim Laporan</button>
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
    <script src="{{ URL::asset('build/libs/leaflet/leaflet.js') }}"></script>
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/app/dashboard.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script>
        let map;
        let marker;

        $(document).ready(function() {
            // Inisialisasi Select2
            $('.kategori-select2').select2();
            $('.tingkat-keparahan-select2').select2();

            // Inisialisasi peta
            initMap();

            // Ambil lokasi pengguna
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Set lokasi peta dan marker dengan zoom level yang lebih jauh (angka lebih kecil)
                    map.setView([lat, lng], 10);
                    marker.setLatLng([lat, lng]);

                    // Update input latitude dan longitude
                    $('#latitude').val(lat);
                    $('#longitude').val(lng);
                }, function(error) {
                    console.error("Geolocation error: ", error);
                    alert('Tidak dapat mengambil lokasi. Menampilkan lokasi default.');
                    // Lokasi fallback dengan zoom level yang lebih jauh
                    const defaultLat = -6.200000;
                    const defaultLng = 106.816666; // Jakarta
                    map.setView([defaultLat, defaultLng], 10);
                    marker.setLatLng([defaultLat, defaultLng]);
                });
            } else {
                alert('Geolocation tidak didukung oleh browser ini.');
            }
        });

        function initMap() {
            if (map) return; // Mencegah inisialisasi ganda

            // Elemen peta dengan attributionControl dinonaktifkan
            map = L.map('map', {
                attributionControl: false
            }).setView([0, 0], 10);

            // Layer peta dasar (OpenStreetMap) tanpa attribution
            const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: ''
            }).addTo(map);

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
            marker = L.marker([0, 0], {
                draggable: false,
                icon: redIcon
            }).addTo(map);
        }
    </script>
@endsection
