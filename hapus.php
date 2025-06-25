<?php
// koneksi.php
$koneksi = mysqli_connect("localhost", "root", "", "perpustakaan"); // Sesuaikan nama database jika perlu

if (mysqli_connect_errno()) {
    echo "Gagal terhubung ke MySQL: " . mysqli_connect_error();
    exit();
}

$isbn_to_delete = null;
$buku_info = null;

// Mengambil ISBN dari parameter 'id' di URL
if (isset($_GET['id'])) {
    $isbn_to_delete = mysqli_real_escape_string($koneksi, $_GET['id']);
    // Query SELECT untuk mendapatkan Judul_Buku berdasarkan ISBN
    $query_select = "SELECT Judul_Buku FROM data WHERE ISBN = '$isbn_to_delete'";
    $result_select = mysqli_query($koneksi, $query_select);

    if (mysqli_num_rows($result_select) > 0) {
        $buku_info = mysqli_fetch_assoc($result_select);
    } else {
        // Redirect jika data tidak ditemukan
        header("Location: index.php?status=gagal&pesan=" . urlencode("Data tidak ditemukan untuk dihapus."));
        exit();
    }
}

// Proses penghapusan jika form konfirmasi dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_delete'])) {
    $isbn_delete_confirmed = mysqli_real_escape_string($koneksi, $_POST['isbn_delete']);
    // Query DELETE berdasarkan ISBN
    $query_delete = "DELETE FROM data WHERE ISBN = '$isbn_delete_confirmed'";

    if (mysqli_query($koneksi, $query_delete)) {
        header("Location: index.php?status=hapus_sukses");
        exit();
    } else {
        header("Location: index.php?status=gagal&pesan=" . urlencode(mysqli_error($koneksi)));
        exit();
    }
}

// Redirect jika ISBN tidak diberikan saat pertama kali diakses
if (!$isbn_to_delete) {
    header("Location: index.php?status=gagal&pesan=" . urlencode("ISBN tidak diberikan untuk dihapus."));
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Data Buku</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <div class="container">
        <h2>Konfirmasi Hapus Data</h2>

        <div class="delete-confirmation">
            <p>Anda yakin ingin menghapus buku dengan judul "<strong><?php echo htmlspecialchars($buku_info['Judul_Buku']); ?></strong>" (ISBN: <?php echo htmlspecialchars($isbn_to_delete); ?>)?</p>

            <form action="" method="POST">
                <input type="hidden" name="isbn_delete" value="<?php echo htmlspecialchars($isbn_to_delete); ?>">
                <div class="button-group">
                    <button type="submit" name="confirm_delete" class="btn-submit" style="background-color: #dc3545;">Ya, Hapus</button>
                    <a href="index.php" class="btn-cancel">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php
mysqli_close($koneksi);
?>