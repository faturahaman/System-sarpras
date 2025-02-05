<?php 
session_start();  // Mulai sesi untuk mengelola session pengguna
require '../../config/dbConn.php';  // Memuat konfigurasi koneksi database

// Pastikan 'id' tersedia di URL
if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan!";  // Menampilkan pesan error jika ID tidak ada di URL
    exit;
}

$id_diedit = $_GET['id'];  // Menyimpan nilai ID yang diterima dari URL

// Ambil data dari database berdasarkan id
$sql = "SELECT * FROM tb_barang WHERE id_barang = '$id_diedit'";  // Query untuk mengambil data barang berdasarkan ID
$query = mysqli_query($conn, $sql);  // Eksekusi query
$row = mysqli_fetch_assoc($query);  // Mengambil hasil query sebagai array asosiasi

// Cek apakah data ditemukan
if (!$row) {
    echo "Data tidak ditemukan!";  // Jika data tidak ditemukan, tampilkan pesan error
    exit;
}

// Proses update jika tombol "Simpan" ditekan
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Gaya tambahan jika diperlukan */
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-r from-purple-400 ">

    <div class="max-w-lg w-full bg-white shadow-lg rounded-xl p-8 border border-gray-200">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Edit Barang</h2>
        <!-- Form untuk mengedit data barang -->
        <form action="" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Nama Barang</label>
                <input type="text" name="namaBarang" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" value="<?= htmlspecialchars($row['namaBarang']) ?>" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Stok Barang</label>
                <input type="number" name="stokBarang" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" value="<?= htmlspecialchars($row['stokBarang']) ?>" required>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-200" name="btnedit">Simpan</button>
                <a href="databarang.php">
                    <div class="bg-purple-600 mx-2 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-200">Cancel</div>
                </a>
            </div>
        </form>
    </div>

</body>
</html>

<?php

// Proses ketika tombol "Simpan" ditekan
if(isset($_POST['btnedit'])){
    // Mengambil data dari form dan memastikan data aman untuk digunakan di query SQL
    $namaBarang = mysqli_real_escape_string($conn, $_POST['namaBarang']);
    $stokBarang = mysqli_real_escape_string($conn, $_POST['stokBarang']);

    // Query untuk memperbarui data barang di database
    $update_sql = "UPDATE tb_barang SET namaBarang='$namaBarang', stokBarang='$stokBarang' WHERE id_barang='$id_diedit'";
    $update_query = mysqli_query($conn, $update_sql);  // Eksekusi query update

    // Cek apakah query berhasil dieksekusi
    if ($update_query) {
        // Menampilkan SweetAlert jika update berhasil
        echo "<!DOCTYPE html>
              <html lang='en'>
              <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              </head>
              <body>
                <script>
                  Swal.fire({
                    icon: 'success',
                    title: 'Berhasil mengubah',
                    text: 'data barang berhasil di ubah',
                    confirmButtonText: 'OK'
                  }).then(function() {
                      // Redirect ke halaman daftar barang setelah berhasil
                      window.location.href = 'databarang.php'; 
                  });
                </script>
              </body>
              </html>";
    } else {
        // Menampilkan SweetAlert jika update gagal
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
          <meta charset='UTF-8'>
          <meta name='viewport' content='width=device-width, initial-scale=1.0'>
          <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
          <script>
            Swal.fire({
              icon: 'error',
              title: 'gagal Mengubah',
              text: 'data barang tidak berhasil di ubah',
              confirmButtonText: 'OK'
            }).then(function() {
                // Redirect ke halaman edit jika gagal
                window.location.href = 'edit.php'; 
            });
          </script>
        </body>
        </html>";
    }
}
