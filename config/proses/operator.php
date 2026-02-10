<?php
require '../conn.php';
session_start();

//-------------------
// Tambah
//-------------------
if ($_POST['aksi'] == 'tambah') {  
$nama_penguna     = $_POST['nama_penguna'];
$username         = $_POST['username'];
$alamat_penguna   = $_POST['alamat_penguna'];
$email_penguna    = $_POST['email_penguna'];
$password         = $_POST['password'];
$role             = $_POST['role'];

// HASH PASSWORD (WAJIB)
$hashPassword = password_hash($password, PASSWORD_DEFAULT);


// CEK USERNAME / EMAIL
$cek = $conn->prepare("SELECT * FROM penguna WHERE username = ? OR email_penguna = ?");
$cek->execute([$username, $email_penguna]);

if ($cek->rowCount() > 0) {
    header("Location: ../../admin/pages/operator/tambah_operator.php?error=username_exist&username=" . urlencode($username));
    exit;    
}

// INSERT DATA
$sql = "INSERT INTO penguna (nama_penguna, username, alamat_penguna, email_penguna, password, status)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->execute([
    $nama_penguna,
    $username,
    $alamat_penguna,
    $email_penguna,
    $hashPassword,
    $role
]);

header("Location: ../../admin/pages/operator/data_operator.php?msg=added");
exit;

}

//edit data
if ($_POST['aksi'] === 'edit') {
    $id       = $_POST['id_penguna'];
    $nama = trim($_POST['nama_penguna']);
    $email = trim($_POST['email_penguna']);
    $alamat = trim($_POST['alamat_penguna']);

  
    if ($nama === '') {
      header("Location: ../../admin/pages/operator/data_operator.php?error=kosong");
      exit;
    }
  
    $sql = "UPDATE penguna SET nama_penguna = :penguna, alamat_penguna = :alamat, email_penguna = :email WHERE id_penguna = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
      ':penguna' => $nama,
      ':alamat' => $alamat,
      ':email' => $email,
      ':id'       => $id
    ]);
  
    header("Location: ../../admin/pages/operator/data_operator.php?msg=updated");
    exit;
  }

  //hapus
  if ($_POST['aksi'] === 'hapus') {
    $id = $_POST['id_penguna'];
  
    $sql = "DELETE FROM penguna WHERE id_penguna = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
  
    header("Location: ../../admin/pages/operator/data_operator.php?msg=deleted");
    exit;
  }
  
  //cari
  $search = trim($_GET['search'] ?? '');

  // Jika kosong
  if ($search === '') {
    header("Location: ../../admin/pages/operator/data_operator.php?error=kosong");
    exit;
  }
  
  // Cek data
  $sql = "SELECT COUNT(*) FROM penguna 
          WHERE nama_penguna LIKE :search 
          OR username LIKE :search";
  
  $stmt = $conn->prepare($sql);
  $stmt->execute([
    ':search' => "%$search%"
  ]);
  
  $jumlah = $stmt->fetchColumn();
  
  // Redirect berdasarkan hasil
  if ($jumlah == 0) {
    header("Location: ../../admin/pages/operator/data_operator.php?search=$search&msg=notfound");
  } else {
    header("Location: ../../admin/pages/operator/data_operator.php?search=$search");
  }
  exit;