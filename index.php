<?php
// koneksi.php
$host = "localhost";
$user = "root"; 
$pass = "";     
$db = "perpustakaan"; // nama database

$conn = mysqli_connect($host, $user, $pass, $db);

if (mysqli_connect_errno()) {
    echo "Gagal terhubung ke MySQL: " . mysqli_connect_error();
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Data Buku</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <div class="container"> <h1>Daftar Data Buku</h1>

        <?php
        // Pesan sukses/error dari operasi sebelumnya (tambah/edit/hapus)
        $pesan = '';
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'tambah_sukses') {
                $pesan = '<div class="pesan sukses">Data buku berhasil ditambahkan!</div>';
            } elseif ($_GET['status'] == 'edit_sukses') {
                $pesan = '<div class="pesan sukses">Data buku berhasil diperbarui!</div>';
            } elseif ($_GET['status'] == 'hapus_sukses') {
                $pesan = '<div class="pesan sukses">Data buku berhasil dihapus!</div>';
            } elseif ($_GET['status'] == 'gagal') {
                $detail_pesan = isset($_GET['pesan']) ? ' Detail: ' . htmlspecialchars(urldecode($_GET['pesan'])) : '';
                $pesan = '<div class="pesan error">Terjadi kesalahan. Silakan coba lagi.' . $detail_pesan . '</div>';
            }
        }
        echo $pesan; // Tampilkan pesan di dalam .container
        ?>

        <div class="action-buttons">
            <a href="tambah.php">Tambah Data Buku</a>
        </div>

        <?php
        // Mengambil semua data dari tabel 'data'
        $result = mysqli_query($conn, "SELECT * FROM data");

        if (mysqli_num_rows($result) > 0) {
        ?>
        <table>
            <thead>
                <tr>
                    <th>ISBN</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Penerbit</th>
                    <th>Tahun Terbit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td data-label='ISBN'>" . htmlspecialchars($row['ISBN']) . "</td>";
                    echo "<td data-label='Judul'>" . htmlspecialchars($row['Judul_Buku']) . "</td>";
                    echo "<td data-label='Penulis'>" . htmlspecialchars($row['Penulis']) . "</td>";
                    echo "<td data-label='Penerbit'>" . htmlspecialchars($row['Penerbit']) . "</td>";
                    echo "<td data-label='Tahun Terbit'>" . htmlspecialchars($row['Tahun_Terbit']) . "</td>";
                    echo "<td data-label='Aksi' class='table-actions'>";
                    echo " <a href='edit.php?id=" . urlencode($row['ISBN']) . "' class='edit-btn'>Edit</a> |";
                    echo " <a href='hapus.php?id=" . urlencode($row['ISBN']) . "' class='delete-btn' onclick=\"return confirm('Anda yakin ingin menghapus data ini?');\">Hapus</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <?php
        } else {
            echo "<p style='text-align: center; margin-top: 30px; font-style: italic;'>Belum ada data buku.</p>";
        }
        ?>
    </div> </body>
</html>

<?php
// Tutup koneksi
mysqli_close($conn);
?>