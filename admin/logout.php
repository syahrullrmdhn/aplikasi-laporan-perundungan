<?php
// Mulai session
session_start();

// Hapus semua variabel sesi
$_SESSION = array();

// Hapus sesi secara menyeluruh
session_destroy();

// Redirect pengguna kembali ke halaman login
header("location: ../login.php");
exit;
?>
