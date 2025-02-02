<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-topbar="light">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{ $global_pengaturan_website->nama }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ $global_pengaturan_website->deskripsi }}" name="description" />
    <meta content="{{ $global_pengaturan_website->tagline }}" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('storage/' . $global_pengaturan_website->favicon) }}">
    @include('layouts.head-css')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
</head>

@yield('body')

@if (session()->has('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
            });
        });
    </script>
@elseif(session()->has('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: '{{ session('warning') }}',
            });
        });
    </script>
@elseif(session()->has('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
            });
        });
    </script>
@endif

@yield('content')

@include('layouts.vendor-scripts')


<script>
    function updateUserLocation(latitude, longitude) {
        fetch('/user/koordinate/latest', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                latitude: latitude,
                longitude: longitude
            })
        }).then(response => {
            if (!response.ok) {
                console.error("Failed to update location:", response.statusText);
            } else {
                console.log("Update user location succeed " + latitude + ' and ' + longitude);
            }
        }).catch(error => {
            console.error("Error sending location update:", error);
        });
    }

    function getCurrentLocation() {
        if ("geolocation" in navigator) {
            navigator.geolocation.watchPosition(
                function(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    setInterval(() => {
                        updateUserLocation(latitude, longitude);
                    }, 5000);
                },
                function(error) {
                    console.error("Error getting location:", error.message);
                    Swal.fire({
                        icon: 'warning',
                        title: 'Lokasi Tidak Tersedia',
                        text: 'Silahkan aktifkan izin lokasi terlebih dahulu',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        getCurrentLocation(); // Cek ulang setelah klik OK
                    });
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Geolokasi Tidak Didukung',
                text: 'Perangkat Anda tidak mendukung pelacakan lokasi',
                confirmButtonText: 'OK'
            }).then(() => {
                getCurrentLocation(); // Cek ulang setelah klik OK
            });
        }
    }

    document.addEventListener('DOMContentLoaded', getCurrentLocation);
</script>
</body>

</html>
