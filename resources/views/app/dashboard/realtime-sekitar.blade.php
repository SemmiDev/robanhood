<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Realtime</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #map-container {
            height: 100vh;
            width: 100vw;
            position: relative;
        }

        #map {
            height: 100%;
            width: 100%;
        }

        .recommendations {
            position: absolute;
            right: 0;
            bottom: 0;
            width: 500px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-height: 80vh;
            overflow-y: auto;
        }

        .recommendations h2 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }

        .alert-item {
            background: #fff;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 8px;
            border-left: 4px solid #ff4444;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .alert-item h3 {
            color: #333;
            font-size: 1rem;
            margin-bottom: 5px;
        }

        .alert-item p {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }

        .alert-item {
            background: #fff;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 8px;
            border-left: 4px solid #ff4444;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: start;
            gap: 12px;
        }

        .alert-icon {
            width: 20px;
            /* Reduced from 24px */
            height: 20px;
            /* Reduced from 24px */
            min-width: 20px;
            /* Prevent icon from shrinking */
            object-fit: contain;
            margin-top: 2px;
            /* Align with text */
        }

        .alert-content {
            flex: 1;
        }

        .pulse {
            background: rgba(255, 0, 0, 0.4);
            border-radius: 50%;
            height: 30px;
            width: 30px;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }

            100% {
                transform: translate(-50%, -50%) scale(10);
                opacity: 0;
            }
        }

        @media (max-width: 768px) {
            .recommendations {
                width: 100%;
                top: auto;
                bottom: 0;
                right: 0;
                max-height: 40vh;
                border-radius: 20px 20px 0 0;
            }
        }
    </style>
</head>

<body>
    <div id="map-container">
        <div id="map"></div>
        <div class="recommendations">
            <h2>🚨 Peringatan dan Rekomendasi</h2>
            <div id="alerts-list"></div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map with closer default zoom
            const map = L.map('map', {
                zoomControl: true,
                minZoom: 17,
            }).setView([0, 0], 18);

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


            // Ambil URL foto profil dari backend
            const userProfilePhoto =
                "{{ \App\Http\Controllers\Helpers\ProfilePhoto::get(auth()->user()->avatar, auth()->user()->name) }}";

            // Custom user location icon dengan foto profil
            const userIcon = L.divIcon({
                html: `
        <div class="pulse"></div>
        <div style="
            width: 40px;
            height: 40px;
            background: url('${userProfilePhoto}') center center / cover no-repeat;
            border-radius: 50%;
            border: 3px solid white;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        "></div>
    `,
                className: 'user-location-icon',
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });


            let userMarker = null;

            // Function to update recommendations from API
            function updateRecommendations(latitude, longitude) {
                fetch(`/realtime/sekitar/info?latitude=${latitude}&longitude=${longitude}`)
                    .then(response => response.json())
                    .then(data => {

                        const alertsList = document.getElementById('alerts-list');
                        alertsList.innerHTML = ''; // Clear existing alerts

                        data.rekomendasi.forEach(item => {
                            const alertElement = document.createElement('div');
                            alertElement.className = 'alert-item';
                            alertElement.innerHTML = `
                             <img src="${item.icon}" alt="Alert Icon" class="alert-icon">

                            <div class="alert-content">
                                    <h3>${item.saran}</h3>
                                    <p>${item.deskripsi}</p>
                                </div>
                            `;
                            alertsList.appendChild(alertElement);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching recommendations:', error);
                    });
            }

            // Initialize geolocation watching
            if ("geolocation" in navigator) {
                const options = {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                };

                // Watch position and update marker
                navigator.geolocation.watchPosition(function(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    // Update or create marker
                    if (userMarker === null) {
                        userMarker = L.marker([latitude, longitude], {
                            icon: userIcon
                        }).addTo(map);
                        map.setView([latitude, longitude], 18, {
                            animate: true,
                            duration: 1
                        });
                    } else {
                        userMarker.setLatLng([latitude, longitude]);
                        map.setView([latitude, longitude], map.getZoom(), {
                            animate: true,
                            duration: 1
                        });
                    }

                    // Update recommendations
                    updateRecommendations(latitude, longitude);

                }, function(error) {
                    console.error("Error getting location:", error);
                    alert("Unable to get your location. Please enable location services.");
                }, options);
            } else {
                alert("Geolocation is not supported by your browser");
            }
        });
    </script>
</body>

</html>
