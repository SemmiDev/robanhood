@extends('layouts.master')
@section('title')
    Manajemen Kasus Realtime
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="d-flex justify-content-center align-items-center">
                    <h4 class="mb-sm-0 font-size-18">Laporan Kasus Realtime</h4>
                    <button class="btn btn-load">
                        <span class="d-flex align-items-center">
                            <span class="spinner-grow flex-shrink-0" role="status">
                                <span class="visually-hidden"></span>
                            </span>
                        </span>
                    </button>

                </div>

                <div class="page-title-right d-flex flex-wrap align-items-center gap-2">
                    <label for="update-interval" class="form-label me-2 mb-0 text-center text-md-start">Perbarui setiap:</label>
                    <select id="update-interval" class="form-select w-100 w-md-auto">
                        <option value="1000">1 detik</option>
                        <option value="2000">2 detik</option>
                        <option value="3000" selected>3 detik</option>
                        <option value="4000">4 detik</option>
                        <option value="5000">5 detik</option>
                    </select>
                    <p id="last-update" class="mb-0 text-center text-md-start">
                        Terakhir di update {{ now()->format('Y-m-d H:i:s') }}
                    </p>
                </div>


            </div>
        </div>
    </div>


    <div class="row mb-3">
        <div class="col-md-2">
            <label for="filter-keparahan" class="form-label">Tingkat Keparahan</label>
            <select id="filter-keparahan" class="form-select">
                <option value="">Semua</option>
                <option value="RINGAN">Ringan</option>
                <option value="SEDANG">Sedang</option>
                <option value="BERAT">Berat</option>
                <option value="LAINNYA">Lainnya</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="filter-status" class="form-label">Status</label>
            <select id="filter-status" class="form-select">
                <option value="">Semua</option>
                <option value="MENUNGGU">Menunggu</option>
                <option value="DITUGASKAN">Ditugaskan</option>
                <option value="DALAM_PROSES">Dalam Proses</option>
                <option value="SELESAI">Selesai</option>
                <option value="DITUTUP">Ditutup</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="sort-by" class="form-label">Sort By</label>
            <select id="sort-by" class="form-select">
                <option value="">Default</option>
                <option value="jarak">Jarak</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="sort-order" class="form-label">Urutan</label>
            <select id="sort-order" class="form-select">
                <option value="asc">Menaik</option>
                <option value="desc">Menurun</option>
            </select>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-nowrap align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Kasus</th>
                                    <th scope="col">Kategori</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Keparahan</th>
                                    <th scope="col">Perkiraan Jarak anda ke lokasi kejadian</th>
                                    <th scope="col">Rute</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
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

    <script>
        const routes = {
            detail: "{{ route('manajemenKasus.show', ['id' => ':id']) }}",
            rute: "{{ route('manajemenKasus.rute', ['id' => ':id']) }}"
        };
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const earthRadius = 6371000; // Radius bumi dalam meter
            let updateInterval = 3000; // Default interval 3 detik
            let intervalId;

            // Fungsi untuk menghitung jarak dengan formula Haversine
            function calculateDistance(lat1, lon1, lat2, lon2) {
                const toRad = (value) => (value * Math.PI) / 180;
                const latDelta = toRad(lat2 - lat1);
                const lonDelta = toRad(lon2 - lon1);
                const lat1Rad = toRad(lat1);
                const lat2Rad = toRad(lat2);

                const a =
                    Math.sin(latDelta / 2) * Math.sin(latDelta / 2) +
                    Math.cos(lat1Rad) * Math.cos(lat2Rad) *
                    Math.sin(lonDelta / 2) * Math.sin(lonDelta / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                return earthRadius * c;
            }

            // Fungsi untuk memfilter data
            function filterData(data, keparahan, status) {
                return data.filter(item => {
                    const matchesKeparahan = !keparahan || item.tingkat_keparahan === keparahan;
                    const matchesStatus = !status || item.status === status;
                    return matchesKeparahan && matchesStatus;
                });
            }

            // Fungsi untuk memuat data dengan penanganan error yang lebih baik
            function loadData() {
                // Cek apakah browser mendukung geolocation
                if (!navigator.geolocation) {
                    console.error("Geolocation tidak didukung browser");
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const userLat = position.coords.latitude;
                        const userLon = position.coords.longitude;

                        // Tambahkan error handling untuk fetch
                        fetch('/ajax/manajemen-kasus', {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log(data);

                                const keparahan = document.getElementById('filter-keparahan').value;
                                const status = document.getElementById('filter-status').value;
                                const sortBy = document.getElementById('sort-by').value;
                                const sortOrder = document.getElementById('sort-order').value;

                                // Filter data
                                const filteredData = filterData(data, keparahan, status);

                                // Hitung jarak untuk setiap item
                                const dataWithDistance = filteredData.map(item => {
                                    const distance = calculateDistance(userLat, userLon, item
                                        .latitude, item.longitude);
                                    return {
                                        ...item,
                                        distance
                                    };
                                });

                                // Sorting data berdasarkan jarak dan urutan
                                if (sortBy === 'jarak') {
                                    dataWithDistance.sort((a, b) => {
                                        return sortOrder === 'asc' ? a.distance - b.distance : b
                                            .distance - a.distance;
                                    });
                                }

                                // Update tabel
                                const tbody = document.querySelector("table tbody");
                                tbody.innerHTML = ""; // Kosongkan tabel sebelumnya

                                dataWithDistance.forEach((item, index) => {
                                    const tr = document.createElement('tr');
                                    tr.innerHTML = `
                                <td class="fw-medium">${index + 1}</td>
                                <td>${item.judul ?? ''}</td>
                                <td>${item.kategori_kasu?.nama ?? 'sos'}</td>
                                <td>${item.status ?? ''}</td>
                                <td>${item.tingkat_keparahan ?? ''}</td>
                                <td>${(item.distance / 1000).toFixed(2)} KM</td>

                                <td>
                                    <a class="btn btn-sm btn-info" href="${item.rute}">Lihat Rute
                                    </a>
                                </td>

                                <td>
                                    <a class="btn btn-sm btn-primary" href="${item.show}">Lihat Detail</a>
                                </td>
                            `;
                                    tbody.appendChild(tr);
                                });

                                // Perbarui waktu terakhir update
                                const now = new Date();
                                document.getElementById('last-update').textContent =
                                    `Terakhir di update ${now.toLocaleString()}`;
                            })
                            .catch(error => {
                                console.error("Error fetching data:", error);
                                // Tambahkan notifikasi error untuk pengguna
                                document.getElementById('last-update').textContent =
                                    `Gagal memperbarui data: ${error.message}`;
                            });
                    },
                    (error) => {
                        console.error("Kesalahan lokasi:", error.message);
                        alert("Tidak dapat mengakses lokasi. Mohon izinkan akses lokasi.");
                    }
                );
            }

            // Fungsi untuk memulai interval pembaruan
            function startInterval() {
                clearInterval(intervalId);
                intervalId = setInterval(loadData, updateInterval);
            }

            // Event listener untuk dropdown interval pembaruan
            document.getElementById('update-interval').addEventListener('change', function() {
                updateInterval = parseInt(this.value, 10);
                startInterval();
            });

            // Event listener untuk filter dan sorting
            // Event listener untuk filter dan sorting
            ['filter-keparahan', 'filter-status', 'sort-by', 'sort-order'].forEach(id => {
                const element = document.getElementById(id);
                element.addEventListener('change', () => setTimeout(loadData, 50));
                element.addEventListener('input', () => setTimeout(loadData, 50)); // Tambahan untuk mobile
            });

            // document.getElementById('filter-keparahan').addEventListener('change', loadData);
            // document.getElementById('filter-status').addEventListener('change', loadData);
            // document.getElementById('sort-by').addEventListener('change', loadData);
            // document.getElementById('sort-order').addEventListener('change', loadData);

            // Mulai interval pembaruan awal
            startInterval();
            loadData();
        });
    </script>
@endsection
