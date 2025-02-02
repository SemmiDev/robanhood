@extends('layouts.master-without-nav')
@section('title')
    Peta Lokasi Polisi Realtime
@endsection

@section('css')
    <link href="{{ URL::asset('build/libs/leaflet/leaflet.css') }}" rel="stylesheet" type="text/css" />
    <style>
        #persebaran-kasus-dan-polisi {
            height: 100vh;
            width: 100vw;
            margin: 0;
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
        }

        .pulse-MENUNGGU {
            background: rgba(255, 0, 0, 0.5);
            border-radius: 50%;
            height: 30px;
            width: 30px;
            position: absolute;
            left: 50%;
            top: 50%;
            animation: pulse 1s infinite;
            transform-origin: center center;
        }

        .pulse-DALAM_PROSES {
            background: rgba(13, 219, 112, 0.5);
            border-radius: 50%;
            height: 30px;
            width: 30px;
            position: absolute;
            left: 50%;
            top: 50%;
            animation: pulse 1s infinite;
            transform-origin: center center;
        }

        @keyframes pulse {
            0% {
                transform: scale(1) translate(-50%, -50%);
                opacity: 0.5;
            }

            100% {
                transform: scale(8) translate(-7%, -7%);
                opacity: 0;
            }
        }
    </style>
@endsection

@section('content')
    <div id="persebaran-kasus-dan-polisi" class="persebaran-kasus-dan-polisi"></div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="{{ URL::asset('build/libs/leaflet/leaflet.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/satellite.js/3.0.0/satellite.min.js"></script>

    <script>
        $(document).ready(function() {
            const map = L.map('persebaran-kasus-dan-polisi', {
                crs: L.CRS.EPSG3857,
                preferCanvas: true,
                doubleClickZoom: true,
                scrollWheelZoom: true,
                dragging: true,
            });

            const zoomControl = L.control.zoom({
                position: 'bottomright',
            }).addTo(map);

            $('.leaflet-control-zoom-in').off('click').on('click', function() {
                const currentZoom = map.getZoom();
                map.setZoom(currentZoom + 2); // Zoom in dua tingkat
            });

            $('.leaflet-control-zoom-out').off('click').on('click', function() {
                const currentZoom = map.getZoom();
                map.setZoom(currentZoom - 2); // Zoom out dua tingkat
            });

            const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            });

            var satelliteLayer = L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
                });

            streetLayer.addTo(map);

            const policeIcon = L.icon({
                iconUrl: '/polisi.png', // Ganti dengan path gambar Anda
                iconSize: [40, 40], // Ukuran ikon
                iconAnchor: [0, 0], // Titik di mana ikon "menempel" pada koordinat
                popupAnchor: [0, -40] // Posisi popup relatif terhadap ikon
            });

            const policeAreOnDutyIcon = L.icon({
                iconUrl: '/polisi-drive.png', // Ganti dengan path gambar Anda
                iconSize: [40, 40], // Ukuran ikon
                iconAnchor: [0, 0], // Titik di mana ikon "menempel" pada koordinat
                popupAnchor: [0, -40] // Posisi popup relatif terhadap ikon
            });

            let markers = {};
            let kasusMarkers = {};
            let initialFit = true; // Flag untuk mengetahui apakah fitBounds dilakukan pertama kali

            function updateMarkers(usersCoords) {
                // Get current user IDs
                const currentUserIds = new Set(usersCoords.map(user => user.id));

                // Remove markers for users no longer in the list
                Object.keys(markers).forEach(userId => {
                    if (!currentUserIds.has(parseInt(userId))) {
                        map.removeLayer(markers[userId]);
                        delete markers[userId];
                    }
                });

                let newMarkerAdded = false;

                usersCoords.forEach(user => {
                    if (markers[user.id]) {
                        // Update existing marker position and popup
                        const marker = markers[user.id];
                        marker.setLatLng(user.lastCoordinate);
                        marker.getPopup().setContent(user.bindPopup);

                        // Update icon based on user status if needed
                        const icon = user.onDuty ? policeAreOnDutyIcon : policeIcon;
                        marker.setIcon(icon);
                    } else {
                        // Create new marker
                        const marker = L.marker(user.lastCoordinate, {
                            icon: user.onDuty ? policeAreOnDutyIcon : policeIcon
                        }).addTo(map);
                        marker.bindPopup(user.bindPopup);
                        markers[user.id] = marker;
                        newMarkerAdded = true;
                    }
                });

                // Adjust map view only on initial load or new markers
                if (initialFit || newMarkerAdded) {
                    const bounds = Object.values(markers).map(marker => marker.getLatLng());
                    if (bounds.length) {
                        map.fitBounds(bounds, {
                            padding: [50, 50],
                            // maxZoom: 18
                        });
                    }
                    initialFit = false;
                }
            }

            function updateKasusMarkers(listKasus) {
                // Buat set ID kasus yang baru
                const currentKasusIds = new Set(listKasus.map(kasus => kasus.id));

                // Hapus marker yang tidak ada dalam data baru
                Object.keys(kasusMarkers).forEach(kasusId => {
                    if (!currentKasusIds.has(parseInt(kasusId))) {
                        map.removeLayer(kasusMarkers[kasusId]);
                        delete kasusMarkers[kasusId];
                    }
                });

                // Update atau tambahkan marker baru
                listKasus.forEach(kasus => {
                    const coordinates = kasus.coordinate;
                    if (!coordinates || coordinates.length !== 2) return; // Skip jika koordinat tidak valid

                    let pulseStatusClassName = 'pulse-MENUNGGU pulse';
                    if (kasus.status === 'DALAM_PROSES') {
                        pulseStatusClassName = 'pulse-DALAM_PROSES pulse';
                    }

                    const newIconHtml = `
            <div class="${pulseStatusClassName}"></div>
            <img src="${kasus.simbol}" alt="${kasus.judul}"
                 style="width: 30px; height: 30px; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
        `;

                    if (kasusMarkers[kasus.id]) {
                        // Marker sudah ada, cek apakah perlu diperbarui
                        const existingMarker = kasusMarkers[kasus.id];

                        const oldCoordinates = existingMarker.getLatLng();
                        const hasCoordinateChanged = oldCoordinates.lat !== coordinates[0] || oldCoordinates
                            .lng !== coordinates[1];
                        const hasIconChanged = existingMarker.options.icon.options.html !== newIconHtml;
                        const hasPopupChanged = existingMarker.getPopup().getContent() !== kasus.html;

                        if (hasCoordinateChanged || hasIconChanged || hasPopupChanged) {
                            // Hapus dan buat ulang marker jika ada perubahan
                            map.removeLayer(existingMarker);
                            delete kasusMarkers[kasus.id];

                            const newMarker = createMarker(kasus, coordinates, newIconHtml);
                            kasusMarkers[kasus.id] = newMarker;
                        }
                    } else {
                        // Marker belum ada, buat baru
                        const newMarker = createMarker(kasus, coordinates, newIconHtml);
                        kasusMarkers[kasus.id] = newMarker;
                    }
                });
            }

            // Fungsi untuk membuat marker baru
            function createMarker(kasus, coordinates, iconHtml) {
                const marker = L.marker(coordinates, {
                    icon: L.divIcon({
                        html: iconHtml,
                        className: 'custom-icon',
                        iconSize: [30, 30],
                        iconAnchor: [15, 15],
                        popupAnchor: [0, -15]
                    })
                }).addTo(map);

                marker.bindPopup(kasus.html);
                return marker;
            }

            function fetchLatestKasus() {
                $.ajax({
                    url: '/get-latest-kasus/{{$id}}',
                    method: 'GET',
                    success: function(response) {
                        updateKasusMarkers(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching kasus data:', error);
                    }
                });
            }

            function fetchLatestCoords() {
                $.ajax({
                    url: '/get-latest-coord-polisi-assign/{{$id}}',
                    method: 'GET',
                    success: function(userCoords) {
                        updateMarkers(userCoords);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching coordinates:', error);
                    }
                });
            }

            fetchLatestCoords();
            setInterval(fetchLatestCoords, 3000);

            fetchLatestKasus();
            setInterval(fetchLatestKasus, 3000);

            const baseLayers = {
                "Jalan": streetLayer,
                "Satelit": satelliteLayer,
            };

            L.control.layers(baseLayers, null, {
                collapsed: false,
                position: 'topright'
            }).addTo(map);

            L.control.scale({
                metric: true,
                imperial: false,
                position: 'bottomleft'
            }).addTo(map);

        });
    </script>
@endsection
