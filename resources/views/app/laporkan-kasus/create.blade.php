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

                                            <input type="number" name="kategori_kasus_id"
                                                value="{{ request()->get('kategori_kasus_id', 1) }}" hidden>

                                            <div class="col-12 col-lg-12 mb-3">
                                                <label for="tingkat_keparahan" class="form-label">Tingkat Keparahan<span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control form-select" name="tingkat_keparahan">
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
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script defer>
        // Map configuration constants
        const MAP_CONFIG = {
            defaultLocation: {
                lat: -6.200000,
                lng: 106.816666,
                zoom: 10
            },
            userLocationZoom: 15,
            tileLayerUrl: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            markerIconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            markerShadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png'
        };

        // Initialize map components
        let map = null;
        let marker = null;

        // Create marker icon once instead of on every marker creation
        const redIcon = L.icon({
            iconUrl: MAP_CONFIG.markerIconUrl,
            shadowUrl: MAP_CONFIG.markerShadowUrl,
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Initialize map with lazy loading
        function initMap() {
            if (map) return;

            // Create map with minimal initial configuration
            map = L.map('map', {
                attributionControl: false,
                zoomControl: true,
                minZoom: 3
            });

            // Add tile layer with better caching options
            L.tileLayer(MAP_CONFIG.tileLayerUrl, {
                maxZoom: 19,
                attribution: '',
                crossOrigin: true,
                updateWhenIdle: true,
                updateWhenZooming: false
            }).addTo(map);

            // Create marker
            marker = L.marker([0, 0], {
                draggable: false,
                icon: redIcon
            }).addTo(map);

            // Set initial view with animation disabled for faster initial load
            map.setView(
                [MAP_CONFIG.defaultLocation.lat, MAP_CONFIG.defaultLocation.lng],
                MAP_CONFIG.defaultLocation.zoom, {
                    animate: false
                }
            );
        }

        // Handle location updates
        function updateLocation(lat, lng, zoom) {
            if (!map || !marker) return;

            marker.setLatLng([lat, lng]);
            map.setView([lat, lng], zoom, {
                animate: false
            });

            // Update form inputs
            $('#latitude').val(lat);
            $('#longitude').val(lng);
        }

        // Get user location with timeout
        // Fungsi mendapatkan lokasi dengan fallback ke default
        async function getUserLocation() {
            return new Promise((resolve) => {
                if (!navigator.geolocation) {
                    console.warn("Geolocation tidak didukung di browser ini.");
                    resolve(null);
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        resolve({
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        });
                    },
                    (error) => {
                        console.warn("Gagal mendapatkan lokasi:", error.message);
                        resolve(null);
                    }, {
                        enableHighAccuracy: true, // Coba mendapatkan lokasi seakurat mungkin
                        timeout: 3000, // Batasi waktu tunggu agar tidak terlalu lama
                        maximumAge: 1000 // Gunakan cache lokasi terbaru jika ada
                    }
                );
            });
        }

        // Initialize everything when document is ready
        $(document).ready(async function() {
            // Initialize map
            initMap();

            const position = await getUserLocation();
            if (position) {
                updateLocation(position.lat, position.lng, MAP_CONFIG.userLocationZoom);
            } else {
                console.warn("Menggunakan lokasi default (Jakarta).");
                updateLocation(MAP_CONFIG.defaultLocation.lat, MAP_CONFIG.defaultLocation.lng, MAP_CONFIG
                    .defaultLocation.zoom);
            }
        });
    </script>
@endsection
