<?php
include 'koneksi.php';
$id = $_GET['id'];

$query = "SELECT * FROM fasilitas_umum WHERE id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $alamat = $_POST['alamat'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $foto = $data['foto'];

    if (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "img/" . $foto);
    }

    $query = "UPDATE fasilitas_umum 
              SET nama = '$nama', kategori = '$kategori', alamat = '$alamat', latitude = '$latitude', longitude = '$longitude', foto = '$foto' 
              WHERE id = $id";
    mysqli_query($conn, $query);
    header('Location: admin.php');
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Fasilitas</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        /* Gaya Halaman */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            background-color: #007BFF;
            color: white;
            padding: 20px;
            margin: 0;
            font-size: 28px;
        }

        .container {
            width: 50%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            margin-bottom: 8px;
        }

        input[type="text"],
        textarea {
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="file"] {
            margin-bottom: 20px;
        }

        button {
            padding: 12px 20px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #f1f1f1;
            margin-top: 20px;
        }

        a {
            color: #007BFF;
            text-decoration: none;
            font-size: 16px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h1>Ubah Fasilitas Pusat Perbelanjaan</h1>

    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data">
            <label>Nama:</label>
            <input type="text" name="nama" value="<?= $data['nama']; ?>" required>

            <label>Kategori:</label>
            <input type="text" name="kategori" value="<?= $data['kategori']; ?>" required>
            <label>Alamat:</label>
            <textarea name="alamat" required><?= $data['alamat']; ?></textarea>

            <label>Latitude:</label>
            <input type="text" name="latitude" value="<?= $data['latitude']; ?>" required>

            <label>Longitude:</label>
            <input type="text" name="longitude" value="<?= $data['longitude']; ?>" required>

            <label>Foto:</label>
            <input type="file" name="foto">

            <button type="submit">Simpan</button>
        </form>

        <p><a href="admin.php">Kembali ke Halaman Admin</a></p>
    </div>

    <footer>
    </footer>
</body>

</html>