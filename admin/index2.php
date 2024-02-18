<?php
// Mulai session
session_start();

// Periksa jika pengguna tidak dalam sesi login, maka redirect ke halaman login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}

// Sertakan file koneksi.php untuk koneksi ke database
require_once "../koneksi.php";

// Inisialisasi variabel nama pengguna
$user_name = "";

// Periksa jika id pengguna tersedia dalam sesi
if(isset($_SESSION["id"]) && !empty($_SESSION["id"])){
    // Persiapkan pernyataan SELECT
    $sql = "SELECT email FROM users WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variabel parameter ke pernyataan persiapan sebagai parameter
        $stmt->bind_param("i", $param_id);

        // Set parameter
        $param_id = $_SESSION["id"];

        // Mencoba menjalankan pernyataan persiapan
        if ($stmt->execute()) {
            // Simpan hasil
            $stmt->store_result();

            // Bind hasil ke variabel
            $stmt->bind_result($user_name);

            // Jika data ditemukan, kembalikan nama pengguna
            if ($stmt->fetch()) {
                // Data ditemukan, gunakan nama pengguna untuk menyambut
                $user_name = $user_name;
            }
        }
        // Tutup pernyataan
        $stmt->close();
    }

    // Query untuk mengambil data dari tabel pelaporan
    $sql_pelaporan = "SELECT * FROM pelaporan";
    $result = $conn->query($sql_pelaporan);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2"></script> <!-- Tambahkan Alpine.js -->
</head>
<body>
<div role="alert" class="rounded border-s-4 border-green-500 bg-green-50 p-4">
  <div class="flex items-center gap-2 text-green-800">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
      <path
        fill-rule="evenodd"
        d="M12 2C6.487 2 2 6.487 2 12s4.487 10 10 10 10-4.487 10-10S17.513 2 12 2zM8.293 13.707a1 1 0 001.414 1.414L12 11.414l2.293 2.293a1 1 0 001.414-1.414L13.414 10l2.293-2.293a1 1 0 00-1.414-1.414L12 8.586 9.707 6.293a1 1 0 00-1.414 1.414L10.586 10l-2.293 2.293z"
        clip-rule="evenodd"
      />
    </svg>

    <strong class="block font-medium"> Welcome, <?php echo htmlspecialchars($user_name); ?>! </strong>
  </div>

  <p class="mt-2 text-sm text-green-700">
    You have successfully logged in.
  </p>

  <!-- Tombol Logout -->
  <form action="logout.php" method="post" class="mt-4">
    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
  </form>
</div>
</div>
</body>
</html>
