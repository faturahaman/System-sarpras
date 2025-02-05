<?php
session_start();  // Mulai sesi untuk mengelola session pengguna
require '../../config/dbConn.php';  // Memuat konfigurasi koneksi database

// Proses login ketika tombol "login" ditekan
if (isset($_POST['login'])) {
    // Mengambil data dari form dan memastikan data aman untuk digunakan di query SQL
    $namaAdmin = mysqli_real_escape_string($conn, $_POST['namaAdmin']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);  // Enkripsi password menggunakan md5

    // Query untuk mengecek apakah pengguna dengan kombinasi nama, email, dan password ada di database
    $query = "SELECT * FROM tb_admin WHERE namaAdmin = '$namaAdmin' AND email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);  // Eksekusi query

    if (mysqli_num_rows($result) > 0) {
        // Jika data ditemukan, ambil data pengguna
        $row = mysqli_fetch_assoc($result);

        // Login berhasil, simpan data pengguna ke dalam session
        $_SESSION['loginadmin'] = true;
        $_SESSION['namaAdmin'] = $row['namaAdmin'];
        $_SESSION['email'] = $row['email'];

        // Tampilkan SweetAlert dengan pesan sukses dan redirect ke dashboard admin
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
                    title: 'Berhasil Login',
                    text: 'Selamat datang Admin!',
                    confirmButtonText: 'OK'
                  }).then(function() {
                    window.location.href = 'admin_dashboard.php';  // Redirect ke halaman dashboard admin
                  });
                </script>
              </body>
              </html>";
        exit();  // Keluar dari eksekusi lebih lanjut
    } else {
        // Jika login gagal, tampilkan pesan error menggunakan SweetAlert dan redirect kembali ke halaman login
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
                    title: 'Gagal Login',
                    text: 'Username, Email atau Password salah',
                    confirmButtonText: 'OK'
                  }).then(function() {
                    window.location.href = 'login.php';  // Redirect ke halaman login
                  });
                </script>
              </body>
              </html>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login Admin</title>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-br from-purple-600 to-blue-500">
    <div class="p-8 bg-white rounded-2xl shadow-2xl w-96 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-600 opacity-20"></div>
        <h2 class="mb-6 text-3xl font-extrabold text-center text-gray-800 relative z-10">Login Staff Admin</h2>

        <!-- Form untuk login admin -->
        <form action="" method="POST" class="relative z-10">
            <div class="mb-6">
                <label for="namaAdmin" class="block mb-2 text-lg font-semibold text-gray-700">Nama Pegawai</label>
                <input type="text" id="namaAdmin" name="namaAdmin" required
                    class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="email" class="block mb-2 text-lg font-semibold text-gray-700">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="password" class="block mb-2 text-lg font-semibold text-gray-700">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" name="login"
                class="w-full py-3 text-lg font-bold text-white bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-md hover:opacity-90 transition-all">Login</button>
        </form>

        <!-- Link untuk mendaftar jika belum punya akun -->
        <div class="mt-6 text-center relative z-10">
            <p class="text-gray-600">Belum punya akun?</p>
            <a href="register.php"
                class="text-blue-600 font-semibold hover:underline">Daftar</a>
        </div>

        <!-- Menampilkan pesan error jika ada -->
        <?php if (isset($error_message)) : ?>
            <p class="mt-4 text-center text-red-500 font-medium relative z-10">
                <?php echo $error_message; ?>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
