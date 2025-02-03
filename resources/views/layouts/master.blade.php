<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="horizontal" data-topbar="light"
    data-sidebar="light" data-sidebar-image="none" data-preloader="disable">

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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"
        type="text/css" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(async function(OneSignal) {
            try {
                await OneSignal.init({
                    appId: "37ff0e3f-4bea-494a-b17f-0da06fa8bba4",
                });

                const onesignalId = OneSignal.User._currentUser.onesignalId;
                const userId = OneSignal.User.PushSubscription._id;

                console.info("onesignalId = " + onesignalId);
                console.info("user id = " + userId);

                // Check if onesignalId is defined and not empty
                if (userId && userId.trim() !== '' && userId.trim() != 'undefined') {
                    $.ajax({
                        url: '/onesignal/register?oneSignalId=' + userId,
                        method: 'GET',
                        success: function(response) {
                            console.info('Successfully registered OneSignal ID');
                        },
                        error: function(xhr, status, error) {
                            console.error('Error registering OneSignal ID:', error);
                        }
                    });
                } else {
                    console.warn('OneSignal ID is undefined or empty. Skipping registration.');
                }
            } catch (error) {
                console.error('Error initializing OneSignal:', error);
            }
        });
    </script>
</head>

@section('body')
    @include('layouts.body')
@show
<!-- Begin page -->
<div id="layout-wrapper">
    @include('layouts.topbar')
    @include('layouts.sidebar')
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

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
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        @include('layouts.footer')
    </div>
    <!-- end main content-->
</div>
<!-- END layout-wrapper -->

@include('layouts.customizer')

<!-- JAVASCRIPT -->
@include('layouts.vendor-scripts')


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>

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

    function checkLocation() {
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    updateUserLocation(latitude, longitude);
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
                        checkLocation(); // Cek ulang setelah klik OK
                    });
                },  {enableHighAccuracy:false,maximumAge:Infinity, timeout:60000}
            );
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Geolokasi Tidak Didukung',
                text: 'Perangkat Anda tidak mendukung pelacakan lokasi',
                confirmButtonText: 'OK'
            }).then(() => {
                checkLocation(); // Cek ulang setelah klik OK
            });
        }
    }

    document.addEventListener('DOMContentLoaded', checkLocation);
</script>

<script>
    // notifikasi
    // Fungsi untuk mengambil notifikasi dari backend
    function fetchNotifications() {
        $.ajax({
            url: '/notifikasi', // URL endpoint untuk mengambil notifikasi
            method: 'GET',
            success: function(response) {
                if (response.notifications.length > 0) {
                    updateNotifications(response.notifications);
                }
            },
            error: function(error) {
                console.error('Gagal mengambil notifikasi:', error);
            }
        });
    }

    // Fungsi untuk memperbarui tampilan notifikasi
    function updateNotifications(notifications) {
        let notificationList = $('#notificationItemsTabContent #all-noti-tab');
        notificationList.empty(); // Kosongkan isi notifikasi yang ada

        notifications.forEach(function(notification) {
            // Tentukan link dan ikon berdasarkan jenis notifikasi
            let notificationLink = '';
            let notificationIcon = '';

            if (notification.jenis === 'chat') {
                notificationLink =
                    `/manajemen-kasus/${notification.kasus_id}/chat?source=notif&id_notif=${notification.notif_id}`;
                notificationIcon = 'bx bx-chat'; // Ikon untuk chat
            } else if (notification.jenis === 'penugasan') {
                notificationLink =
                    `/manajemen-kasus/${notification.kasus_id}/show?source=notif&id_notif=${notification.notif_id}`;
                notificationIcon = 'bx bx-task'; // Ikon untuk penugasan
            } else if (notification.jenis === 'kasus_sekitar') {
                notificationLink =
                    `/manajemen-kasus/${notification.kasus_id}/show?source=notif&id_notif=${notification.notif_id}`;
                notificationIcon = 'bx bx-map-pin'; // ✅ Ikon lebih sesuai untuk kasus sekitar
            }


            let notificationItem = `
                <div class="text-reset notification-item d-block dropdown-item position-relative">
                    <div class="d-flex">
                        <div class="avatar-xs me-3 flex-shrink-0">
                            <span class="avatar-title bg-info-subtle text-info rounded-circle fs-16">
                                <i class="${notificationIcon}"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <a href="${notificationLink}" class="stretched-link">
                                <h6 class="mt-0 mb-2 lh-base">${notification.message}</h6>
                            </a>
                            <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                <span><i class="mdi mdi-clock-outline"></i> ${notification.timeAgo}</span>
                            </p>
                        </div>
                    </div>
                </div>
            `;
            notificationList.append(notificationItem);
        });

        $totalNotifikasi = notifications.length;
        // Update badge dengan jumlah notifikasi baru
        $('#notificationDropdown .topbar-badge').text($totalNotifikasi);
    }

    // Panggil fetchNotifications setiap 5 detik
    setInterval(fetchNotifications, 5000); // 5000ms = 5 detik

    // Panggil fungsi pertama kali saat halaman dimuat
    $(document).ready(function() {
        fetchNotifications();
    });
</script>


</body>

</html>
