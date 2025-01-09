<?php
$conn = mysqli_connect("localhost", "root", "", "gis");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
