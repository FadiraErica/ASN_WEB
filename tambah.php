<?php
// koneksi.php
$koneksi = mysqli_connect("localhost", "root", "", "perpustakaan"); // Sesuaikan nama database jika perlu

if (mysqli_connect_errno()) {
    echo "Gagal terhubung ke MySQL: " . mysqli_connect_error();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil semua data dari form, termasuk ISBN
    $isbn = mysqli_real_escape_string($koneksi, $_POST['isbn']);
    $judul_buku = mysqli_real_escape_string($koneksi, $_POST['judul_buku']);
    $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $tahun_terbit = mysqli_real_escape_string($koneksi, $_POST['tahun_terbit']);

    // Query INSERT: Menentukan kolom yang akan diisi secara eksplisit.
    // Kolom 'ID' tidak disertakan karena AUTO_INCREMENT.
    // Kolom 'ISBN' disertakan karena diisi manual.
    $query = "INSERT INTO data (ISBN, Judul_Buku, Penulis, Penerbit, Tahun_Terbit) VALUES ('$isbn', '$judul_buku', '$penulis', '$penerbit', '$tahun_terbit')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?status=tambah_sukses");
        exit();
    } else {
        // Tambahkan detail error untuk debugging
        header("Location: index.php?status=gagal&pesan=" . urlencode(mysqli_error($koneksi)));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Buku</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <div class="form-container">
        <h2>Tambah Data Buku Baru</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="isbn">ISBN:</label>
                <input type="text" id="isbn" name="isbn" required maxlength="13">
            </div>

            <div class="form-group">
                <label for="judul_buku">Judul Buku:</label>
                <input type="text" id="judul_buku" name="judul_buku" required>
            </div>

            <div class="form-group">
                <label for="penulis">Penulis:</label>
                <input type="text" id="penulis" name="penulis" required>
            </div>

            <div class="form-group">
                <label for="penerbit">Penerbit:</label>
                <input type="text" id="penerbit" name="penerbit" required>
            </div>

            <div class="form-group">
                <label for="tahun_terbit">Tahun Terbit:</label>
                <input type="number" id="tahun_terbit" name="tahun_terbit" min="1000" max="<?php echo date('Y'); ?>" required>
            </div>

            <div class="button-group">
                <button type="submit" class="btn-submit">Simpan Data</button>
                <a href="index.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>

<?php
mysqli_close($koneksi);
?>