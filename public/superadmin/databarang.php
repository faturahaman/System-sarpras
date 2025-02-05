<?php
session_start();
require '../../config/dbConn.php'; // Menghubungkan ke database

// Cek apakah koneksi database berhasil
if ($conn === null) {
  die("Koneksi database gagal.");
}

// Cek apakah admin sudah login, jika tidak, redirect ke halaman login
if(isset($_SESSION['loginadmin']) !== true){
    header("Location:login.php");
    exit();
}

$messageAlert = "";

// Query untuk mengambil data dari tb_barang
$query = "SELECT * FROM tb_barang";
$result = $conn->query($query);

// Cek jika ada data dalam tabel barang
if ($result->num_rows == 0) {
    $messageAlert = "Tidak ada data barang ditemukan.";
}

// Proses penambahan barang jika tombol tambah ditekan
if(isset($_POST['btntambah'])){
    $nama = $_POST['namaBarang'];
    $stok = $_POST['stokBarang'];

    // Query untuk menambahkan data ke dalam tabel tb_barang
    $query = "INSERT INTO tb_barang (namaBarang, stokBarang) VALUES ('$nama', '$stok')";
    $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .sidebar {
            height: 100vh;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex flex-col md:flex-row bg-gradient-to-r from-purple-400">
        <?php include '../../asset/sidebar.php' ?>

        <!-- Main content -->
        <div class="flex-1 p-6">
            <h2 class="text-2xl font-semibold text-[#2E285B] mb-6">Daftar Barang</h2>

            <?php if ($messageAlert): ?>
                <!-- Tampilkan pesan jika tidak ada data barang -->
                <div class="bg-red-500 text-white p-4 mb-4 rounded-md">
                    <p><?php echo $messageAlert; ?></p>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php
                // Looping untuk menampilkan setiap barang dalam daftar
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "
                        <div class='bg-white rounded-lg shadow-md p-4 transition-all duration-300 hover:shadow-xl'>
                            <h3 class='text-lg font-semibold text-[#2E285B]'>" . htmlspecialchars($row['namaBarang']) . "</h3>
                            <p class='text-gray-600'>Stok: " . htmlspecialchars($row['stokBarang']) . "</p>
                            
                            <!-- Tombol Edit -->
                            <a href='edit.php?id=" . $row["id_barang"] . "'>
                                <div class='mt-4 inline-block px-4 py-2 bg-blue-500 text-white text-sm rounded-md hover:bg-blue-600'>
                                    Edit
                                </div>
                            </a>
                            
                            <!-- Tombol Delete -->
                            <button onclick='confirmDelete(" . $row['id_barang'] . ")' class='mt-4 inline-block px-4 py-2 bg-red-500 text-white text-sm rounded-md hover:bg-red-600'>
                                Delete
                            </button>
                        </div>
                        ";
                    }
                }
                ?>
            </div>

            <!-- Button Tambah Barang -->
            <button onclick="openTambahModal()" class="bg-[#2E285B] w-fit p-3 absolute right-20 bottom-20 text-white rounded-lg">
                Tambah barang
            </button>

            <!-- Modal Tambah Barang -->
            <div id="tambahModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                    <h2 class="text-xl font-semibold text-[#2E285B] mb-4">Tambah Barang</h2>
                    <form action="" method="POST">
                        <div class="mb-4">
                            <label class="block text-gray-700">Nama Barang</label>
                            <input type="text" name="namaBarang" class="w-full p-2 border rounded-lg" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Stok Barang</label>
                            <input type="number" name="stokBarang" class="w-full p-2 border rounded-lg" required>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" onclick="closeTambahModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Batal</button>
                            <button type="submit" class="bg-[#2E285B] text-white px-4 py-2 rounded-lg" name="btntambah">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <button onclick="window.location.reload();" class="bg-[#2E285B] w-fit p-3 absolute right-20 top-3 text-white rounded-lg">Refresh Page</button>
        </div>
    </div>

    <!-- SweetAlert Script untuk konfirmasi penghapusan -->
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data barang ini akan dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete_barang.php?id=' + id;
                }
            });
        }
    </script>
</body>
</html>