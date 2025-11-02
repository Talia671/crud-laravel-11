<?php
require '../vendor/autoload.php';
require '../config/koneksi.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Konfigurasi Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Ambil data dari database
$query = mysqli_query($conn, "SELECT * FROM mahasiswa ORDER BY nama ASC");

// HTML untuk PDF
$html = '
<html>
<head>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Daftar Mahasiswa</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>';

$no = 1;
while ($data = mysqli_fetch_assoc($query)) {
    $html .= '
        <tr>
            <td>' . $no++ . '</td>
            <td>' . htmlspecialchars($data['nama']) . '</td>
            <td>' . htmlspecialchars($data['nim']) . '</td>
            <td>' . htmlspecialchars($data['email']) . '</td>
        </tr>';
}

$html .= '
        </tbody>
    </table>
</body>
</html>';

// Render PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output ke browser
$dompdf->stream("daftar_mahasiswa.pdf", ["Attachment" => false]);
exit();
?>
