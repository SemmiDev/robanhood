@extends('layouts.master')
@section('title')
    Rute Ke Lokasi Kejadian
@endsection

@section('css')
    <link href="{{ URL::asset('build/libs/leaflet/leaflet.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <style>
        #rute {
            z-index: 1;
            height: calc(100vh - 140px);
            width: 100%;
            margin: 0;
            padding: 0;
            border-radius: 1.5rem;
            /* Adds rounded corners */
            overflow: hidden;
            /* Ensures the map content respects the rounded corners */
        }

        .leaflet-popup-content img {
            border-radius: 50%;
            object-fit: cover;
        }

        .leaflet-popup-content h5 {
            margin-bottom: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
        }

        .leaflet-popup-content p {
            margin: 0;
            font-size: 0.875rem;
            color: #6c757d;
        }

        .custom-user-icon img {
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.5);
        }

        .pulse-container {
            position: relative;
            width: 40px;
            height: 40px;
        }

        .pulse-container .pulse {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 0, 0, 0.4);
            border-radius: 50%;
            animation: pulse-animation 1.5s infinite;
        }

        @keyframes pulse-animation {
            0% {
                transform: scale(1);
                opacity: 0.6;
            }

            100% {
                transform: scale(2.5);
                opacity: 0;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $profilePhoto = \App\Http\Controllers\Helpers\ProfilePhoto::get(
            auth()->user()->avatar ?? '',
            auth()->user()->name ?? 'Anonymous',
        );
    @endphp

    <div id="rute"></div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script src="{{ URL::asset('build/libs/leaflet/leaflet.js') }}"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map
            const map = L.map('rute').setView([-6.2088, 106.8456], 13);

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);


            // Kasus coordinates
            const kasusCoords = [{{ $kasus->latitude }}, {{ $kasus->longitude }}];

            // Add kasus marker with popup
            const kasusMarker = L.marker(kasusCoords, {
                icon: L.divIcon({
                    className: 'custom-kasus-icon',
                    html: `
            <div class="pulse-container">
                <div class="pulse"></div>
                <img src="{{ asset('storage/' . $kasus->kategori_kasu->simbol) }}" alt="Kasus" width="40" height="40" />
            </div>
        `,
                    iconSize: [40, 40],
                    popupAnchor: [0, -20]
                })
            }).addTo(map);


            const kasusPopupContent = `
                <div class="p-3">
                    <h5 class="mb-2">Lokasi Kejadian</h5>
                    <p class="mb-1"><strong>Kategori:</strong> {{ $kasus->kategori_kasu->nama }}</p>
                    <p class="mb-1"><strong>Deskripsi:</strong> {{ $kasus->deskripsi }}</p>
                    <p class="mb-0"><strong>Tingkat Keparahan:</strong> {{$kasus->tingkat_keparahan}}</p>
                    <div class="mt-2">
                        <a href="{{ route('manajemenKasus.show', ['id' => $kasus->id]) }}"
                        class="btn btn-info text-white btn-sm"
                        target="_blank">Lihat Detail</a>
                    </div>
                </div>
            `;


            kasusMarker.bindPopup(kasusPopupContent).openPopup();

            // Get current position and create route
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const userCoords = [position.coords.latitude, position.coords.longitude];

                    // Add user marker with popup
                    const userMarker = L.marker(userCoords, {
                        icon: L.divIcon({
                            className: 'custom-user-icon',
                            html: `<img src="{{ $profilePhoto }}" alt="User" class="rounded-circle" width="40" height="40" style="border: 2px solid #0d6efd;"/>`,
                            iconSize: [40, 40],
                            popupAnchor: [0, -20]
                        })
                    }).addTo(map);


                    const userPopupContent = `
                        <div class="d-flex align-items-center p-2">
                            <img src="{{ $profilePhoto }}" alt="Avatar" class="rounded-circle me-2" width="40" height="40" />
                            <div>
                                <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                                <p class="mb-0"><strong>Tujuan Kejadian:</strong> {{ $kasus->judul }}</p>
                                <div class="mt-2">
                                    <a href="{{ route('manajemenKasus.show', ['id' => $kasus->id]) }}"
                                    class="btn btn-info text-white btn-sm"
                                    target="_blank">Lihat Detail Kejadian</a>
                                </div>
                            </div>
                        </div>
                    `;

                    userMarker.bindPopup(userPopupContent);

                    // Create routing control
                    const routingControl = L.Routing.control({
                        waypoints: [
                            L.latLng(userCoords[0], userCoords[1]),
                            L.latLng(kasusCoords[0], kasusCoords[1])
                        ],
                        routeWhileDragging: true,
                        showAlternatives: true,
                        lineOptions: {
                            styles: [{
                                color: '#0d6efd',
                                opacity: 0.8,
                                weight: 5
                            }]
                        },
                        createMarker: function() {
                            return null;
                        } // Menghindari marker ganda
                    }).addTo(map);

                    // Fit bounds to show both markers
                    const bounds = L.latLngBounds([userCoords, kasusCoords]);
                    map.fitBounds(bounds, {
                        padding: [50, 50]
                    });

                    // Watch position updates
                    navigator.geolocation.watchPosition(function(newPosition) {
                        const newCoords = [newPosition.coords.latitude, newPosition.coords
                            .longitude
                        ];
                        userMarker.setLatLng(newCoords);
                        userMarker.getPopup().setContent(`
                           <div class="d-flex align-items-center p-2">
                                <img src="{{ $profilePhoto }}" alt="Avatar" class="rounded-circle me-2" width="40" height="40" />
                                <div>
                                    <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                                    <p class="mb-0"><strong>Tujuan Kejadian:</strong> {{ $kasus->judul }}</p>
                                    <div class="mt-2">
                                        <a href="{{ route('manajemenKasus.show', ['id' => $kasus->id]) }}"
                                        class="btn btn-info text-white btn-sm"
                                        target="_blank">Lihat Detail Kejadian</a>
                                    </div>
                                </div>
                            </div>
                        `);
                        routingControl.setWaypoints([
                            L.latLng(newCoords[0], newCoords[1]),
                            L.latLng(kasusCoords[0], kasusCoords[1])
                        ]);
                    });

                    // Call the function every 5 seconds
                    setInterval(() => {
                        navigator.geolocation.getCurrentPosition(function(newPosition) {
                            const latitude = newPosition.coords.latitude;
                            const longitude = newPosition.coords.longitude;

                            // Update the marker's position
                            userMarker.setLatLng([latitude, longitude]);

                            console.log("sukses")
                        }, function(error) {
                            console.error("Error updating location:", error);
                        });
                    }, 5000); // 5s
                }, function(error) {
                    console.error("Error getting location:", error);
                    alert("Tidak dapat mengakses lokasi Anda. Mohon aktifkan GPS.");
                });
            } else {
                alert("Browser Anda tidak mendukung geolocation");
            }

            // Add layer controls
            const baseMaps = {
                "Street": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'),
                "Satellite": L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'
                )
            };

            L.control.layers(baseMaps).addTo(map);
        });
    </script>
@endsection
