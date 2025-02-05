<?php
// Memanggil koneksi ke database dari file 'dbConn.php'
require '../../config/dbConn.php';  // Memanggil koneksi database

// Proses penyimpanan data jika form di-submit
if (isset($_POST['daftar-btn'])) {
    // Mengambil data yang dikirimkan melalui form
    $namaAdmin = $_POST['namaAdmin'];  // Nama admin yang dimasukkan
    $email = $_POST['email'];          // Email yang dimasukkan
    $password = md5($_POST['password']);  // Enkripsi password dengan md5 (disarankan menggunakan password_hash untuk keamanan yang lebih baik)

    // Query untuk menyimpan data admin baru ke dalam tabel 'tb_admin'
    $query = "INSERT INTO tb_admin (namaAdmin, email, password) VALUES ('$namaAdmin', '$email', '$password')";
    // Menjalankan query dan mengecek apakah berhasil
    if (mysqli_query($conn, $query)) {
        // Jika berhasil, menampilkan pesan sukses
        $success_message = "Pendaftaran berhasil!";
    } else {
        // Jika gagal, menampilkan pesan error
        $error_message = "Pendaftaran gagal: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Memasukkan Tailwind CSS dari CDN untuk styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Memasukkan SweetAlert2 dari CDN untuk konfirmasi pendaftaran -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Daftar Pegawai</title>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-r from-purple-500 to-blue-500">
    <!-- Kontainer untuk form pendaftaran -->
    <div class="p-8 rounded-2xl shadow-2xl bg-white w-96">
        <h2 class="text-violet-900 text-3xl font-bold mb-6 text-center">Daftar Staff Admin</h2>
        <hr class="border-violet-900 mb-6">
        
        <!-- Form Pendaftaran Admin -->
        <form id="form-daftar" action="" method="POST">
            <!-- Input untuk nama admin -->
            <div class="mb-4">
                <label class="block text-violet-900 font-semibold mb-2" for="namaPegawai">Nama Admin</label>
                <input class="w-full p-3 rounded-lg bg-sky-100 focus:ring-2 focus:ring-violet-700" type="text" id="namaPegawai" name="namaAdmin" required>
            </div>
            <!-- Input untuk email admin -->
            <div class="mb-4">
                <label class="block text-violet-900 font-semibold mb-2" for="noPegawai">Email</label>
                <input class="w-full p-3 rounded-lg bg-sky-100 focus:ring-2 focus:ring-violet-700" type="text" id="noPegawai" name="email" required>
            </div>
            <!-- Input untuk password admin -->
            <div class="mb-4">
                <label class="block text-violet-900 font-semibold mb-2" for="password">Password</label>
                <input class="w-full p-3 rounded-lg bg-sky-100 focus:ring-2 focus:ring-violet-700" type="password" id="password" name="password" required>
            </div>
            <!-- Tombol pendaftaran dan tombol kembali -->
            <div class="flex flex-col space-y-3">
                <!-- Tombol untuk submit form -->
                <button class="w-full bg-violet-900 text-white p-3 rounded-lg text-lg font-semibold hover:bg-violet-700 transition-all duration-300" type="button" id="daftar-btn">Daftar</button>
                <!-- Tombol untuk kembali ke halaman login -->
                <a href="login.php" class="w-full">
                    <button class="w-full bg-yellow-300 text-black p-3 rounded-lg text-lg font-semibold hover:bg-yellow-400 transition-all duration-300" type="button">Kembali</button>
                </a>
            </div>
        </form>
        
        <!-- Menampilkan Pesan Sukses atau Error -->
        <?php if (isset($success_message)) : ?>
            <p class="text-green-600 font-semibold mt-4 text-center"><?php echo $success_message; ?></p>
        <?php elseif (isset($error_message)) : ?>
            <p class="text-red-600 font-semibold mt-4 text-center"><?php echo $error_message; ?></p>
        <?php endif; ?>
    </div>

    <script>
        // Menambahkan event listener ke tombol daftar
        document.getElementById('daftar-btn').addEventListener('click', function() {
            // Menampilkan popup konfirmasi dengan SweetAlert
            Swal.fire({
                title: 'Apakah Anda Yakin ingin mendaftar?',
                text: 'Anda akan mendaftar sebagai Admin',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Saya Yakin!',
                cancelButtonText: 'Tidak, Batalkan'
            }).then((result) => {
                // Jika pengguna mengonfirmasi, form akan disubmit
                if (result.value) {
                    document.getElementById('form-daftar').submit();
                }
            })
        });
    </script>
</body>
</html>
