<?php
// Menghubungkan ke database
require '../../config/dbConn.php';

// Cek apakah koneksi ke database berhasil
if ($conn === null) {
  die("Koneksi database gagal.");
}

// Periksa apakah ada parameter 'id' di URL sebelum melakukan proses penghapusan
if (isset($_GET['id'])) {
    $id_barang = $_GET['id']; // Ambil ID barang dari URL

    // Persiapkan query untuk menghapus barang berdasarkan ID
    $query = "DELETE FROM tb_barang WHERE id_barang = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_barang); // Bind parameter ID barang sebagai integer
    
    // Eksekusi query dan tampilkan notifikasi dengan SweetAlert
    if ($stmt->execute()) {
        // Jika berhasil dihapus, tampilkan pesan sukses dan redirect ke halaman daftar barang
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
                    title: 'Barang Dihapus!',
                    text: 'Barang berhasil dihapus.',
                    confirmButtonText: 'OK'
                  }).then(function() {
                    window.location.href = 'databarang.php';  // Redirect ke halaman daftar barang
                  });
                </script>
              </body>
              </html>";
    } else {
        // Jika gagal menghapus, tampilkan pesan error
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
                    title: 'Terjadi Kesalahan',
                    text: 'Gagal menghapus barang.',
                    confirmButtonText: 'OK'
                  }).then(function() {
                    window.location.href = 'databarang.php';
                  });
                </script>
              </body>
              </html>";
    }

    // Tutup prepared statement
    $stmt->close();
} else {
    // Jika parameter 'id' tidak ditemukan, langsung redirect ke halaman daftar barang
    header("Location: databarang.php");
    exit;
}
?>
