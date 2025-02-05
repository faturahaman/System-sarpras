# Dokumentasi Fitur - Dashboard Peminjaman Sarpras

## 1. **admin_dashboard.php**
File ini adalah halaman utama untuk admin dalam mengelola peminjaman sarana dan prasarana.

### **Fitur Utama:**
1. **Autentikasi Admin**
   - Menggunakan `session_start()` untuk memastikan hanya admin yang telah login yang dapat mengakses halaman ini.
   - Jika admin belum login, akan diarahkan ke `login.php`.

2. **Menampilkan Statistik Barang**
   - Menghitung total jenis barang (`tb_barang`).
   - Menghitung jumlah barang yang sedang dipinjam (`tb_peminjaman`).
   - Menghitung total stok barang yang tersedia (`tb_barang`).
   - Menampilkan barang yang tersedia setelah dikurangi dengan barang yang dipinjam.

3. **Tampilan Dashboard**
   - Menggunakan **Tailwind CSS** untuk tampilan modern dan responsif.
   - Sidebar untuk navigasi yang dimuat dari file eksternal (`sidebar.php`).
   - Menampilkan 3 informasi utama dalam bentuk kartu:
     - **Total Barang** → jumlah total jenis barang.
     - **Barang Dipinjam** → jumlah barang yang sedang dalam status "Dipinjam".
     - **Barang Tersedia** → total stok barang dikurangi barang yang sedang dipinjam.

---

## 2. **databarang.php**
File ini digunakan untuk mengelola data barang di dalam sistem.

### **Fitur Utama:**
1. **Autentikasi Admin**
   - Sama seperti `admin_dashboard.php`, admin harus login terlebih dahulu untuk mengakses halaman ini.

2. **Menampilkan Daftar Barang**
   - Mengambil data dari tabel `tb_barang`.
   - Jika tidak ada barang, akan menampilkan pesan "Tidak ada data barang ditemukan.".
   - Menampilkan setiap barang dengan:
     - Nama Barang
     - Stok Barang
     - Tombol Edit
     - Tombol Delete dengan konfirmasi menggunakan **SweetAlert**

3. **Menambah Barang**
   - Form input untuk menambah barang baru ke database (`tb_barang`).
   - Data yang dikirim melalui metode `POST`.
   - Form ini berada di dalam modal yang dapat dibuka dan ditutup menggunakan JavaScript.

4. **Menghapus Barang**
   - Saat tombol "Delete" ditekan, muncul konfirmasi menggunakan **SweetAlert**.
   - Jika admin mengonfirmasi, akan diarahkan ke `delete_barang.php?id=<id_barang>` untuk menghapus data barang tersebut.

5. **Tombol Refresh Page**
   - Mempermudah admin untuk memuat ulang halaman tanpa harus menekan tombol browser.

---

## 3. **Teknologi yang Digunakan**
- **PHP** → Backend untuk mengelola data dan sesi admin.
- **MySQL** → Database untuk menyimpan data barang dan peminjaman.
- **Tailwind CSS** → Styling tampilan yang modern dan responsif.
- **SweetAlert2** → Notifikasi konfirmasi untuk penghapusan data.
- **FontAwesome** → Ikon pada dashboard.

### **Catatan Perbaikan:**
- **Bug pada Barang Dipinjam**: Saat menghitung barang yang dipinjam, query menggunakan `SUM(status)`, padahal `status` adalah string ("Dipinjam"). Sebaiknya ubah query menjadi `COUNT(*) WHERE status = 'Dipinjam'`.
- **Keamanan**: Disarankan menggunakan `mysqli_real_escape_string()` atau prepared statement saat menangani input user agar terhindar dari SQL Injection.
