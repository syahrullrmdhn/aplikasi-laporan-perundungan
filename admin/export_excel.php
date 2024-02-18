<?php
// Sertakan file koneksi.php untuk koneksi ke database
require_once "../koneksi.php";

// Membuat file Excel
$filename = "laporan_excel_" . date('Ymd') . ".xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");

// Query untuk mengambil data dari tabel pelaporan
$sql = "SELECT * FROM pelaporan";
$result = $conn->query($sql);

// Membuat header Excel
echo "Nama Lengkap\tNomor Whatsapp\tNama Pelaku\tNo Pelaku\tTempat Kejadian\tWaktu\tBukti\tDeskripsi\n";

// Mengambil data dari database dan menampilkannya dalam Excel
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo $row['fullname'] . "\t" . $row['whatsapp'] . "\t" . $row['perpetrator'] . "\t" . $row['perpetrator_id'] . "\t" . $row['location'] . "\t" . $row['time'] . "\t" . $row['evidence'] . "\t" . $row['keterangan'] . "\n";
    }
} else {
    echo "Tidak ada data\n";
}

$conn->close();
?>
