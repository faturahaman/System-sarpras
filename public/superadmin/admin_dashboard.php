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

// Query untuk menghitung total jenis barang
$totalBarangQuery = "SELECT COUNT(*) AS total FROM tb_barang";
$totalBarangResult = $conn->query($totalBarangQuery);
$totalBarang = $totalBarangResult->fetch_assoc()['total'] ?? 0;

// Query untuk menghitung total barang yang sedang dipinjam
$barangDipinjamQuery = "SELECT SUM(status) AS total FROM tb_peminjaman WHERE status = 'Dipinjam'";
$barangDipinjamResult = $conn->query($barangDipinjamQuery);
$barangDipinjam = $barangDipinjamResult->fetch_assoc()['total'] ?? 0;

// Query untuk menghitung total stok barang yang tersedia di sistem
$jumlahBarangQuery = "SELECT SUM(stokBarang) AS jumlahBrg FROM tb_barang ";
$jumlahBarangresult = $conn->query($jumlahBarangQuery);
$jumlahBarang = $jumlahBarangresult->fetch_assoc()['jumlahBrg'] ?? 0;

// Menghitung jumlah barang yang tersedia dengan mengurangkan barang yang sedang dipinjam
$barangTersedia = $jumlahBarang - $barangDipinjam;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Dashboard Peminjaman Sarpras</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .sidebar {
            height: 100vh;
        }
    </style>
</head>
<body class="bg-white">
    <div class="flex flex-col md:flex-row min-h-screen bg-gradient-to-r from-purple-400">
        <!-- Sidebar untuk navigasi -->
        <?php include '../../asset/sidebar.php' ?>
        
        <!-- Konten utama dashboard -->
        <div class="flex-1 p-6">
            <h1 class="text-2xl font-semibold text-[#2E285B]">Dashboard Peminjaman Sarpras</h1>
            <p class="mt-4 text-[#2E285B]">Kelola data barang, peminjaman, dan pengaturan sarpras di sini.</p>
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Kartu Total Barang -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-boxes text-[#2E285B] text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-xl font-semibold text-[#2E285B]">Total Barang</h2>
                            <p class="mt-2 text-[#2E285B] text-3xl font-bold"><?= $totalBarang; ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Kartu Barang Dipinjam -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clipboard-check text-green-500 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-xl font-semibold text-[#2E285B]">Barang Dipinjam</h2>
                            <p class="mt-2 text-[#2E285B] text-3xl font-bold"><?= $barangDipinjam; ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Kartu Barang Tersedia -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-box-open text-blue-500 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-xl font-semibold text-[#2E285B]">Barang Tersedia</h2>
                            <p class="mt-2 text-[#2E285B] text-3xl font-bold"><span class="text-gray-400"><?= $jumlahBarang ?></span>/<?= $barangTersedia; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
