<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\PushNotification;
use App\Models\BuktiKasu;
use App\Models\Kasu;
use App\Models\KategoriKasu;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LaporkanKasusController extends Controller
{
    public function index()
    {
        $listKasus = Kasu::where('pelapor_id', '=', auth()->user()->id)
            ->where('status', '=', 'MENUNGGU')
            ->orWhere('status', '=', 'DALAM_PROSES')
            ->get();

        if (count($listKasus) > 0) {
            return redirect(route('root.dashboardWarga'))->with('warning', 'Mohon maaf, saat ini Anda belum dapat membuat laporan baru. Silakan tunggu laporan sebelumnya diselesaikan terlebih dahulu');
        }

        $kategoriKasus = KategoriKasu::all();
        return view('app.laporkan-kasus.create', [
            'kategoriKasus' => $kategoriKasus,
        ]);
    }

    public function sos()
    {
        Kasu::create([
            'pelapor_id' => auth()->user()->id,
            'judul' => 'SOS',
            'jenis' => 'sos',
            'deskripsi' => 'SOS',
            'tingkat_keparahan' => 'BERAT',
            'latitude' => auth()->user()->latitude_terakhir,
            'longitude' => auth()->user()->longitude_terakhir,
            'status' => 'MENUNGGU',
            'waktu_kejadian' => now(),
        ]);

        session()->flash('success', 'SOS berhasil dikirim. Tunggu petugas menuju ketempat anda.');
        return redirect()->route('root');
    }

    public function store(Request $request)
    {
        $listKasus = Kasu::where('pelapor_id', '=', auth()->user()->id)
            ->where('status', '=', 'MENUNGGU')
            ->orWhere('status', '=', 'DALAM_PROSES')
            ->get();

        if (count($listKasus) > 0) {
            return redirect(route('root.dashboardWarga'))->with('warning', 'Mohon maaf, saat ini Anda belum dapat membuat laporan baru. Silakan tunggu laporan sebelumnya diselesaikan terlebih dahulu');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_kasus_id' => 'required|exists:kategori_kasus,id',
            'deskripsi' => 'nullable|string',
            'alamat' => 'nullable|string',
            'tingkat_keparahan' => 'required|in:RINGAN,SEDANG,BERAT,LAINNYA',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'bukti.*' => 'file|max:20480', // Maksimal 20MB per file
        ]);

        try {
            // Begin transaction
            DB::beginTransaction();

            $pelaporId = auth()->check() ? auth()->id() : null;

            // Create kasus record
            $kasus = Kasu::create([
                'pelapor_id' => $pelaporId,
                'kategori_kasus_id' => $request->kategori_kasus_id,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'alamat' => $request->alamat,
                'tingkat_keparahan' => $request->tingkat_keparahan,
                'status' => 'MENUNGGU',
                'waktu_kejadian' => now(),
            ]);

            // Handle multiple file uploads
            if ($request->hasFile('bukti')) {
                foreach ($request->file('bukti') as $file) {
                    // Generate unique filename
                    $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // Nama asli tanpa ekstensi
                    $timestamp = now()->format('YmdHis'); // Timestamp saat ini
                    $extension = $file->getClientOriginalExtension(); // Ekstensi file
                    $filename = $originalFileName . '-' . $timestamp . '.' . $extension;

                    // Define storage path
                    $path = 'bukti_kasus/' . $filename;

                    // Store file
                    Storage::disk('public')->put($path, file_get_contents($file));

                    // Get file MIME type and size
                    $mime = $file->getMimeType();
                    $size = round($file->getSize() / 1024, 2); // Convert size to KB

                    // Create bukti_kasus record
                    BuktiKasu::create([
                        'kasus_id' => $kasus->id,
                        'path' => $path,
                        'mime' => $mime,
                        'size' => $size, // in MB
                        'keterangan' => 'Bukti kejadian'
                    ]);
                }
            }

            // kirim notifikasi ke admin
            $push = [];
            $listAdmin = User::where('peran', '=', 'ADMIN')->get();

            foreach ($listAdmin as $adm) {
                // buat notifikasi
                Notifikasi::create([
                    'kasus_id' => $kasus->id,
                    'user_id' => $adm->id,
                    'push_notifikasi_terkirim' => true,
                    'pesan' => auth()->user()->name . ' baru saja melaporkan kasus, cek sekarang',
                    'jenis' => 'kasus_sekitar',
                    'read' => false,
                ]);

                if ($adm->onesignal_id) {
                    $push[] = $adm->onesignal_id;
                }
            }

            if (count($push) > 0) {
                $title = "Kasus Baru";
                $body = auth()->user()->name . ' baru saja melaporkan kasus, cek sekarang';
                $url = "/manajemen-kasus/$kasus->id/show";

                PushNotification::SendOneSignalNotification($push, $title, $body, $url = $url);
            }

            DB::commit();

            return redirect(route('root.dashboardWarga'))->with('success', 'Laporan berhasil dikirim. Tunggu petugas untuk memproses laporan Anda.');
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error creating kasus: ' . $e->getMessage());

            session()->flash('error', 'Terjadi kesalahan saat mengirim laporan. Silakan coba lagi.');
            return redirect()
                ->back()
                ->withInput();
        }
    }
}
