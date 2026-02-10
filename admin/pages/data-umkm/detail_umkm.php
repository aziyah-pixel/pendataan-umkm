<?php
require '../../../config/auth/auth_admin.php';
require '../../../config/conn.php';

// CEK ID UMKM
if (!isset($_GET['id_umkm']) || empty($_GET['id_umkm'])) {
    header("Location: data_umkm.php?pilih=true");
    exit;
}

$id_umkm = $_GET['id_umkm'];

// AMBIL DATA UMKM
$sql = "SELECT 
u.*,
w.wilayah,
j.jenis_usaha
FROM umkm u
LEFT JOIN wilayah w ON u.id_wilayah = w.id_wilayah
LEFT JOIN jenis_usaha j ON u.id_usaha = j.id_usaha
WHERE u.id_umkm = ?
LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_umkm]);
$umkm = $stmt->fetch(PDO::FETCH_ASSOC);

// JIKA DATA TIDAK DITEMUKAN
if (!$umkm) {
    header("Location: data_umkm.php?notfound=true");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Plus Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../../assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../../../assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../../assets/vendors/font-awesome/css/font-awesome.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../../../assets/vendors/jquery-bar-rating/css-stars.css">
    <link rel="stylesheet" href="../../../assets/vendors/font-awesome/css/font-awesome.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../../../assets/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile border-bottom">
            <a href="#" class="nav-link flex-column">
              <img class="sidebar-brand-logo" src="../../../assets/images/logo.png" alt="">
              <!--<img class="sidebar-brand-logomini" src="../assets/images/logo-mini.png" alt="">-->
              <div class="nav-profile-text d-flex ms-0 mb-3 flex-column">
                <span class="fw-semibold mb-1 mt-2 text-center"><?= $_SESSION['nama_penguna']; ?></span>
              </div>
            </a>
          </li>
          <li class="nav-item pt-3">
            <form class="d-flex align-items-center" action="#">
              <div class="input-group">
                <div class="input-group-prepend">
                  <i class="input-group-text border-0 mdi mdi-magnify"></i>
                </div>
                <input type="text" class="form-control border-0" placeholder="Search">
              </div>
            </form>
          </li>
          <li class="pt-2 pb-1">
            <span class="nav-item-head">Menu Utama</span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../index.php">
              <i class="mdi mdi-compass-outline menu-icon"></i>
              <span class="menu-title">Beranda</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#data-umkm" aria-expanded="false" aria-controls="ui-basic">
              <i class="mdi mdi-store menu-icon"></i>
              <span class="menu-title">Data UMKM</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="data-umkm">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="../data-umkm/tambah_umkm.php">Tambah Data</a></li>
                <li class="nav-item"> <a class="nav-link" href="../data-umkm/data_umkm.php">Data UMKM</a></li>
                <li class="nav-item"> <a class="nav-link" href="../data-umkm/detail_umkm.php">Detail UMKM</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#operator" aria-expanded="false" aria-controls="icons">
              <i class="mdi mdi-account-group menu-icon"></i>
              <span class="menu-title">Operator</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="operator">
              <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="../operator/tambah_operator.php">Tambah Operator</a></li>
                <li class="nav-item"> <a class="nav-link" href="../operator/operator.php">Daftar Operator</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#master-data" aria-expanded="false" aria-controls="forms">
              <i class="mdi mdi-database menu-icon"></i>
              <span class="menu-title">Master Data</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="master-data">
              <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="../master-data/jenis_usaha.php">Jenis Data</a></li>
                <li class="nav-item"> <a class="nav-link" href="../master-data/pengurus.php">Pengurus</a></li>
                <li class="nav-item"> <a class="nav-link" href="../master-data/wilayah.php">Wilayah</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#laporan" aria-expanded="false" aria-controls="charts">
              <i class="mdi mdi-chart-bar menu-icon"></i>
              <span class="menu-title">Laporan</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="laporan">
              <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="../laporan/lap-umkm.php">Data UMKM</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
              <i class="mdi mdi-lock menu-icon"></i>
              <span class="menu-title">User Pages</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
              <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="../profil/profil.php"> Profil </a></li>
                <li class="nav-item"> <a class="nav-link" href="../profil/logout.php"> Log Out </a></li>
              </ul>
            </div>
          </li>
        </ul>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
          <div class="navbar-menu-wrapper d-flex align-items-stretch">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
              <span class="mdi mdi-chevron-double-left"></span>
            </button>
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
              <a class="navbar-brand brand-logo-mini" href="../../index.php"><img src="../../../assets/images/logo-mini.png" alt="logo" /></a>
            </div>
            <ul class="navbar-nav">
              <li class="nav-item dropdown ms-3">
                <a class="nav-link" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                  <i class="mdi mdi-bell-outline"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-left navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                  <h6 class="px-3 py-3 fw-semibold mb-0">Notifications</h6>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-success">
                        <i class="mdi mdi-calendar"></i>
                      </div>
                    </div>
                    <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                      <h6 class="preview-subject fw-normal mb-0">New order recieved</h6>
                      <p class="text-gray ellipsis mb-0"> 45 sec ago </p>
                    </div>
                  </a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-warning">
                        <i class="mdi mdi-image-filter-vintage"></i>
                      </div>
                    </div>
                    <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                      <h6 class="preview-subject fw-normal mb-0">Server limit reached</h6>
                      <p class="text-gray ellipsis mb-0"> 55 sec ago </p>
                    </div>
                  </a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-info">
                        <i class="mdi mdi-link-variant"></i>
                      </div>
                    </div>
                    <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                      <h6 class="preview-subject fw-normal mb-0">Kevin karvelle</h6>
                      <p class="text-gray ellipsis mb-0"> 11:09 PM </p>
                    </div>
                  </a>
                  <div class="dropdown-divider"></div>
                  <h6 class="p-3 font-13 mb-0 text-primary text-center">View all notifications</h6>
                </div>
              </li>
            </ul>
            <ul class="navbar-nav navbar-nav-right">
              <li class="nav-item nav-profile dropdown d-none d-md-block">
                <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                  <div class="nav-profile-text">English </div>
                </a>
                <div class="dropdown-menu center navbar-dropdown" aria-labelledby="profileDropdown">
                  <a class="dropdown-item" href="#">
                    <i class="flag-icon flag-icon-bl me-3"></i> French </a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">
                    <i class="flag-icon flag-icon-cn me-3"></i> Chinese </a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">
                    <i class="flag-icon flag-icon-de me-3"></i> German </a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">
                    <i class="flag-icon flag-icon-ru me-3"></i>Russian </a>
                </div>
              </li>
              <li class="nav-item nav-logout d-none d-lg-block">
                <a class="nav-link" href="../../index.php">
                  <i class="mdi mdi-home-circle"></i>
                </a>
              </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
              <span class="mdi mdi-menu"></span>
            </button>
          </div>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper pb-0">

             <!-- PAGE HEADER -->
            <div class="page-header d-flex justify-content-between align-items-center">
                <h3 class="page-title">
                <i class="mdi mdi-file-document-outline text-primary"></i>
                  Detail UMKM
                </h3>
                <a href="data_umkm.php" class="btn btn-danger mt-2 mt-sm-0 btn-icon-text text-center">
                    <i class="mdi mdi-arrow-left"></i> Kembali
                </a>
            </div>
            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'update') : ?>
                  <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-pencil"></i> Data UMKM berhasil diperbarui
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>
            <?php endif; ?>

           <!-- CARD DETAIL -->
            <div class="card">
                <div class="card-body">

                <div class="row">

                    <!-- FOTO UMKM -->
                    <div class="col-md-4 text-center">
                    <?php
                    $foto = $umkm['foto'] ? $umkm['foto'] : 'default.jpg';
                    ?>
                    <img
                        src="../../asset/images/umkm/<?= $foto ?>"
                        class="img-fluid img-umkm mb-3"
                        alt="Foto UMKM">
                    </div>

                    <!-- DATA UMKM -->
                    <div class="col-md-8">
                    <h3 class="fw-bold mb-0"><?= htmlspecialchars($umkm['nama_umkm']); ?></h3>
                    <table class="table table-borderless table-detail">
                        <tr>
                        <td>Nama Pemilik</td>
                        <td>: <?= htmlspecialchars($umkm['nama_pemilik']); ?></td>
                        </tr>
                        <tr>
                        <td>NIK</td>
                        <td>: <?= htmlspecialchars($umkm['nik']); ?></td>
                        </tr>
                        <tr>
                        <td>No HP</td>
                        <td>: <?= htmlspecialchars($umkm['no_hp']); ?></td>
                        </tr>
                        <tr>
                        <td>Email</td>
                        <td>: <?= htmlspecialchars($umkm['email']); ?></td>
                        </tr>
                        <tr>
                        <td>Jenis Usaha</td>
                        <td>: <?= htmlspecialchars($umkm['jenis_usaha']); ?></td>
                        </tr>
                        <tr>
                        <td>Kategori</td>
                        <td>: <?= htmlspecialchars($umkm['kategori_usaha']); ?></td>
                        </tr>
                        <tr>
                        <td>Wilayah</td>
                        <td>: <?= htmlspecialchars($umkm['wilayah']); ?></td>
                        </tr>
                        <tr>
                        <td>Kelurahan</td>
                        <td>: <?= htmlspecialchars($umkm['kelurahan']); ?></td>
                        </tr>
                    </table>
                    </div>

                </div>

                <hr>

                <!-- ALAMAT -->
                <div class="row">
                    <div class="col-md-12">
                    <h5 class="mb-2">
                        <i class="mdi mdi-map-marker"></i> Alamat UMKM
                    </h5>
                    <p class="text-muted">
                    <?= nl2br(htmlspecialchars($umkm['alamat'])); ?>                    </p>
                    </div>
                </div>

                <!-- BUTTON AKSI -->
                <div class="text-end mt-4">
                    <a href="edit_umkm.php?id_umkm=<?= $umkm['id_umkm']; ?>" class="btn btn-warning t-2 mt-sm-0 btn-icon-text text-center">
                    <i class="mdi mdi-pencil"></i> Edit
                    </a>
                    <a href="export_detail_umkm_pdf.php?id_umkm=<?= $umkm['id_umkm']; ?>" 
                    class="btn btn-primary t-2 mt-sm-0 btn-icon-text text-center" target="blank">
                    <i class="mdi mdi-file-document-outline "></i> Export PDF
                    </a>
                </div>

                </div>
            </div>


          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2023 <a href="https://www.bootstrapdash.com/" target="_blank">BootstrapDash</a>. All rights reserved.</span>
              <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="mdi mdi-heart text-danger"></i></span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../../../assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../../../assets/vendors/jquery-bar-rating/jquery.barrating.min.js"></script>
    <script src="../../../assets/vendors/chart.js/chart.umd.js"></script>
    <script src="../../../assets/vendors/flot/jquery.flot.js"></script>
    <script src="../../../assets/vendors/flot/jquery.flot.resize.js"></script>
    <script src="../../../assets/vendors/flot/jquery.flot.categories.js"></script>
    <script src="../../../assets/vendors/flot/jquery.flot.fillbetween.js"></script>
    <script src="../../../assets/vendors/flot/jquery.flot.stack.js"></script>
    <script src="../../../assets/js/jquery.cookie.js" type="text/javascript"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../../../assets/js/off-canvas.js"></script>
    <script src="../../../assets/js/misc.js"></script>
    <script src="../../../assets/js/settings.js"></script>
    <script src="../../../assets/js/todolist.js"></script>
    <script src="../../../assets/js/hoverable-collapse.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="../../../assets/js/proBanner.js"></script>
    <script src="../../../assets/js/dashboard.js"></script>
    <!-- End custom js for this page -->
    <script src="../../asset/js/script.js"></script>
  </body>
</html>