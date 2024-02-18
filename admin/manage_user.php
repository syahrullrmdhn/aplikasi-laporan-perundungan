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

// Fungsi untuk menambah pengguna baru
function tambahPengguna($conn, $full_name, $email, $password)
{
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (full_name, email, password) VALUES ('$full_name', '$email', '$hashed_password')";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Periksa apakah form tambah pengguna telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Panggil fungsi tambahPengguna untuk menambah pengguna baru
    if (tambahPengguna($conn, $full_name, $email, $password)) {
        // Redirect ke halaman manajemen pengguna setelah pengguna ditambahkan
        header("location: manage_user.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
// Query untuk mengambil data pengguna
$sql = "SELECT id, full_name, email, status FROM users";
$result = $conn->query($sql);
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
                            <a class="sidebar-link" href="laporan.php" aria-expanded="false">
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
                            <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse"
                                href="javascript:void(0)">
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
                                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="./assets/images/profile/user-1.jpg" alt="" width="35" height="35"
                                        class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
                                    aria-labelledby="drop2">
                                    <div class="message-body">
                                        <a href="javascript:void(0)"
                                            class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-user fs-6"></i>
                                            <p class="mb-0 fs-3">My Profile</p>
                                        </a>

                                        <a href="./logout.php"
                                            class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
           <div class="container-fluid">
         <div class="container-fluid">
        <h1 class="text-3xl font-semibold mb-5">Manajemen Pengguna</h1>
        <!-- Tambah Pengguna Button -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahPenggunaModal">
            Tambah Pengguna
        </button>

        <table class="min-w-full bg-white shadow-md rounded mb-5">
            <!-- Table Header -->
            <thead class="bg-gray-800 text-black">
                <tr>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">ID</th>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama Lengkap</th>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Email</th>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Status</th>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Tindakan</th>
                </tr>
            </thead>
            <tbody class="text-black">
                <?php
                if ($result->num_rows > 0) {
                    // Output data setiap baris
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='border-t px-4 py-4 text-black'>" . $row["id"] . "</td>";
                        echo "<td class='border-t px-4 py-4 text-black'>" . $row["full_name"] . "</td>";
                        echo "<td class='border-t px-4 py-4 text-black'>" . $row["email"] . "</td>";
                        echo "<td class='border-t px-4 py-4 text-black'>" . $row["status"] . "</td>";
                        echo "<td class='border-t px-4 py-4'><button onclick='showEditPopup(" . $row["id"] . ")' class='bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded'>Edit</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='border-t px-4 py-4 text-center'>Tidak ada pengguna</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Pengguna -->
    <div class="modal fade" id="tambahPenggunaModal" tabindex="-1" role="dialog"
        aria-labelledby="tambahPenggunaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPenggunaModalLabel">Tambah Pengguna Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="full_name">Nama Lengkap</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
            <!-- Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script src="./assets/libs/jquery/dist/jquery.min.js"></script>
            <script src="./assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
            <script src="./assets/js/sidebarmenu.js"></script>
            <script src="./assets/js/app.min.js"></script>
            <script src="./assets/libs/simplebar/dist/simplebar.js"></script>
    <script>
        var userId;

        function showEditPopup(id) {
            userId = id;
            $('#editUserModal').modal('show');
        }

        function updateUserStatus() {
            var status = $('#status').val();

            // Kirim data ke server untuk pembaruan status pengguna
            $.ajax({
                url: 'update_user_status.php',
                type: 'POST',
                data: {
                    id: userId,
                    status: status
                },
                success: function (response) {
                    // Tutup modal
                    $('#editUserModal').modal('hide');
                    // Refresh halaman
                    location.reload();
                },
                error: function (xhr, status, error) {
                    // Tangani kesalahan
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
</body>

</html>