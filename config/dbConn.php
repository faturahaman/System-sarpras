<?php

$servername = 'localhost';
$dbname = 'sarprasDB';
$username = 'root';
$password = '';

// Membuat koneksi ke database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Memeriksa apakah koneksi berhasil
if ($conn == null) {
    echo "Connection ERROR: " . mysqli_connect_error();
} 
?>
