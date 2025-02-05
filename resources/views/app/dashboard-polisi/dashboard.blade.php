@extends('layouts.master')

@section('title')
    @lang('translation.dashboards')
@endsection

@section('css')
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row g-4 mb-3">
        <div class="col-sm-auto">
            <div class="flex-grow-1">
                <h4 class="fs-16 mb-1">{{ $greeting }}</h4>
                <p class="text-muted mb-0">Insight Utama dari Data yang Kami Kumpulkan.</p>
            </div>

        </div>

        {{-- filter --}}
        <div class="col-sm">
            <div class="d-flex justify-content-sm-end gap-2">
                <div>

                    <form action="{{ route('root.dashboardPolisi') }}" method="GET">
                        <div class="row g-3 mb-0 align-items-center">
                            <div class="col-sm-auto">
                                <div class="input-group">
                                    <input type="date" id="start_date" name="start_date"
                                        class="form-control border-0 dash-filter-picker shadow"
                                        value="{{ old('start_date', isset($filter['startDate']) ? \Carbon\Carbon::parse($filter['startDate'])->format('Y-m-d') : \Carbon\Carbon::now()->startOfYear()->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-sm-auto">
                                <div class="input-group">
                                    <input type="date" id="end_date" name="end_date"
                                        class="form-control border-0 dash-filter-picker shadow"
                                        value="{{ old('end_date', isset($filter['endDate']) ? \Carbon\Carbon::parse($filter['endDate'])->format('Y-m-d') : \Carbon\Carbon::now()->endOfYear()->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-sm-auto">
                                <button type="submit" class="btn btn-primary">
                                    Filter
                                </button>
                                <a href="{{ route('root.dashboardPolisi') }}" class="btn btn-warning">
                                    Reset
                                </a>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col">

            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                            <div class="flex-grow-1">
                                <div>
                                    <button data-bs-toggle="modal" data-bs-target="#ubah-status-modal"
                                        class="btn btn-soft-info btn-load">
                                        <span class="d-flex align-items-center">
                                            <span class="spinner-grow flex-shrink-0" role="status">
                                                <span class="visually-hidden">Status</span>
                                            </span>
                                            <span class="flex-grow-1 ms-2">
                                                {{ str_replace('_', ' ', auth()->user()->status) }}
                                            </span>
                                        </span>
                                    </button>

                                    <div class="hstack gap-3 fs-16">
                                        <div id="ubah-status-modal" class="modal fade" tabindex="-1"
                                            aria-labelledby="ubah-status-modal-label" aria-hidden="true">
                                            <div class="modal-dialog modal-md">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="ubah-status-modal-label">
                                                            Ubah Status
                                                        </h5>

                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('user.changeStatus') }}" method="POST">
                                                            @csrf

                                                            <div class="mb-3">
                                                                <label for="ketua" class="form-label text-start">Pilih
                                                                    Status</label>
                                                                @php
                                                                    $status = [
                                                                        'SEDANG_TIDAK_BERTUGAS',
                                                                        'SEDANG_BERTUGAS',
                                                                        'SEDANG_MENANGANI_KASUS',
                                                                    ];
                                                                @endphp

                                                                <select name="status" class="form-control">
                                                                    @foreach ($status as $s)
                                                                        <option value="{{ $s }}"
                                                                            {{ auth()->user()->status == $s ? 'selected' : '' }}>
                                                                            {{ str_replace('_', ' ', $s) }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="mt-4">
                                                                <button class="btn btn-success w-100"
                                                                    type="submit">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 mt-lg-0">
                            </div>
                        </div><!-- end card header -->
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

                <div class="row">
                    <!-- Total Kontribusi -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Kontribusi
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-20 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                data-target="{{ $cards['total_terlibat'] }}">0</span> Kasus</h4>
                                        {{-- <a href="{{route('profil')}}" class="text-decoration-underline">Lihat Riwayat Kasus</a> --}}
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-info-subtle rounded fs-3">
                                            <i class="bx bx-group text-info"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kasus Diselesaikan -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Kasus
                                            Diselesaikan</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-20 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                data-target="{{ $cards['total_selesai'] }}">0</span> Kasus</h4>
                                        {{-- <a href="" class="text-decoration-underline">Lihat Riwayat Kasus Yang
                                            Diselesaikan</a> --}}
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-success-subtle rounded fs-3">
                                            <i class="bx bx-check-circle text-success"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kasus Dalam Proses -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Kasus
                                            Dalam Proses</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-20 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                data-target="{{ $cards['total_dalam_proses'] }}">0</span> Kasus</h4>
                                        {{-- <a href="" class="text-decoration-underline">Lihat Riwayat Kasus Dalam Proses</a> --}}
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-success-subtle rounded fs-3">
                                            <i class="bx bx-check-circle text-success"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Poin -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Poin</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-20 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                data-target="{{ $cards['total_poin'] }}">0</span> Poin</h4>
                                        {{-- <a href="" class="text-decoration-underline">Lihat Riwayat Kasus</a> --}}
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-warning-subtle rounded fs-3">
                                            <i class="bx bx-star text-warning"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Kasus Berdasarkan Kategori</h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div id="polisi-kasus-berdasarkan-kategori-source"
                                    data-colors='["--vz-primary", "--vz-success", "--vz-warning", "--vz-danger", "--vz-dark", "--vz-info"]'
                                    class="apex-charts" dir="ltr"></div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div>

                    <div class="col-xl-4">
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Tingkat Keparahan Kasus</h4>
                            </div>

                            <div class="card-body">
                                <div id="polisi-tingkat-keparahan-source"
                                    data-colors='["--vz-primary", "--vz-success", "--vz-warning", "--vz-danger"]'
                                    class="apex-charts" dir="ltr"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Status</h4>
                            </div>

                            <div class="card-body">
                                <div id="polisi-status-kasus-source"
                                    data-colors='["--vz-primary", "--vz-success", "--vz-warning", "--vz-danger"]'
                                    class="apex-charts" dir="ltr"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    {{-- <script src="{{ URL::asset('build/js/app/dashboard.js') }}"></script> --}}
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>

    <script>
        function getChartColorsArray(chartId) {
            if (document.getElementById(chartId) !== null) {
                var colors = document.getElementById(chartId).getAttribute("data-colors");
                colors = JSON.parse(colors);
                return colors.map(function(value) {
                    var newValue = value.replace(" ", "");
                    if (newValue.indexOf(",") === -1) {
                        var color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
                        if (color) return color;
                        else return newValue;;
                    } else {
                        var val = value.split(',');
                        if (val.length == 2) {
                            var rgbaColor = getComputedStyle(document.documentElement).getPropertyValue(val[0]);
                            rgbaColor = "rgba(" + rgbaColor + "," + val[1] + ")";
                            return rgbaColor;
                        } else {
                            return newValue;
                        }
                    }
                });
            }
        }

        // Chart 1: Tingkat Keparahan Kasus
        var kasusKeparahan = {!! json_encode($cards['kasus_keparahan']) !!}; // Data dari backend
        var seriesKeparahan = Object.values(kasusKeparahan); // Nilai keparahan
        var labelsKeparahan = Object.keys(kasusKeparahan); // Label keparahan

        var chartBarKeparahanColors = getChartColorsArray("polisi-tingkat-keparahan-source");

        if (chartBarKeparahanColors) {
            var optionsKeparahan = {
                series: [{
                    name: "Jumlah Kasus",
                    data: seriesKeparahan, // Data series
                }],
                chart: {
                    type: "bar", // Tipe chart bar
                    height: 333, // Tinggi chart
                },
                plotOptions: {
                    bar: {
                        horizontal: false, // Bar vertikal
                        columnWidth: "50%", // Lebar kolom
                    },
                },
                dataLabels: {
                    enabled: true, // Menampilkan data labels di dalam kolom
                    style: {
                        fontSize: '12px',
                        fontWeight: 'bold',
                        colors: ['#fff'] // Warna label putih di dalam kolom
                    },
                    dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 3,
                        opacity: 0.2
                    },
                },
                xaxis: {
                    categories: labelsKeparahan, // Label kategori di sumbu X
                    title: {
                        text: 'Tingkat Keparahan',
                        style: {
                            fontSize: '14px',
                            fontWeight: 'bold',
                            color: '#000',
                        }
                    },
                },
                yaxis: {
                    title: {
                        text: 'Jumlah Kasus',
                        style: {
                            fontSize: '14px',
                            fontWeight: 'bold',
                            color: '#000',
                        }
                    },
                },
                colors: chartBarKeparahanColors, // Warna kolom
                legend: {
                    position: "top", // Posisi legenda di atas
                },
            };

            var chartKeparahan = new ApexCharts(
                document.querySelector("#polisi-tingkat-keparahan-source"),
                optionsKeparahan
            );
            chartKeparahan.render();
        }

        // Chart 2: Status Kasus
        var kasusStatus = {!! json_encode($cards['kasus_status']) !!}; // Data dari backend
        var seriesStatus = Object.values(kasusStatus); // Nilai status
        var labelsStatus = Object.keys(kasusStatus); // Label status

        var chartBarStatusColors = getChartColorsArray("polisi-status-kasus-source");

        if (chartBarStatusColors) {
            var optionsStatus = {
                series: [{
                    name: "Jumlah Kasus",
                    data: seriesStatus, // Data series
                }],
                chart: {
                    type: "bar", // Tipe chart bar
                    height: 333, // Tinggi chart
                },
                plotOptions: {
                    bar: {
                        horizontal: false, // Bar vertikal
                        columnWidth: "50%", // Lebar kolom
                    },
                },
                dataLabels: {
                    enabled: true, // Menampilkan data labels di dalam kolom
                    style: {
                        fontSize: '12px',
                        fontWeight: 'bold',
                        colors: ['#fff'] // Warna label putih di dalam kolom
                    },
                    dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 3,
                        opacity: 0.2
                    },
                },
                xaxis: {
                    categories: labelsStatus, // Label kategori di sumbu X
                    title: {
                        text: 'Status Kasus',
                        style: {
                            fontSize: '14px',
                            fontWeight: 'bold',
                            color: '#000',
                        }
                    },
                },
                yaxis: {
                    title: {
                        text: 'Jumlah Kasus',
                        style: {
                            fontSize: '14px',
                            fontWeight: 'bold',
                            color: '#000',
                        }
                    },
                },
                colors: chartBarStatusColors, // Warna kolom
                legend: {
                    position: "top", // Posisi legenda di atas
                },
            };

            var chartStatus = new ApexCharts(
                document.querySelector("#polisi-status-kasus-source"),
                optionsStatus
            );
            chartStatus.render();
        }
    </script>



    <script>
        // Chart: Kasus Berdasarkan Kategori (Bar Chart)
        var kasusKategori = {!! json_encode($cards['total_total_kasus_berdasarkan_kategori']) !!}; // Data dari backend
        var seriesKategori = Object.values(kasusKategori); // Nilai kategori
        var labelsKategori = Object.keys(kasusKategori); // Label kategori

        var chartBarKategoriColors = getChartColorsArray("polisi-kasus-berdasarkan-kategori-source");

        if (chartBarKategoriColors) {
            var optionsKategori = {
                series: [{
                    name: "Jumlah Kasus",
                    data: seriesKategori, // Data series
                }],
                chart: {
                    type: "bar", // Tipe chart bar
                    height: 333, // Tinggi chart
                },
                plotOptions: {
                    bar: {
                        horizontal: false, // Bar vertikal
                        columnWidth: "50%", // Lebar bar
                    },
                },
                dataLabels: {
                    enabled: true, // Tampilkan nilai di atas bar
                    formatter: function(val) {
                        return val + " kasus";
                    },
                },
                colors: chartBarKategoriColors, // Warna bar
                xaxis: {
                    categories: labelsKategori, // Label kategori pada sumbu X
                },
                yaxis: {
                    title: {
                        text: "Jumlah Kasus", // Label sumbu Y
                    },
                },
                legend: {
                    position: "bottom", // Posisi legenda di bawah
                },
                grid: {
                    borderColor: "#f1f1f1", // Warna grid
                },
            };

            var chartKategori = new ApexCharts(
                document.querySelector("#polisi-kasus-berdasarkan-kategori-source"),
                optionsKategori
            );
            chartKategori.render();
        }
    </script>
@endsection
