<?php
require_once "../conn.php";
session_start();

$search = trim($_GET['search'] ?? '');

// Jika kosong
if ($search === '') {
  header("Location: ../../admin/pages/laporan/lap-umkm.php");
  exit;
}

// Cek data
$sql = "SELECT COUNT(*) FROM umkm 
        WHERE nama_umkm LIKE :search 
        OR kode_umkm LIKE :search";

$stmt = $conn->prepare($sql);
$stmt->execute([
  ':search' => "%$search%"
]);

$jumlah = $stmt->fetchColumn();

// Redirect berdasarkan hasil
if ($jumlah == 0) {
  header("Location: ../../admin/pages/laporan/lap-umkm.php?search=$search&msg=notfound");
} else {
  header("Location: ../../admin/pages/laporan/lap-umkm.php?search=$search");
}
exit;

?>