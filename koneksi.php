<?php
$host = "localhost"; //ketika hosting, bagian ini bisa diisi dengan hostingnya.
$user = "root";
$password = "";
$dbname = "perpustakaan";

// Membuat koneksi
$conn = mysqli_connect($host, $user, $password, $dbname);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>


