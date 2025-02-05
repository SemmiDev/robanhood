@extends('layouts.master')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/leaflet/leaflet.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .transition-all {
            transition: all 0.3s ease;
        }

        .hover\:shadow-lg:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .hover\:scale-105:hover {
            transform: scale(1.05);
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
                        <div class="p-2 mt-4 form-container">
                            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3">
                                @foreach ($kategoriKasus as $kasus)
                                    <div class="col">
                                        <a
                                        href="{{"/laporkan-kasus?kategori_kasus_id=" . $kasus->id}}"
                                            class="card h-100 text-center shadow-sm transition-all duration-300 hover:shadow-lg hover:scale-105">
                                            <div class="card-body d-flex flex-column align-items-center">
                                                <img src="{{ asset('storage/' . $kasus['simbol']) }}"
                                                    alt="{{ $kasus['nama'] }}"
                                                    class="img-fluid mb-2 transition-all duration-300 group-hover:scale-110"
                                                    style="max-height: 80px; max-width: 80px;">
                                                <h6 class="card-title transition-all duration-300 group-hover:text-primary">
                                                    {{ $kasus['nama'] }}</h6>
                                            </div>
                                            <div class="card-footer text-muted p-2">
                                                <marquee
                                                    class="d-block text-truncate transition-all duration-300 group-hover:text-primary">{{ $kasus['deskripsi'] }}</marquee>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
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
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/app/dashboard.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" async></script>

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

                    // Set lokasi peta dan marker
                    map.setView([lat, lng], 14);
                    marker.setLatLng([lat, lng]);

                    // Update input latitude dan longitude
                    $('#latitude').val(lat);
                    $('#longitude').val(lng);
                }, function(error) {
                    console.error("Geolocation error: ", error);
                    alert('Tidak dapat mengambil lokasi. Menampilkan lokasi default.');
                    // Lokasi fallback
                    const defaultLat = -6.200000;
                    const defaultLng = 106.816666; // Jakarta
                    map.setView([defaultLat, defaultLng], 18);
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
            }).setView([0, 0], 13);

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
            marker = L.marker([0, 0], {
                draggable: false,
                icon: redIcon
            }).addTo(map);
        }
    </script>
@endsection
