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

    // Query untuk mengambil jumlah total formulir dari tabel pelaporan
    $sql_total_forms = "SELECT COUNT(*) AS total FROM pelaporan";
    $result_total_forms = $conn->query($sql_total_forms);
    $row_total_forms = $result_total_forms->fetch_assoc();
    $total_forms = $row_total_forms['total'];

    // Hitung waktu 24 jam yang lalu
    $time_24_hours_ago = time() - (24 * 60 * 60); // 24 jam yang lalu

    // Query untuk mengambil jumlah formulir dalam 24 jam terakhir
    $sql_forms_last_24h = "SELECT COUNT(*) AS forms_last_24h FROM pelaporan WHERE time >= $time_24_hours_ago";
    $result_forms_last_24h = $conn->query($sql_forms_last_24h);
    $row_forms_last_24h = $result_forms_last_24h->fetch_assoc();
    $forms_last_24h = $row_forms_last_24h['forms_last_24h'];
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
              <a class="sidebar-link" href="#" aria-expanded="false">
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
              <a class="sidebar-link" href="laporan.php" aria-expanded="false">
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
              <a href="" target="_blank" class="btn btn-primary">Toogle</a>
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
      <!--  Header End -->
      <div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <h5 class="fw-semibold mb-4">Selamat Datang</h5>
            <p class="mb-0">Di Website Sistem Informasi Aplikasi Laporan Perundungan </p>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <h5 class="mb-3">Total Forms</h5>
            <p><?php echo $total_forms; ?></p>
        </div>
        <div class="col-md-6">
            <h5 class="mb-3">Forms Last 24 Hours</h5>
            <p><?php echo $forms_last_24h; ?></p>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-6 offset-md-3">
            <canvas id="chartForms" width="400" height="400"></canvas>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data untuk grafik lingkaran
    var data = {
        labels: ["Total Forms", "Forms Last 24 Hours"],
        datasets: [{
            data: [<?php echo $total_forms; ?>, <?php echo $forms_last_24h; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)'
            ],
            borderWidth: 1
        }]
    };

    // Konfigurasi grafik lingkaran
    var options = {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            position: 'top',
        },
        title: {
            display: true,
            text: 'Forms Overview'
        }
    };

    // Inisialisasi grafik lingkaran
    var ctx = document.getElementById('chartForms').getContext('2d');
    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: data,
        options: options
    });
</script>


  <script src="./assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="./assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./assets/js/sidebarmenu.js"></script>
  <script src="./assets/js/app.min.js"></script>
  <script src="./assets/libs/simplebar/dist/simplebar.js"></script>
</body>

</html>