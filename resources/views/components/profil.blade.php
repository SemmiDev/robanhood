<div class="profile-foreground position-relative mx-n4 mt-n4">
    <div class="profile-wid-bg">
        <img src="{{ URL::asset('build/images/profile-bg.jpg') }}" alt="" class="profile-wid-img" />
    </div>
</div>

<div class="pt-4 mb-4 mb-lg-3 pb-lg-4 profile-wrapper">
    <div class="row g-4">
        <div class="col-auto">
            <div class="avatar-lg">
                @php
                    $profilePhoto = \App\Http\Controllers\Helpers\ProfilePhoto::get($user->avatar, $user->name);
                @endphp
                <img src="{{ $profilePhoto }}" alt="user-img" class="img-thumbnail rounded-circle" />
            </div>
        </div>
        <!--end col-->
        <div class="col">
            <div class="p-2">
                <h3 class="text-white mb-1">{{ $user->name }}</h3>
                <p class="text-white text-opacity-75">{{ $user->peran }}</p>

                @if ($user->peran == 'POLISI' || $user->peran == 'ADMIN')
                    <div class="hstack text-white-50 gap-1">
                        <div class="badge bg-primary-subtle fs-12 text-primary">
                            {{ $user->profil_polisis->first()->pangkat_polisi->nama ?? '-' }}
                        </div>
                    </div>

                    <div class="hstack mt-2 text-white-50 gap-1">
                        <div class="badge bg-info-subtle fs-12 text-info">
                            {{ $user->profil_polisis->first()->unit_polisi->nama ?? '-' }}
                        </div>
                    </div>

                    <div class="badge bg-warning-subtle fs-12 mt-2 text-info">
                        Jabatan: {{ $user->profil_polisis->first()->jabatan ?? '-' }}
                    </div>
                @endif
            </div>
        </div>

        <!--end col-->
        @if ($user->peran == 'POLISI')
            <div class="col-12 col-lg-auto order-last order-lg-0">
                <div class="row text text-white-50 text-center">
                    <h1 class="text-white mb-1">
                        {{ $user->total_poin }}
                    </h1>
                    <p class="fs-16 mb-0">Poin</p>
                </div>
            </div>
        @endif
        <!--end col-->

    </div>
    <!--end row-->
</div>

<div class="row">
    <div class="col-lg-12">
        <div>
            <div class="d-flex profile-wrapper">
                <!-- Nav tabs -->
                <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                            <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span
                                class="d-none d-md-inline-block">Biodata</span>
                        </a>
                    </li>

                    @if ($user->peran == 'POLISI' || $user->peran == 'ADMIN')
                        <li class="nav-item">
                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#projects" role="tab">
                                <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span
                                    class="d-none d-md-inline-block">Riwayat Semua Penyelesaian Kasus</span>
                            </a>
                        </li>
                    @endif
                </ul>

                <div class="flex-shrink-0">
                    <a href="{{ route('profil.edit') }}" class="btn btn-success"><i
                            class="ri-edit-box-line align-bottom"></i> Edit Data Profil</a>
                </div>
            </div>
            <!-- Tab panes -->
            <div class="tab-content pt-4 text-muted">
                <div class="tab-pane active" id="overview-tab" role="tabpanel">
                    <div class="row">
                        <div class="col-xxl-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Biodata</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <th class="ps-0" scope="row">Nama Lengkap :</th>
                                                    <td class="text-muted">{{ $user->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">No Hp / Wa :</th>
                                                    <td class="text-muted">{{ $user->no_whatsapp ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">No Telepon :</th>
                                                    <td class="text-muted">{{ $user->no_telepon ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">E-mail :</th>
                                                    <td class="text-muted">{{ $user->email }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">Lokasi Terakhir :</th>
                                                    <td class="text-muted">
                                                        <i class="ri-map-pin-line"></i>
                                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $user->latitude_terakhir }},{{ $user->longitude_terakhir }}"
                                                            target="_blank">Lihat di Peta</a>
                                                    </td>
                                                </tr>
                                                @if ($user->peran == 'POLISI' || $user->peran == 'ADMIN')
                                                    <tr>
                                                        <th class="ps-0" scope="row">Tempat dan Tanggal Lahir</th>
                                                        <td class="text-muted">
                                                            {{ $user->profil_polisis->first()->tempat_lahir }}
                                                            @if ($user->profil_polisis->first()->tanggal_lahir)
                                                                {{ date('d-m-Y', strtotime($user->profil_polisis->first()->tanggal_lahir)) }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        @php
                                                            $jenisKelamin = \App\Http\Controllers\Helpers\JenisKelamin::get(
                                                                $user->profil_polisis->first()->jenis_kelamin,
                                                            );
                                                        @endphp
                                                        <th class="ps-0" scope="row">Jenis Kelamin</th>
                                                        <td class="text-muted">
                                                            {{ $jenisKelamin }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div>
                        <!--end col-->

                        @if ($user->peran == 'POLISI' || $user->peran == 'ADMIN')
                            <div class="col-xxl-9">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Penyelesaian Kasus Baru yang Terlibat</h5>
                                        @forelse ($user->anggota_penanganans as $index => $anggotaPenanganan)
                                            @if ($index == 4)
                                              @break
                                            @endif

                                            <div
                                                class="card profile-project-card shadow-none profile-project-info mb-0 mt-3">
                                                <div class="card-body p-4">
                                                    <div class="d-flex">
                                                        <div class="flex-grow-1 text-muted overflow-hidden">
                                                            <h5 class="fs-14 text-truncate mb-1">
                                                                <a href="{{ route('manajemenKasus.show', ['id' => $anggotaPenanganan->kasu->id]) }}"
                                                                    class="text-body">
                                                                    {{ $anggotaPenanganan->kasu->judul }}
                                                                </a>
                                                            </h5>
                                                            <p class="text-muted text-truncate mb-0">
                                                                Waktu Kejadian: <span class="fw-semibold text-dark">
                                                                    {{ \Carbon\Carbon::parse($anggotaPenanganan->kasu->waktu_kejadian)->diffForHumans() }}
                                                                </span></p>
                                                        </div>
                                                        <div class="flex-shrink-0 ms-2">
                                                            <div class="badge bg-warning-subtle text-warning fs-10">
                                                                {{ $anggotaPenanganan->selesai == 0 ? 'Dalam Proses' : 'Selesai' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div>
                                                                    <h5 class="fs-12 text-muted mb-0">
                                                                        Tim :</h5>
                                                                </div>
                                                                <div class="avatar-group">
                                                                    @php
                                                                        $ketua = $anggotaPenanganan->kasu->anggota_penanganans->firstWhere(
                                                                            'peran',
                                                                            'KETUA',
                                                                        );
                                                                        $anggotaLain = $anggotaPenanganan->kasu->anggota_penanganans->where(
                                                                            'peran',
                                                                            'ANGGOTA',
                                                                        );
                                                                    @endphp

                                                                    @if ($ketua)
                                                                        <?php
                                                                        $profilePhoto = \App\Http\Controllers\Helpers\ProfilePhoto::get($ketua->user->avatar, $ketua->user->name);
                                                                        ?>
                                                                        <a href="javascript:void(0);"
                                                                            class="avatar-group-item"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="top"
                                                                            data-bs-trigger="hover"
                                                                            data-bs-original-title="{{ $ketua->user->name . ' (' . $ketua->peran . ')' }}">
                                                                            <img src="{{ $profilePhoto }}"
                                                                                alt=""
                                                                                class="rounded-circle avatar-xs" />
                                                                        </a>
                                                                    @endif

                                                                    @forelse ($anggotaLain as $anggota)
                                                                        <?php
                                                                        $profilePhoto = \App\Http\Controllers\Helpers\ProfilePhoto::get($anggota->user->avatar, $anggota->user->name);
                                                                        ?>
                                                                        <a href="javascript:void(0);"
                                                                            class="avatar-group-item"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="top"
                                                                            data-bs-trigger="hover"
                                                                            data-bs-original-title="{{ $anggota->user->name . ' (' . $anggota->peran . ')' }}">
                                                                            <img src="{{ $profilePhoto }}"
                                                                                alt=""
                                                                                class="rounded-circle avatar-xs" />
                                                                        </a>
                                                                    @empty
                                                                    @endforelse
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end card body -->
                                            </div>
                                        @empty
                                        <span class="text-muted">Belum ada data.</span>
                                        @endforelse
                                </div>
                            </div><!-- end card -->
                        </div>
                    @endif

                    <!--end col-->
                </div>
                <!--end row-->
            </div>
            <!--end tab-pane-->
            <div class="tab-pane fade" id="projects" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @forelse ($user->anggota_penanganans as $index => $anggotaPenanganan)
                                <div class="col-xxl-3 col-sm-6">
                                    <div class="card profile-project-card shadow-none profile-project-info">
                                        <div class="card-body p-4">
                                            <div class="d-flex">
                                                <div class="flex-grow-1 text-muted overflow-hidden">
                                                    <h5 class="fs-14 text-truncate"><a
                                                            href="{{ route('manajemenKasus.show', ['id' => $anggotaPenanganan->kasu->id]) }}"
                                                            class="text-body">
                                                            {{ $anggotaPenanganan->kasu->judul }}
                                                        </a>
                                                    </h5>
                                                    <p class="text-muted text-truncate mb-0">
                                                        Waktu Kejadian: <span class="fw-semibold text-dark">
                                                            {{ \Carbon\Carbon::parse($anggotaPenanganan->kasu->waktu_kejadian)->diffForHumans() }}
                                                        </span></p>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <div class="badge bg-warning-subtle text-warning fs-10">
                                                        {{ $anggotaPenanganan->selesai == 0 ? 'Dalam Proses' : 'Selesai' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex mt-4">
                                                <div class="flex-grow-1">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div>
                                                            <h5 class="fs-12 text-muted mb-0">
                                                                Tim :</h5>
                                                        </div>
                                                        <div class="avatar-group">
                                                            @php
                                                                $ketua = $anggotaPenanganan->kasu->anggota_penanganans->firstWhere(
                                                                    'peran',
                                                                    'KETUA',
                                                                );
                                                                $anggotaLain = $anggotaPenanganan->kasu->anggota_penanganans->where(
                                                                    'peran',
                                                                    'ANGGOTA',
                                                                );
                                                            @endphp

                                                            @if ($ketua)
                                                                <?php
                                                                $profilePhoto = \App\Http\Controllers\Helpers\ProfilePhoto::get($ketua->user->avatar, $ketua->user->name);
                                                                ?>
                                                                <a href="javascript:void(0);"
                                                                    class="avatar-group-item"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    data-bs-trigger="hover"
                                                                    data-bs-original-title="{{ $ketua->user->name . ' (' . $ketua->peran . ')' }}">
                                                                    <img src="{{ $profilePhoto }}" alt=""
                                                                        class="rounded-circle avatar-xs" />
                                                                </a>
                                                            @endif

                                                            @forelse ($anggotaLain as $anggota)
                                                                <?php
                                                                $profilePhoto = \App\Http\Controllers\Helpers\ProfilePhoto::get($anggota->user->avatar, $anggota->user->name);
                                                                ?>
                                                                <a href="javascript:void(0);"
                                                                    class="avatar-group-item"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    data-bs-trigger="hover"
                                                                    data-bs-original-title="{{ $anggota->user->name . ' (' . $anggota->peran . ')' }}">
                                                                    <img src="{{ $profilePhoto }}" alt=""
                                                                        class="rounded-circle avatar-xs" />
                                                                </a>
                                                            @empty
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end card body -->
                                    </div>

                                    <!-- end card -->
                                </div>
                            @empty
                            <span class="text-muted">Belum ada data.</span>
                            @endforelse

                        </div>
                        <!--end row-->
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
            </div>
            <!--end tab-pane-->
            <!--end tab-pane-->
        </div>
        <!--end tab-content-->
    </div>
</div>
<!--end col-->
</div>
