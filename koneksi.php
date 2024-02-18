<?php
// Koneksi ke database
$servername = "localhost"; // Ganti dengan nama server Anda
$username = "u1574155_aliza"; // Ganti dengan username database Anda
$password = "siapsmanel"; // Ganti dengan password database Anda
$database = "u1574155_aliza"; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
