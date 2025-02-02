<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\OneSignalController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes(['verify' => true]); // verifikasi email user

Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);
Route::get('/', function () {
    return redirect('/dashboard');
})->name('index');

Route::group(['middleware' => ['auth', 'verified']], function () {

    Route::get('/onesignal/register', [OneSignalController::class, 'register']);

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

    Route::post('/users/change-status', [App\Http\Controllers\UserController::class, 'changeStatus'])->name('user.changeStatus');
    Route::get('/user/koordinate/latest', [App\Http\Controllers\UserController::class, 'getLatestUserCoordinates'])->name('user.getLatestUserCoordinates');
    Route::post('/user/koordinate/latest', [App\Http\Controllers\UserController::class, 'updateLatestUserCoordinates'])->name('user.updateLatestUserCoordinates');

    Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

    Route::group(['middleware' => ['role:WARGA']], function () {
        Route::get('/dashboard-warga', [App\Http\Controllers\WargaDashboardController::class, 'index'])->name('root.dashboardWarga');
        Route::get('laporkan-kasus', [App\Http\Controllers\LaporkanKasusController::class, 'index'])->name('laporkanKasus');
        Route::post('laporkan-kasus', [App\Http\Controllers\LaporkanKasusController::class, 'store'])->name('laporkanKasus.store');
        Route::get('sos', [App\Http\Controllers\LaporkanKasusController::class, 'sos'])->name('sos');
        Route::get('/realtime/sekitar', [App\Http\Controllers\RealtimeManajemenKasusController::class, 'realtimeSekitar'])->name('realtimeSekitar');
        Route::get('/realtime/sekitar/info', [App\Http\Controllers\RealtimeManajemenKasusController::class, 'realtimeSekitarInfo'])->name('realtimeSekitar.info');
    });

    Route::group(['middleware' => ['role:POLISI']], function () {
        Route::get('/dashboard-polisi', [App\Http\Controllers\PolisiDashboardController::class, 'index'])->name('root.dashboardPolisi');
    });

    Route::group(['middleware' => ['role:ADMIN']], function () {
        Route::get('/dashboard-admin', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('root.dashboardAdmin');

        Route::post('/update-status', [AdminDashboardController::class, 'updateStatusPolisi'])->name('update.status');
        Route::post('/update-aktif-anggota', [AdminDashboardController::class, 'updateAktifAnggota'])->name('update.aktifAnggota');

        Route::get('/manajemen-pangkat', [App\Http\Controllers\ManajemenPangkatController::class, 'index'])->name('manajemenPangkat');
        Route::get('/manajemen-pangkat/create', [App\Http\Controllers\ManajemenPangkatController::class, 'create'])->name('manajemenPangkat.create');
        Route::get('/manajemen-pangkat/{id}/show', [App\Http\Controllers\ManajemenPangkatController::class, 'edit'])->name('manajemenPangkat.show');
        Route::post('/manajemen-pangkat', [App\Http\Controllers\ManajemenPangkatController::class, 'store'])->name('manajemenPangkat.store');
        Route::put('/manajemen-pangkat/{id}', [App\Http\Controllers\ManajemenPangkatController::class, 'update'])->name('manajemenPangkat.update');
        Route::delete('/manajemen-pangkat/{id}', [App\Http\Controllers\ManajemenPangkatController::class, 'destroy'])->name('manajemenPangkat.destroy');

        Route::get('/manajemen-unit', [App\Http\Controllers\ManajemenUnitController::class, 'index'])->name('manajemenUnit');
        Route::get('/manajemen-unit/create', [App\Http\Controllers\ManajemenUnitController::class, 'create'])->name('manajemenUnit.create');
        Route::get('/manajemen-unit/{id}/show', [App\Http\Controllers\ManajemenUnitController::class, 'edit'])->name('manajemenUnit.show');
        Route::post('/manajemen-unit', [App\Http\Controllers\ManajemenUnitController::class, 'store'])->name('manajemenUnit.store');
        Route::put('/manajemen-unit/{id}', [App\Http\Controllers\ManajemenUnitController::class, 'update'])->name('manajemenUnit.update');
        Route::delete('/manajemen-unit/{id}', [App\Http\Controllers\ManajemenUnitController::class, 'destroy'])->name('manajemenUnit.destroy');

        Route::get('/manajemen-pengaturan-website', [App\Http\Controllers\ManajemenPengaturanWebsiteController::class, 'index'])->name('manajemenPengaturanWebsite');
        Route::put('/manajemen-pengaturan-website', [App\Http\Controllers\ManajemenPengaturanWebsiteController::class, 'update'])->name('manajemenPengaturanWebsite.update');

        Route::get('/manajemen-anggota', [App\Http\Controllers\ManajemenAnggotaController::class, 'index'])->name('manajemenAnggota');
        Route::get('/manajemen-anggota/{id}/show', [App\Http\Controllers\ManajemenAnggotaController::class, 'show'])->name('manajemenAnggota.show');
        Route::get('/manajemen-anggota/{id}/edit', [App\Http\Controllers\ManajemenAnggotaController::class, 'edit'])->name('manajemenAnggota.edit');
        Route::delete('/manajemen-anggota/{id}/destroy', [App\Http\Controllers\ManajemenAnggotaController::class, 'destroy'])->name('manajemenAnggota.destroy');

        Route::get('/manajemen-kategori-kasus', [App\Http\Controllers\ManajemenKategoriKasusController::class, 'index'])->name('manajemenKategoriKasus');
        Route::get('/manajemen-kategori-kasus/create', [App\Http\Controllers\ManajemenKategoriKasusController::class, 'create'])->name('manajemenKategoriKasus.create');
        Route::get('/manajemen-kategori-kasus/{id}/show', [App\Http\Controllers\ManajemenKategoriKasusController::class, 'edit'])->name('manajemenKategoriKasus.show');
        Route::get('/manajemen-kategori-kasus/{id}/edit', [App\Http\Controllers\ManajemenKategoriKasusController::class, 'edit'])->name('manajemenKategoriKasus.edit');
        Route::post('/manajemen-kategori-kasus', [App\Http\Controllers\ManajemenKategoriKasusController::class, 'store'])->name('manajemenKategoriKasus.store');
        Route::put('/manajemen-kategori-kasus/{id}', [App\Http\Controllers\ManajemenKategoriKasusController::class, 'update'])->name('manajemenKategoriKasus.update');
        Route::delete('/manajemen-kategori-kasus/{id}', [App\Http\Controllers\ManajemenKategoriKasusController::class, 'destroy'])->name('manajemenKategoriKasus.destroy');

        Route::get('/leaderboard', [App\Http\Controllers\LeaderboardController::class, 'index'])->name('leaderboard');
    });

    Route::group(['middleware' => ['role:ADMIN,POLISI']], function () {
        Route::get('/manajemen-kasus', [App\Http\Controllers\ManajemenKasusController::class, 'index'])->name('manajemenKasus');
    });

    Route::get('/manajemen-kasus/{id}/show', [App\Http\Controllers\ManajemenKasusController::class, 'show'])->name('manajemenKasus.show');
    Route::delete('/manajemen-kasus/{id}/destroy', [App\Http\Controllers\ManajemenKasusController::class, 'destroy'])->name('manajemenKasus.destroy');
    Route::post('/manajemen-kasus/{id}/handle', [App\Http\Controllers\ManajemenKasusController::class, 'handle'])->name('manajemenKasus.handle');
    Route::post('/manajemen-kasus/{id}/unhandle', [App\Http\Controllers\ManajemenKasusController::class, 'unhandle'])->name('manajemenKasus.unhandle');
    Route::post('/manajemen-kasus/{id}/selesaikan', [App\Http\Controllers\ManajemenKasusController::class, 'selesaikan'])->name('manajemenKasus.selesaikan');
    Route::post('/manajemen-kasus/{id}/tutup', [App\Http\Controllers\ManajemenKasusController::class, 'tutup'])->name('manajemenKasus.tutup');
    Route::get('/manajemen-kasus/{id}/edit', [App\Http\Controllers\ManajemenKasusController::class, 'edit'])->name('manajemenKasus.edit');
    Route::put('/manajemen-kasus/{id}/update', [App\Http\Controllers\ManajemenKasusController::class, 'update'])->name('manajemenKasus.update');
    Route::post('/manajemen-kasus/{id}/assign', [App\Http\Controllers\ManajemenKasusController::class, 'assign'])->name('manajemenKasus.assign');
    Route::post('/manajemen-kasus/{id}/switch-status', [App\Http\Controllers\ManajemenKasusController::class, 'switchStatus'])->name('manajemenKasus.switchStatus');
    Route::get('/manajemen-kasus/{id}/rute', [App\Http\Controllers\ManajemenKasusController::class, 'rute'])->name('manajemenKasus.rute');
    Route::get('/manajemen-kasus/{id}/rute-sos', [App\Http\Controllers\ManajemenKasusController::class, 'ruteSos'])->name('manajemenKasus.ruteSos');
    Route::post('/manajemen-kasus/{id}/feedback', [App\Http\Controllers\ManajemenKasusController::class, 'feedback'])->name('manajemenKasus.feedback');
    Route::get('/manajemen-kasus/{id}/chat', [App\Http\Controllers\ManajemenKasusController::class, 'chat'])->name('manajemenKasus.chat');
    Route::post('/manajemen-kasus/{id}/send-chat', [App\Http\Controllers\ManajemenKasusController::class, 'sendChat'])->name('manajemenKasus.sendChat');
    Route::post('/manajemen-kasus/{id}/bukti-pekerjaan', [App\Http\Controllers\ManajemenKasusController::class, 'storeBuktiPekerjaan'])->name('manajemenKasus.storeBuktiPekerjaan');
    Route::post('/manajemen-kasus/{kasus_id}/verifikasi-bukti-pekerjaan/{user_id}', [App\Http\Controllers\ManajemenKasusController::class, 'verifikasiBuktiPekerjaan'])->name('manajemenKasus.verifikasiBuktiPekerjaan');
    Route::post('/manajemen-kasus/{kasus_id}/reset-bukti-pekerjaan/{user_id}', [App\Http\Controllers\ManajemenKasusController::class, 'resetBuktiPekerjaan'])->name('manajemenKasus.resetBuktiPekerjaan');
    Route::post('/manajemen-kasus/{kasus_id}/hapus-anggota/{user_id}', [App\Http\Controllers\ManajemenKasusController::class, 'hapusAnggota'])->name('manajemenKasus.hapusAnggota');

    Route::get('/realtime/manajemen-kasus', [App\Http\Controllers\RealtimeManajemenKasusController::class, 'index'])->name('realtimeManajemenKasus')->middleware('role:ADMIN');
    Route::get('/ajax/manajemen-kasus', [App\Http\Controllers\AjaxManajemenKasusController::class, 'index'])->name('ajaxManajemenKasus');
    Route::get('/realtime/peta-manajemen-kasus', [App\Http\Controllers\RealtimeManajemenKasusController::class, 'index'])->name('petaRealtimeManajemenKasus');

    Route::get('/profil', [App\Http\Controllers\ProfilController::class, 'index'])->name('profil');
    Route::get('/profil/edit', [App\Http\Controllers\ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil/update', [App\Http\Controllers\ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/updatePassword', [App\Http\Controllers\ProfilController::class, 'updatePassword'])->name('profil.updatePassword');
    Route::put('/profil/updateFotoProfil', [App\Http\Controllers\ProfilController::class, 'updateFotoProfil'])->name('profil.updateFotoProfil');

    // modul peta realtime (ADMIN)
    Route::get('/peta-kasus', [App\Http\Controllers\PetaController::class, 'petaKasus'])->name('petaKasus')->middleware('role:ADMIN');
    Route::get('/get-latest-coord-polisi', [App\Http\Controllers\PetaController::class, 'getLatestCoordPolisi'])->name('getLatestCoordPolisi')->middleware('role:ADMIN');
    Route::get('/get-latest-kasus', [App\Http\Controllers\PetaController::class, 'getLatestKasus'])->name('getLatestKasus')->middleware('role:ADMIN');

    // modul peta 1 kasus + semua polisi (ADMIN)
    Route::get('/peta-kasus-assign/{id}', [App\Http\Controllers\PetaController::class, 'petaKasusAssign'])->name('petaKasusAssign')->middleware('role:ADMIN');
    Route::get('/get-latest-kasus/{id}', [App\Http\Controllers\PetaController::class, 'getLatestKasusAssign'])->name('getLatestKasusAssign')->middleware('role:ADMIN');
    Route::get('/get-latest-coord-polisi-assign/{id}', [App\Http\Controllers\PetaController::class, 'getLatestCoordPolisiAssign'])->name('getLatestCoordPolisiAssign')->middleware('role:ADMIN');
    Route::get('/get-latest-coord-polisi-assign-polisi', [App\Http\Controllers\PetaController::class, 'getLatestCoordPolisiAssignPolisi'])->name('getLatestCoordPolisiAssignPolisi')->middleware('role:ADMIN');

    // scan peta polisi
    Route::get('/scan-kasus-terdekat', [App\Http\Controllers\PetaController::class, 'scanKasusTerdekat'])->name('scanKasusTerdekat')->middleware('role:POLISI');
    Route::get('/get-kasus-terdekat', [App\Http\Controllers\PetaController::class, 'getKasusTerdekat'])->name('getKasusTerdekat')->middleware('role:POLISI');

    // module notifikasi
    Route::get('/notifikasi', [App\Http\Controllers\NotifikasiController::class, 'index'])->name('notifikasi');
    Route::get('/histori-notifikasi', [App\Http\Controllers\NotifikasiController::class, 'historiNotifikasi'])->name('notifikasi.histori');
    Route::post('/notifikasi', [App\Http\Controllers\NotifikasiController::class, 'store'])->name('notifikasi.store');
    Route::get('/notifikasi/mark-as-read', [App\Http\Controllers\NotifikasiController::class, 'markAsRead'])->name('notifikasi.markAsRead');
});
