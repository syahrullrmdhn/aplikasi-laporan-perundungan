<?php
// Sertakan file koneksi.php untuk koneksi ke database
require_once "koneksi.php";

// Inisialisasi variabel
$email = $password = "";
$email_err = $password_err = "";
$error_message = ""; // Variabel untuk menyimpan pesan kesalahan

// Memproses data formulir ketika disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validasi email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Masukkan email Anda.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validasi password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Masukkan password Anda.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Memeriksa kesalahan masukan sebelum mengirimkan ke database
    if (empty($email_err) && empty($password_err)) {
        // Persiapkan pernyataan SELECT
        $sql = "SELECT id, email, password FROM users WHERE email = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variabel parameter ke pernyataan persiapan sebagai parameter
            $stmt->bind_param("s", $param_email);

            // Set parameter
            $param_email = $email;

            // Mencoba menjalankan pernyataan persiapan
            if ($stmt->execute()) {
                // Menyimpan hasil
                $stmt->store_result();

                // Memeriksa jika email ada, maka verifikasi password
                if ($stmt->num_rows == 1) {
                    // Bind hasil ke variabel
                    $stmt->bind_result($id, $email, $hashed_password);
                    if ($stmt->fetch()) {
                        if ($password === $hashed_password) { // Perubahan disini
                            // Password adalah benar, jadi mulai sesi baru
                            session_start();

                            // Simpan data dalam variabel sesi
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;

                            // Redirect user ke halaman welcome
                            header("location: ./admin/index.php");
                        } else {
                            // Tampilkan pesan error jika password salah
                            $password_err = "Password yang Anda masukkan tidak valid.";
                        }
                    }
                } else {
                    // Tampilkan pesan error jika email tidak ditemukan
                    $email_err = "Akun tidak ditemukan dengan email tersebut.";
                }
            } else {
                // Simpan pesan kesalahan koneksi
                $error_message = "Oops! Ada yang salah. Silakan coba lagi nanti.";
            }

            // Menutup pernyataan
            $stmt->close();
        }
    }

    // Menutup koneksi
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Sistem Informasi Laporan Perundungan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <link rel="shortcut icon" href="./asset/pics/smanel.png" type="image/png">
</head>
<header class="bg-white">
    <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="md:flex md:items-center md:gap-12">
                <a class="block text-teal-600" href="#">
                    <span class="sr-only">Home</span>
                    <!-- Ganti SVG dengan gambar PNG -->
                    <img class="h-8" src="./asset/pics/logo smanel.png" alt="Logo">
                </a>
            </div>

    <div class="flex flex-1 items-center justify-end md:justify-between">
      <nav aria-label="Global" class="hidden md:block">
        <ul class="flex items-center gap-6 text-sm">
               <li>
            <a class="text-black transition hover:text-black-500/75" href="index.php"> Homepage </a>
          </li>
          <li>
            <a class="text-black transition hover:text-black-500/75" href="./halaman/about.html"> Tentang Aplikasi </a>
          </li>

          <li>
            <a class="text-black transition hover:text-gray-500/75" href="./halaman/contact.html"> Kontak Kami </a>
          </li>
          
          <li>
            <a class="text-black transition hover:text-gray-500/75" href="./form"> Input Laporan  </a>
          </li>
          
          <li>
            <a class="text-black transition hover:text-gray-500/75" href="./status.php"> Cari Laporan </a>
          </li>
        </ul>
      </nav>

      <div class="flex items-center gap-4">
        <div class="sm:flex sm:gap-4">
          <a
            class="block rounded-md bg-yellow-400 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700"
            href="./login.php"
          >
            Login
          </a>
        </div>

        <button
          class="block rounded bg-gray-100 p-2.5 text-gray-600 transition hover:text-gray-600/75 md:hidden"
        >
          <span class="sr-only">Toggle menu</span>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</header>
<body>
<div class="mx-auto max-w-screen-xl px-4 py-16 sm:px-6 lg:px-8">
  <div class="mx-auto max-w-lg">
    <h1 class="text-center text-2xl font-bold text-red-600 sm:text-3xl">Administrator Login</h1>

    <p class="mx-auto mt-4 max-w-md text-center text-gray-500">
      Hanya Administrator yang Diizinkan
    </p>

    <?php
    // Tampilkan pesan kesalahan jika ada
    if (!empty($error_message)) {
        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">';
        echo '<strong class="font-bold">Oops!</strong> ' . $error_message;
        echo '</div>';
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="mb-0 mt-6 space-y-4 rounded-lg p-4 shadow-lg sm:p-6 lg:p-8">
      <p class="text-center text-lg font-medium">Masukkan Akun Anda</p>

      <div>
        <label for="email" class="sr-only">Email</label>

        <div class="relative">
          <input
            type="email"
            name="email" 
            class="w-full rounded-lg border-gray-200 p-4 pe-12 text-sm shadow-sm"
            placeholder="Masukkan email"
          />

          <span class="absolute inset-y-0 end-0 grid place-content-center px-4">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="size-4 text-gray-400"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"
              />
            </svg>
          </span>
        </div>
      </div>

      <div>
        <label for="password" class="sr-only">Password</label>

        <div class="relative">
          <input
            type="password"
            name="password" 
            class="w-full rounded-lg border-gray-200 p-4 pe-12 text-sm shadow-sm"
            placeholder="Masukkan password"
          />

          <span class="absolute inset-y-0 end-0 grid place-content-center px-4">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="size-4 text-gray-400"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
              />
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
              />
            </svg>
          </span>
        </div>
      </div>

      <button
        type="submit"
        class="block w-full rounded-lg bg-red-800 px-5 py-3 text-sm font-medium text-white"
      >
        Masuk
      </button>
    </form>
  </div>
</div>
</body>
</html>
