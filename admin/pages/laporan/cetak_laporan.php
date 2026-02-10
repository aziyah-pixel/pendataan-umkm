<?php
require '../../../config/conn.php';
session_start();

// header supaya browser download Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_umkm.xls");
header("Pragma: no-cache");
header("Expires: 0");

// ambil id user login
$id_penguna = $_SESSION['id_penguna'];

// query data (contoh)
$sql = "SELECT 
    u.*,
    w.wilayah,
    j.jenis_usaha
FROM umkm u
LEFT JOIN wilayah w ON u.id_wilayah = w.id_wilayah
LEFT JOIN jenis_usaha j ON u.id_usaha = j.id_usaha
WHERE u.id_penguna = ?
ORDER BY u.id_umkm DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([$id_penguna]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!-- JUDUL -->
<p style="text-align:center;">
  Dicetak pada: <?= date('d-m-Y H:i') ?>
</p>
<h2 style="text-align:center;">LAPORAN DATA UMKM</h2>

<br>

<table border="1">
  <thead>
    <tr style="background:#d9edf7;">
    <th>No</th>
                      <th>Kode UMKM</th>
                      <th>Nama UMKM</th>
                      <th>Pemilik UMKM</th>
                      <th>NIK Pemilik</th>
                      <th>Email</th>
                      <th>Kontak</th>
                      <th>Jenis UMKM</th>
                      <th>Kategori UMKM</th>
                      <th>Wilayah</th>
                      <th>Alamat UMKM</th>
                      <th>Operator</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; foreach ($data as $row): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= $row['kode_umkm']; ?></td>
      <td><?= $row['nama_umkm']; ?></td>
      <td><?= $row['nama_pemilik']; ?></td>
      <td><?= $row['nik']; ?></td>
      <td><?= $row['email']; ?></td>
      <td><?= $row['no_hp']; ?></td>
      <td><?= $row['jenis_usaha']; ?></td>
      <td><?= $row['kategori_usaha']; ?></td>
      <td><?= $row['wilayah']; ?></td>
      <td><?= $row['alamat']; ?></td>
      <td><?= $row['operator']; ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
