<?php
// koneksi.php
$koneksi = mysqli_connect("localhost", "root", "", "perpustakaan"); // Sesuaikan nama database jika perlu

if (mysqli_connect_errno()) {
    echo "Gagal terhubung ke MySQL: " . mysqli_connect_error();
    exit();
}

$buku = null;
$isbn_from_get = '';

// Mengambil data buku berdasarkan ISBN dari URL (parameter 'id' dari index.php)
if (isset($_GET['id'])) {
    $isbn_from_get = mysqli_real_escape_string($koneksi, $_GET['id']);
    // Query SELECT menggunakan ISBN sebagai kunci
    $query_select = "SELECT * FROM data WHERE ISBN = '$isbn_from_get'";
    $result_select = mysqli_query($koneksi, $query_select);

    if (mysqli_num_rows($result_select) > 0) {
        $buku = mysqli_fetch_assoc($result_select);
    } else {
        // Redirect jika data tidak ditemukan
        header("Location: index.php?status=gagal&pesan=" . urlencode("Data buku tidak ditemukan untuk diedit."));
        exit();
    }
} else {
    // Redirect jika parameter ID (ISBN) tidak diberikan
    header("Location: index.php?status=gagal&pesan=" . urlencode("ISBN buku tidak diberikan untuk pengeditan."));
    exit();
}

// Proses update data jika form dikirim (metode POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ISBN lama, digunakan untuk identifikasi baris di database (dari hidden field)
    $isbn_lama = mysqli_real_escape_string($koneksi, $_POST['isbn_lama']);

    // Mengambil data baru dari form
    $isbn_baru = mysqli_real_escape_string($koneksi, $_POST['isbn']); // ISBN baru (jika diubah)
    $judul_buku = mysqli_real_escape_string($koneksi, $_POST['judul_buku']);
    $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $tahun_terbit = mysqli_real_escape_string($koneksi, $_POST['tahun_terbit']);

    // Query UPDATE: Mengupdate semua kolom, termasuk ISBN baru
    // WHERE menggunakan ISBN lama untuk menemukan data yang tepat
    $query_update = "UPDATE data SET
                    ISBN = '$isbn_baru',
                    Judul_Buku = '$judul_buku',
                    Penulis = '$penulis',
                    Penerbit = '$penerbit',
                    Tahun_Terbit = '$tahun_terbit'
                    WHERE ISBN = '$isbn_lama'";

    if (mysqli_query($koneksi, $query_update)) {
        header("Location: index.php?status=edit_sukses");
        exit();
    } else {
        header("Location: index.php?status=gagal&pesan=" . urlencode(mysqli_error($koneksi)));
        exit();
    }
}

// Jika setelah semua proses, data buku masih null (misalnya ada masalah di awal),
// ini adalah fallback, meskipun seharusnya sudah ter-redirect di atas.
if (!$buku) {
    echo "Terjadi kesalahan: Data buku tidak dapat dimuat.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container"> <div class="form-container">
            <h2>Edit Data Buku</h2>
            <form action="" method="POST">
                <input type="hidden" name="isbn_lama" value="<?php echo htmlspecialchars($buku['ISBN']); ?>">

                <div class="form-group">
                    <label for="isbn">ISBN:</label>
                    <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars($buku['ISBN']); ?>" required maxlength="13">
                </div>

                <div class="form-group">
                    <label for="judul_buku">Judul Buku:</label>
                    <input type="text" id="judul_buku" name="judul_buku" value="<?php echo htmlspecialchars($buku['Judul_Buku']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="penulis">Penulis:</label>
                    <input type="text" id="penulis" name="penulis" value="<?php echo htmlspecialchars($buku['Penulis']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="penerbit">Penerbit:</label>
                    <input type="text" id="penerbit" name="penerbit" value="<?php echo htmlspecialchars($buku['Penerbit']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="tahun_terbit">Tahun Terbit:</label>
                    <input type="number" id="tahun_terbit" name="tahun_terbit" value="<?php echo htmlspecialchars($buku['Tahun_Terbit']); ?>" min="1000" max="<?php echo date('Y'); ?>" required>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                    <a href="index.php" class="btn-cancel">Batal</a>
                </div>
            </form>
        </div>
    </div> </body>
</html>

<?php
mysqli_close($koneksi);
?>