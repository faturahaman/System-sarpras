<?php
// Memuat file konfigurasi koneksi database dan autoload file dari vendor (PhpSpreadsheet)
require '../config/dbConn.php';
require '../vendor/autoload.php';  // Sesuaikan path ke vendor

// Mengimpor kelas yang diperlukan dari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;  // Untuk membuat spreadsheet baru
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;  // Untuk menulis dan menyimpan file XLSX

// Membuat objek spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();  // Mengambil worksheet aktif (lembar kerja)

// Menyusun header kolom untuk file Excel
$sheet->setCellValue('A1', 'No');  // Kolom A1 untuk "No"
$sheet->setCellValue('B1', 'Nama Peminjam');  // Kolom B1 untuk "Nama Peminjam"
$sheet->setCellValue('C1', 'Kelas');  // Kolom C1 untuk "Kelas"
$sheet->setCellValue('D1', 'Nama Barang');  // Kolom D1 untuk "Nama Barang"
$sheet->setCellValue('E1', 'Waktu Peminjaman');  // Kolom E1 untuk "Waktu Peminjaman"
$sheet->setCellValue('F1', 'Status');  // Kolom F1 untuk "Status"

// Query untuk mengambil semua data peminjaman dari database
$query = "SELECT * FROM tb_peminjaman";
$result = mysqli_query($conn, $query);  // Menjalankan query untuk mengambil data
$rowNumber = 2;  // Baris pertama untuk data dimulai dari baris ke-2 (karena baris pertama untuk header)
$no = 1;  // Menyusun nomor urut

// Mengisi data ke dalam file Excel mulai dari baris kedua
while ($row = mysqli_fetch_assoc($result)) {
    // Mengisi data ke dalam sel sesuai dengan urutan kolom
    $sheet->setCellValue('A' . $rowNumber, $no++);  // Menulis nomor urut
    $sheet->setCellValue('B' . $rowNumber, $row['namaPeminjam']);  // Nama Peminjam
    $sheet->setCellValue('C' . $rowNumber, $row['kelas']);  // Kelas
    $sheet->setCellValue('D' . $rowNumber, $row['namaBarang']);  // Nama Barang
    $sheet->setCellValue('E' . $rowNumber, $row['WaktuPeminjaman']);  // Waktu Peminjaman
    $sheet->setCellValue('F' . $rowNumber, $row['status']);  // Status Peminjaman
    $rowNumber++;  // Melanjutkan ke baris berikutnya
}

// Mengatur header HTTP untuk mendownload file Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Laporan_Peminjaman.xlsx"');  // Menentukan nama file
header('Cache-Control: max-age=0');  // Mengatur cache

// Membuat objek writer untuk menulis data ke format XLSX dan langsung mengirimnya ke output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');  // Menyimpan file langsung ke output untuk diunduh
exit;  // Menghentikan eksekusi script setelah file dikirim
?>
