@extends('layouts.master')

@section('title')
    @lang('translation.dashboards')
@endsection

@section('css')
@endsection

@section('content')
    <div class="row">
        <!-- Chart 1: Komposisi Anggota (Pie Chart) -->
        <div class="col-xl-4">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Komposisi Anggota</h4>
                </div>

                <div class="card-body">
                    <div id="komposisi-anggota-chart" data-colors='["--vz-primary", "--vz-info"]' class="apex-charts"
                        dir="ltr"></div>
                </div>
            </div>
        </div>

        <!-- Chart 2: Distribusi Status Kasus (Donut Chart) -->
        <div class="col-xl-4">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Distribusi Status Kasus</h4>
                </div>

                <div class="card-body">
                    <div id="status-kasus-chart"
                        data-colors='["--vz-primary", "--vz-success", "--vz-warning", "--vz-danger"]' class="apex-charts"
                        dir="ltr"></div>
                </div>
            </div>
        </div>

        <!-- Chart 3: Tingkat Keparahan Kasus (Pie Chart) -->
        <div class="col-xl-4">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Tingkat Keparahan Kasus</h4>
                </div>

                <div class="card-body">
                    <div id="keparahan-kasus-chart" data-colors='["--vz-primary", "--vz-warning", "--vz-danger"]'
                        class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>

        <!-- Chart 4: Kategori Kasus (Donut Chart) -->
        <div class="col-xl-12">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Kategori Kasus</h4>
                </div>

                <div class="card-body">
                    <div id="kategori-kasus-chart"
                        data-colors='["--vz-primary", "--vz-danger", "--vz-success", "--vz-warning"]' class="apex-charts"
                        dir="ltr"></div>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Komposisi Anggota (Pie Chart)
            var totalAnggota = {!! json_encode($cards['total_anggota_polisi'] + $cards['total_anggota_warga']) !!};
            var anggotaLabels = ["Anggota Polisi", "Anggota Warga"];
            var anggotaData = [
                {!! json_encode($cards['total_anggota_polisi']) !!},
                {!! json_encode($cards['total_anggota_warga']) !!}
            ];

            var komposisiAnggotaChart = new ApexCharts(document.querySelector("#komposisi-anggota-chart"), {
                series: anggotaData,
                chart: {
                    type: "pie",
                    height: 300
                },
                labels: anggotaLabels,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: "bottom"
                        }
                    }
                }],
                colors: ["#4e73df", "#1cc88a"],
                legend: {
                    position: 'bottom'
                }
            });
            komposisiAnggotaChart.render();

            // Chart 2: Distribusi Status Kasus (Donut Chart)
            var statusKasus = {!! json_encode($cards['kasus_status']) !!};
            var statusLabels = Object.keys(statusKasus);
            var statusData = Object.values(statusKasus);

            var statusChart = new ApexCharts(document.querySelector("#status-kasus-chart"), {
                series: statusData,
                chart: {
                    type: "donut",
                    height: 300
                },
                labels: statusLabels,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: "bottom"
                        }
                    }
                }],
                colors: ["#1cc88a", "#e74a3b", "#f6c23e", "#36b9cc"],
                legend: {
                    position: 'bottom'
                }
            });
            statusChart.render();

            // Chart 3: Tingkat Keparahan Kasus (Pie Chart)
            var keparahanKasus = {!! json_encode($cards['kasus_keparahan']) !!};
            var keparahanLabels = Object.keys(keparahanKasus);
            var keparahanData = Object.values(keparahanKasus);

            var keparahanChart = new ApexCharts(document.querySelector("#keparahan-kasus-chart"), {
                series: keparahanData,
                chart: {
                    type: "pie",
                    height: 300
                },
                labels: keparahanLabels,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: "bottom"
                        }
                    }
                }],
                colors: ["#ffbb33", "#f39c12", "#dc3545"],
                legend: {
                    position: 'bottom'
                }
            });
            keparahanChart.render();

            // Chart 4: Kategori Kasus (Donut Chart)
            var kategoriKasus = {!! json_encode($cards['total_total_kasus_berdasarkan_kategori']) !!};
            var kategoriLabels = Object.keys(kategoriKasus);
            var kategoriData = Object.values(kategoriKasus);

            var kategoriChart = new ApexCharts(document.querySelector("#kategori-kasus-chart"), {
                series: [{
                    name: "Jumlah Kasus",
                    data: kategoriData
                }],
                chart: {
                    type: "bar",
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        columnWidth: '45%',
                        distributed: true
                    }
                },
                xaxis: {
                    categories: kategoriLabels,
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Jumlah Kasus'
                    }
                },
                colors: ["#4e73df", "#e74a3b", "#28a745", "#ffc107"],
                legend: {
                    show: false
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " kasus";
                        }
                    }
                }
            });
            kategoriChart.render();
        });
    </script>
@endsection
