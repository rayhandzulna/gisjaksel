<?php
include 'koneksi.php'; // Menghubungkan ke database
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - WebGIS Fasilitas Umum</title>
    <link rel="stylesheet" href="https://unpkg.com/font-awesome/css/font-awesome.min.css">
    <style>
        /* Gaya umum halaman */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #212529;
        }

        /* Header */
        h1 {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            margin: 0;
            font-size: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Container untuk konten */
        .container {
            width: 80%;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Tombol-tombol */
        a {
            display: inline-block;
            padding: 10px 20px;
            margin-bottom: 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #218838;
        }

        .button-back {
            background-color: #007bff;
        }

        .button-back:hover {
            background-color: #0056b3;
        }

        /* Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        /* Foto */
        img {
            border-radius: 5px;
        }

        /* Konfirmasi Hapus */
        .action-links {
            color: #007bff;
        }

        .action-links a {
            text-decoration: none;
            margin-right: 10px;
        }

        .action-links a:hover {
            color: #0056b3;
        }
    </style>
</head>

<body>

    <h1>Database Pusat Perbelanjaan Kota Administrasi Jakarta Selatan</h1>


    <div class="container">
        <!-- Tombol ke Halaman Tambah Fasilitas dan Kembali ke Halaman Utama -->
        <a href="tambah_fasilitas.php">Tambah Fasilitas Baru</a>
        <a href="index.php" class="button-back">Kembali ke Halaman Utama</a>

        <!-- Tabel Data Fasilitas -->
        <table>
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
                        <td class="action-links">
                            <a href="ubah_fasilitas.php?id=<?= $row['id']; ?>">Edit</a> |
                            <a href="hapus_fasilitas.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>

</html>