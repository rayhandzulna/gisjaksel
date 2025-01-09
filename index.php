<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebGIS Fasilitas Umum - Jakarta</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        /* Gaya Halaman */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #212529;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .container {
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }

        .btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #218838;
        }

        #map {
            height: 500px;
            border: 2px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .filter-container,
        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .filter-container select,
        .search-container input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 80%;
            max-width: 400px;
            margin-bottom: 10px;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #f1f1f1;
            color: #555;
            border-top: 1px solid #ddd;
            margin-top: 20px;
        }

        footer span {
            color: red;
        }
    </style>
</head>

<body>
    <header>
        <h1>WebGIS Fasilitas Pusat Perbelanjaan</h1>
        <p>Kota Administrasi Jakarta Selatan</p>
    </header>
    <div class="container">
        <!-- Tombol ke Halaman Admin -->
        <a href="admin.php" class="btn">Halaman Admin</a>

        <!-- Form Filter -->
        <div class="filter-container">
            <select id="categoryFilter" onchange="filterByCategory()">
                <option value="">Semua Kategori</option>
                <?php
                // Ambil semua kategori unik dari database
                $kategoriQuery = "SELECT DISTINCT kategori FROM fasilitas_umum";
                $kategoriResult = mysqli_query($conn, $kategoriQuery);

                // Tambahkan kategori ke dropdown
                if ($kategoriResult) {
                    while ($kategori = mysqli_fetch_assoc($kategoriResult)) {
                        $kategoriName = htmlspecialchars($kategori['kategori'], ENT_QUOTES, 'UTF-8');
                        echo "<option value=\"$kategoriName\">$kategoriName</option>";
                    }
                }
                ?>
            </select>
        </div>

        <!-- Form Pencarian -->
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Cari fasilitas..." onkeyup="filterMap()">
        </div>

        <!-- Peta -->
        <div id="map"></div>
    </div>

    <footer>
        &copy; 2024 WebGIS - Kota Jakarta | Dibuat dengan <span>&#10084;</span>
    </footer>

    <script>
        // Inisialisasi peta untuk Jakarta
        var map = L.map('map').setView([-6.2088, 106.8456], 13); // Koordinat Jakarta, zoom level 13

        // Tambahkan layer peta (OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Array untuk menyimpan marker fasilitas
        var markers = [];

        <?php
        // Query untuk mengambil data fasilitas dari database
        $query = "SELECT * FROM fasilitas_umum"; // Pastikan tabel dan kolom sesuai
        $result = mysqli_query($conn, $query);

        // Periksa apakah query berhasil
        if ($result) {
            // Loop melalui setiap data fasilitas dan menambahkan marker pada peta
            while ($row = mysqli_fetch_assoc($result)) {
                // Escape data untuk menghindari karakter khusus yang merusak JavaScript
                $latitude = htmlspecialchars($row['latitude'], ENT_QUOTES, 'UTF-8');
                $longitude = htmlspecialchars($row['longitude'], ENT_QUOTES, 'UTF-8');
                $nama = htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8');
                $kategori = htmlspecialchars($row['kategori'], ENT_QUOTES, 'UTF-8');
                $alamat = htmlspecialchars($row['alamat'], ENT_QUOTES, 'UTF-8');
                $foto = htmlspecialchars($row['foto'], ENT_QUOTES, 'UTF-8');

                // Tentukan path folder gambar
                $fotoPath = "img/$foto"; // Asumsi gambar disimpan di folder 'img'

                // Output JavaScript untuk menambahkan marker
                echo "
            var marker = L.marker([$latitude, $longitude])
                .addTo(map)
                .bindPopup('<b>$nama</b><br><i>$kategori</i><br>$alamat<br><img src=\"$fotoPath\" alt=\"Foto\" style=\"width:150px;height:auto;\">');
            marker.kategori = '$kategori'; // Simpan kategori dalam properti marker
            marker.nama = '$nama'.toLowerCase(); // Simpan nama dalam properti marker
            markers.push(marker);
            ";
            }
        } else {
            // Jika query gagal
            echo "console.error('Query database gagal: " . mysqli_error($conn) . "');";
        }
        ?>

        // Fungsi untuk menyaring marker berdasarkan kategori
        function filterByCategory() {
            var selectedCategory = document.getElementById("categoryFilter").value.toLowerCase();

            markers.forEach(function(marker) {
                if (selectedCategory === "" || marker.kategori.toLowerCase() === selectedCategory) {
                    marker.addTo(map); // Menambahkan marker yang sesuai
                } else {
                    map.removeLayer(marker); // Menghapus marker yang tidak sesuai
                }
            });
        }

        // Fungsi untuk mencari fasilitas berdasarkan nama
        function filterMap() {
            var searchText = document.getElementById("searchInput").value.toLowerCase();

            markers.forEach(function(marker) {
                if (marker.nama.includes(searchText)) {
                    marker.addTo(map); // Menambahkan marker yang sesuai
                } else {
                    map.removeLayer(marker); // Menghapus marker yang tidak sesuai
                }
            });
        }

        // Tambahkan layer GeoJSON untuk Jakarta Selatan
        fetch('jakarta_selatan.geojson')
            .then(response => response.json())
            .then(data => {
                // Tambahkan layer GeoJSON ke peta
                L.geoJSON(data, {
                    style: function(feature) {
                        return {
                            color: '#007bff', // Warna garis batas
                            fillColor: '#85c1e9', // Warna isi wilayah
                            fillOpacity: 0.5, // Transparansi warna isi
                            weight: 2 // Ketebalan garis
                        };
                    }
                }).addTo(map);
            })
            .catch(error => console.error('Error loading GeoJSON:', error));
    </script>
</body>

</html>