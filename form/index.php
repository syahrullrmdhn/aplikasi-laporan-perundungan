<?php
// Sertakan file koneksi.php untuk koneksi ke database
require_once "../koneksi.php";

// Inisialisasi variabel
$fullname = $whatsapp = $perpetrator = $perpetrator_id = $location = $time = $evidence = $description = "";
$message = "";

// Memproses data formulir ketika disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi dan ambil nilai formulir
    $fullname = trim($_POST["fullname"]);
    $whatsapp = trim($_POST["whatsapp"]);
    $perpetrator = trim($_POST["perpetrator"]);
    $perpetrator_id = trim($_POST["perpetrator_id"]);
    $location = trim($_POST["location"]);
    $time = trim($_POST["time"]);
    $description = trim($_POST["description"]);
    
    // Penanganan file unggahan
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["evidence"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    // Periksa apakah file gambar atau tidak
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["evidence"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }
    
    // Periksa apakah file sudah ada
    if (file_exists($target_file)) {
        $uploadOk = 0;
    }
    
    // Periksa ukuran file
    if ($_FILES["evidence"]["size"] > 500000) {
        $uploadOk = 0;
    }
    
    // Izinkan beberapa format file tertentu
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $uploadOk = 0;
    }
    
    // Periksa jika $uploadOk bernilai 0 karena terjadi kesalahan
    if ($uploadOk == 0) {
        $message = "Maaf, file tidak diunggah.";
    // Jika semuanya ok, coba unggah file
    } else {
        if (move_uploaded_file($_FILES["evidence"]["tmp_name"], $target_file)) {
            $evidence = $target_file;
        } else {
            $message = "Maaf, terjadi kesalahan saat mengunggah file.";
        }
    }
    
    // Jika tidak ada kesalahan, lanjutkan untuk menyimpan data ke database
    if (empty($message)) {
        // Persiapkan pernyataan INSERT
        $sql = "INSERT INTO pelaporan (fullname, whatsapp, perpetrator, perpetrator_id, location, time, evidence, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variabel parameter ke pernyataan persiapan sebagai parameter
            $stmt->bind_param("ssssssss", $param_fullname, $param_whatsapp, $param_perpetrator, $param_perpetrator_id, $param_location, $param_time, $param_evidence, $param_keterangan);

            // Set parameter
            $param_fullname = $fullname;
            $param_whatsapp = $whatsapp;
            $param_perpetrator = $perpetrator;
            $param_perpetrator_id = $perpetrator_id;
            $param_location = $location;
            $param_time = $time;
            $param_evidence = $evidence;
            $param_keterangan = $description;

            // Mencoba menjalankan pernyataan persiapan
            if ($stmt->execute()) {
                // Set pesan sukses
                $message = "Laporan berhasil disimpan.";
                
                // Dapatkan ID pelaporan yang baru saja dimasukkan
                $new_report_id = $stmt->insert_id;
                
                // Tampilkan pesan sukses bersama dengan ID pelaporan
                $message_with_id = "Laporan berhasil dikirim. ID pelaporan Anda adalah: " . $new_report_id;
            } else {
                $message = "Oops! Ada yang salah. Silakan coba lagi nanti.";
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
    <title>Form - Sistem Informasi Laporan Perundungan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <link rel="shortcut icon" href="../asset/pics/smanel.png" type="image/png">
     <script src="https://cdn.tiny.cloud/1/2vhezy15g5mg5uguxlq25qmi22buyvmefn6ub42p6lkh253u/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<header class="bg-white">
    <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="md:flex md:items-center md:gap-12">
                <a class="block text-teal-600" href="#">
                    <span class="sr-only">Home</span>
                    <!-- Ganti SVG dengan gambar PNG -->
                    <img class="h-8" src="../asset/pics/logo smanel.png" alt="Logo">
                </a>
            </div>

    <div class="flex flex-1 items-center justify-end md:justify-between">
      <nav aria-label="Global" class="hidden md:block">
        <ul class="flex items-center gap-6 text-sm">
            <li>
            <a class="text-black transition hover:text-black-500/75" href="../index.php"> Homepage </a>
          </li>
          <li>
            <a class="text-black transition hover:text-black-500/75" href="../halaman/about.html"> Tentang Aplikasi </a>
          </li>

          <li>
            <a class="text-black transition hover:text-gray-500/75" href="../halaman/contact.html"> Kontak Kami </a>
          </li>
          
          <li>
            <a class="text-black transition hover:text-gray-500/75" href="../form"> Input Laporan  </a>
          </li>
          
          <li>
            <a class="text-black transition hover:text-gray-500/75" href="../status.php"> Cari Laporan </a>
          </li>
        </ul>
      </nav>

      <div class="flex items-center gap-4">
        <div class="sm:flex sm:gap-4">
          <a
            class="block rounded-md bg-yellow-400 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700"
            href="../login.php"
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

<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h2 class="text-2xl font-semibold mb-4">Form Pelaporan</h2>
        <?php if (!empty($message_with_id)) : ?>
            <div role="alert" class="bg-green-500 text-white font-bold rounded-md p-4 mb-4">
                <?php echo $message_with_id; ?>
            </div>
        <?php elseif (!empty($message)) : ?>
            <div role="alert" class="bg-green-500 text-white font-bold rounded-md p-4 mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="fullname">Nama Lengkap:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="fullname" name="fullname" type="text" placeholder="Nama Lengkap" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="whatsapp">Nomor Whatsapp:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="whatsapp" name="whatsapp" type="tel" placeholder="Nomor Whatsapp" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="perpetrator">Nama Pelaku:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="perpetrator" name="perpetrator" type="text" placeholder="Nama Pelaku">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="perpetrator_id">No Pelaku:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="perpetrator_id" name="perpetrator_id" type="text" placeholder="No Pelaku">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="location">Tempat Kejadian:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="location" name="location" type="text" placeholder="Tempat Kejadian" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="time">Waktu:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="time" name="time" type="datetime-local" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="evidence">Bukti (File):</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="evidence" name="evidence" type="file" accept="image/*">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Deskripsi Lengkap:</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
    <script>
        tinymce.init({
            selector: '#description',
            plugins: 'advlist autolink lists link image charmap print preview anchor',
            toolbar: 'undo redo | formatselect | bold italic backcolor | \
            alignleft aligncenter alignright alignjustify | \
            bullist numlist outdent indent | removeformat | help'
        });
    </script>
</body>
</html>
