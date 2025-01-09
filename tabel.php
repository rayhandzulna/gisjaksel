<?php
include 'koneksi.php'; // Menghubungkan ke database
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - WebGIS Fasilitas Umum</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <h1>Halaman Admin - WebGIS Fasilitas Umum</h1>

    <a href="tambah_fasilitas.php" style="margin-bottom: 20px; display: inline-block;">Tambah Fasilitas Baru</a>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Alamat</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM fasilitas_umum";
            $result = mysqli_query($conn, $query);
            $no = 1;

            while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['kategori']; ?></td>
                    <td><?= $row['alamat']; ?></td>
                    <td><?= $row['latitude']; ?></td>
                    <td><?= $row['longitude']; ?></td>
                    <td>
                        <?php if ($row['foto']): ?>
                            <img src="img/<?= $row['foto']; ?>" width="100" alt="Foto Fasilitas">
                        <?php else: ?>
                            Tidak ada foto
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="ubah_fasilitas.php?id=<?= $row['id']; ?>">Edit</a> |
                        <a href="hapus_fasilitas.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>