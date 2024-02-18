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

// Sertakan pustaka TCPDF untuk ekspor ke PDF
require_once "./fpdf186/fpdf.php";


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

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Sistem Informasi Laporan Perundungan</title>
  <link rel="shortcut icon" type="image/png" href="../asset/pics/logo smanel.png" />
  <link rel="stylesheet" href="./assets/css/styles.min.css" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="" class="text-nowrap logo-img">
            <img src="../asset/pics/logo smanel.png" width="180" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Home</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.php" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Menu</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./laporan.php" aria-expanded="false">
                <span>
                  <i class="ti ti-article"></i>
                </span>
                <span class="hide-menu">Lihat Laporan</span>
              </a>
            </li>
         <li class="sidebar-item">
              <a class="sidebar-link" href="manage_user.php" aria-expanded="false">
                <span>
                  <i class="ti ti-article"></i>
                </span>
                <span class="hide-menu">Manage User</span>
              </a>
            </li>
            
          </ul>
          <div class="unlimited-access hide-menu bg-light-primary position-relative mb-7 mt-5 rounded">
            <div class="d-flex">
              <div class="unlimited-access-title me-3">
                
              <div class="unlimited-access-img">
                <img src="./assets/images/backgrounds/rocket.png" alt="" class="img-fluid">
              </div>
            </div>
          </div>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                <i class="ti ti-bell-ringing"></i>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <a href="#" target="_blank" class="btn btn-primary">Toogle</a>
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="./assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                    
                    <a href="./logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
    <div class="container-fluid">
    <h2 class="mt-5 mb-3">Daftar Laporan</h2>
    <div class="mb-3">
        <a href="export_pdf.php" class="btn btn-primary">Export to PDF</a>
        <a href="export_excel.php" class="btn btn-primary">Export to Excel</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Lengkap</th>
                <th>Nomor Whatsapp</th>
                <th>Nama Pelaku</th>
                <th>No Pelaku</th>
                <th>Tempat Kejadian</th>
                <th>Waktu</th>
                <th>Bukti</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            <?php require_once "../koneksi.php";

            // Query untuk mengambil data dari tabel pelaporan
            $sql = "SELECT * FROM pelaporan";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['fullname'] . "</td>";
                    echo "<td>" . $row['whatsapp'] . "</td>";
                    echo "<td>" . $row['perpetrator'] . "</td>";
                    echo "<td>" . $row['perpetrator_id'] . "</td>";
                    echo "<td>" . $row['location'] . "</td>";
                    echo "<td>" . $row['time'] . "</td>";
                    echo "<td><button type='button' class='btn btn-primary' data-toggle='modal' data-target='#gambarModal{$row['id']}'>Tampilkan</button></td>";
                    echo "<td>" . $row['keterangan'] . "</td>";
                    echo "</tr>";

                    // Modal Gambar Bukti
                    echo "<div class='modal fade' id='gambarModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='gambarModalLabel' aria-hidden='true'>";
                    echo "<div class='modal-dialog' role='document'>";
                    echo "<div class='modal-content'>";
                    echo "<div class='modal-header'>";
                    echo "<h5 class='modal-title' id='gambarModalLabel'>Bukti Gambar</h5>";
                    echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
                    echo "<span aria-hidden='true'>&times;</span>";
                    echo "</button>";
                    echo "</div>";
                    echo "<div class='modal-body'>";
                    echo "<img src='../form/{$row['evidence']}' class='img-fluid' alt='Bukti Gambar'>";
                    echo "</div>";
                    echo "<div class='modal-footer'>";
                    echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Tutup</button>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<tr><td colspan='8'>Tidak ada data</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="./assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="./assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./assets/js/sidebarmenu.js"></script>
  <script src="./assets/js/app.min.js"></script>
  <script src="./assets/libs/simplebar/dist/simplebar.js"></script>
</body>

</html>
