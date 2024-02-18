<?php
// Panggil library FPDF
require('./fpdf186/fpdf.php');

// Sertakan file koneksi.php untuk koneksi ke database
require_once "../koneksi.php";

// Membuat instance dari class FPDF dengan orientasi landscape
$pdf = new FPDF('L');
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);

// Query untuk mengambil data dari tabel pelaporan
$sql = "SELECT * FROM pelaporan";
$result = $conn->query($sql);

// Header tabel
$pdf->Cell(40,10,'Nama Lengkap',1,0,'C');
$pdf->Cell(40,10,'Nomor Whatsapp',1,0,'C');
$pdf->Cell(40,10,'Nama Pelaku',1,0,'C');
$pdf->Cell(40,10,'No Pelaku',1,0,'C');
$pdf->Cell(40,10,'Tempat Kejadian',1,0,'C');
$pdf->Cell(40,10,'Waktu',1,0,'C');
$pdf->Cell(100,10,'Deskripsi',1,1,'C');

// Mengatur jarak antar baris
$pdf->SetXY(10, 30);

// Mengambil data dari database dan menampilkannya dalam tabel PDF
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Mengatur jarak antar kolom
        $pdf->Cell(40,10,$row['fullname'],1,0,'L');
        $pdf->Cell(40,10,$row['whatsapp'],1,0,'L');
        $pdf->Cell(40,10,$row['perpetrator'],1,0,'L');
        $pdf->Cell(40,10,$row['perpetrator_id'],1,0,'L');
        $pdf->Cell(40,10,$row['location'],1,0,'L');
        $pdf->Cell(40,10,$row['time'],1,0,'L');
        $pdf->MultiCell(100,10,$row['keterangan'],1,'L');
        // Mengatur jarak antar baris
        $pdf->SetX(10);
    }
} else {
    // Jika tidak ada data
    $pdf->Cell(360,10,'Tidak ada data',1,1,'C');
}

// Output PDF
$pdf->Output();
?>
