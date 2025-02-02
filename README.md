## NOTIFIKASI

#### JENIS
chat, penugasan, kasus_sekitar, penanganan_selesai, penanganan_dibatalkan

#### ATURAN
- chat
jika sender nya admin -> receivernya : pelapor, anggota penanganan.
jika sender nya pelapor -> receivernya : admin, anggota penanganan.
jika sender nya salah satu anggota penanganan -> receivernya: pelapor, admin, anggota yang lain.

format = [Aktor] baru saja mengirimkan pesan: [Pesan dua kata saja]

- penugasan
jika admin membuat penugasan -> receivernya: polisi yang ditugaskan

format = Admin menugaskan anda untuk menangani kasus [Kasus]

- kasus_sekitar
di master layout ada update lokasi pengguna realtime
ketika di handle di controller, hitung distance dengan semua kasus yang status nya menunggu dan dalam proses
hitung dengan haversine boleh pakai dari sql boleh via kode
cek dulu ke tabel notifikasi, kalau sudah ada skip.
receiver nya dia sendiri

format = ada kasus xxx dalam jarak 5 meter dari lokasi anda

- penanganan_selesai dan penanganan_dibatalkan
berikan notif ke admin jika ada penanganan_selesai dan penanganan_dibatalkan

sammi menyelesaikan tugasnya di kasus xxx, silahkan konfirmasi pekerjaannya.
sammi membatalkan menangani kasusnya, cek disini.
