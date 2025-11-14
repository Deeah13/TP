# Terminal Pintar Access Control Overview

Dokumen ini merangkum hak akses utama berdasarkan peran pada sistem Terminal Pintar sesuai klarifikasi terbaru.

## Peran dan Hak Akses

| Fitur / Aksi                                                                 | Publik | Admin | Guru/Relawan | Orang Tua |
|------------------------------------------------------------------------------|:------:|:-----:|:------------:|:---------:|
| Melihat profil, dokumentasi kegiatan, dan panduan partisipasi               |   ✅   |  ✅*  |      ✅*      |    ✅*    |
| Mengelola data siswa (tambah/ubah)                                          |        |   ✅  |              |           |
| Mengelola sesi pembelajaran lintas guru (penjadwalan, materi, kehadiran)    |        |       |              |           |
| Mengelola sesi pembelajaran yang diampu (materi & kehadiran)                |        |       |      ✅       |           |
| Melihat kehadiran & laporan perkembangan anak sendiri                       |        |       |              |    ✅     |
| Mengelola sirkulasi & koleksi perpustakaan                                  |        |   ✅  |              |           |
| Memperbarui konten Wajah Publik                                             |        |   ✅  |              |           |
| Mencatat transaksi peminjaman/pengembalian perpustakaan                     |        |   ✅  |              |           |

\*Admin, guru/relawan, dan orang tua dapat mengakses informasi publik melalui antarmuka umum, namun tidak memiliki hak tambahan di sana.

## Catatan Kontrol Akses

- Admin fokus pada pengelolaan data master (siswa, konten publik, perpustakaan) tanpa akses operasional ke sesi pembelajaran, materi, ataupun catatan guru.
- Guru/relawan hanya dapat melakukan aksi pada sesi yang mereka ampu.
- Orang tua mendapatkan akses baca saja terhadap data anak mereka.
- Middleware role-based perlu memastikan setiap rute memeriksa hak akses sesuai tabel di atas.
