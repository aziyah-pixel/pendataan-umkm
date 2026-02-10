<?php
require '../../../config/auth/auth_operator.php';
require '../../../config/conn.php';

if (!isset($_GET['id_umkm'])) {
    header("Location: data_umkm.php");
    exit;
}

$id_umkm = $_GET['id_umkm'];

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

if (!$umkm) {
    header("Location: data_umkm.php");
    exit;
}

// Ambil wilayah
$sql = "SELECT * FROM wilayah ORDER BY wilayah ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$wilayah = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil jenis usaha
$sql = "SELECT * FROM jenis_usaha ORDER BY jenis_usaha ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$jenis = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
          <li class="nav-item active">
            <a class="nav-link" data-bs-toggle="collapse" href="#data-umkm" aria-expanded="false" aria-controls="ui-basic">
              <i class="mdi mdi-store menu-icon"></i>
              <span class="menu-title">Data UMKM</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="data-umkm">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="tambah_umkm.php">Tambah Data</a></li>
                <li class="nav-item"> <a class="nav-link" href="data_umkm.php">Data UMKM</a></li>
                <li class="nav-item"> <a class="nav-link" href="detail_umkm.php">Detail UMKM</a></li>
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
            <div class="page-header d-flex justify-content-between align-items-center">
                <h3 class="page-title mb-0">
                    <i class="mdi mdi-store-edit text-primary"></i>
                    Edit Data UMKM
                </h3>

                <a href="data_umkm.php" class="btn btn-danger btn-sm mt-2 mt-sm-0 btn-icon-text text-center">
                    <i class="mdi mdi-arrow-left"></i> Kembali
                </a>
            </div>
              <!-- TOAST NOTIFICATION -->
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
            <div id="toastError" class="toast align-items-center text-bg-danger border-0" role="alert">
              <div class="d-flex">
                <div class="toast-body" id="toastErrorMsg">
                  ❌ Terjadi kesalahan
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
              </div>
            </div>
            </div>
              <!-- FORM -->
            <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
  
                <form action="../../../config/operator/umkm.php" method="POST" enctype="multipart/form-data">
  
                    <input type="hidden" name="aksi" value="edit">
                    <input type="hidden" name="id_umkm" value="<?= $umkm['id_umkm']; ?>">

                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                            <label>Kode UMKM</label>
                            <input type="text" name="kode_umkm" class="form-control"
                                value="<?= $umkm['kode_umkm']; ?>" required readonly>
                        </div>
                        </div>

                        <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama UMKM</label>
                            <input type="text" name="nama_umkm" class="form-control"
                                value="<?= $umkm['nama_umkm']; ?>" required>
                        </div>
                        </div>

                        <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Pemilik</label>
                            <input type="text" name="pemilik" class="form-control"
                                value="<?= $umkm['nama_pemilik']; ?>" required>
                        </div>
                        </div>

                        <div class="col-md-6">
                        <div class="form-group">
                            <label>NIK Pemilik</label>
                            <input type="text" name="nik" class="form-control"
                                value="<?= $umkm['nik']; ?>" required>
                        </div>
                        </div>
                        
                        <div class="col-md-6">
                        <div class="form-group">
                            <label>Kontak</label>
                            <input type="text" name="no_hp" class="form-control"
                                value="<?= $umkm['no_hp']; ?>" required>
                        </div>
                        </div>

                        <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" class="form-control"
                                value="<?= $umkm['email']; ?>" required>
                        </div>
                        </div>

                          <!-- JENIS USAHA -->
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Jenis Usaha</label>
                          <select class="form-select" name="jenis_usaha" required>
                            <option value=""><?= $umkm['jenis_usaha']; ?></option>
                            <?php foreach ($jenis as $j) : ?>
                              <option value="<?= $j['id_usaha']; ?>">
                                <?= htmlspecialchars($j['jenis_usaha']); ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>

                       <!-- KATEGORI USAHA -->
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Kategori Usaha</label>
                          <select class="form-select" name="kategori_usaha" required>
                            <option value=""><?= $umkm['kategori_usaha']; ?></option>
                            <option>Mikro</option>
                            <option>Kecil</option>
                            <option>Menengah</option>
                          </select>
                          <div class="invalid-feedback">Pilih kategori usaha</div>
                        </div>
                      </div>

                        <div class="col-md-12">
                        <div class="form-group">
                          <label>Wilayah</label>
                          <select class="form-select" name="wilayah_umkm" required>
                            <option value=""><?= $umkm['wilayah']; ?></option>
                            <?php foreach ($wilayah as $w) : ?>
                              <option value="<?= $w['id_wilayah']; ?>">
                                <?= htmlspecialchars($w['wilayah']); ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>

                       <!-- KELURAHAN -->
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Kelurahan</label>
                          <input type="text" class="form-control" name="kelurahan_umkm" 
                          value="<?= $umkm['kelurahan']; ?>"required>
                        </div>
                      </div>
  
                      <!-- RT -->
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>RT</label>
                          <input type="text" class="form-control" name="rt_umkm"
                          value="<?= $umkm['rt']; ?>" required>
                        </div>
                      </div>
  
                      <!-- RW -->
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>RW</label>
                          <input type="text" class="form-control" name="rw_umkm" 
                          value="<?= $umkm['rw']; ?>"required>
                        </div>
                      </div>
  
                      <!-- ALAMAT -->
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Alamat UMKM</label>
                          <textarea class="form-control" rows="3" name="alamat_umkm" 
                          value="<?= $umkm['alamat']; ?>" ></textarea>
                        </div>
                      </div>

                    </div>

                    
                    <div class="row">
                        <!-- PREVIEW FOTO -->
                        <div class="col-md-6">
                            <div class="form-group">
                            <label>Preview Foto</label><br>
                            <img id="previewFoto"
                                src="../../asset/images/umkm/<?= $umkm['foto']; ?>"
                                class="img-thumbnail"
                                style="max-height: 150px;">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                            <label>Ganti Foto (Opsional)</label>
                            <input type="file"
                                    name="foto"
                                    class="form-control"
                                    id="fotoUMKM"
                                    accept="image/png, image/jpeg"
                                    value ="<?= $umkm['foto']?>"
                                    >
                            <small class="text-muted">Format JPG / PNG, Maks. 2MB</small>
                            <div class="invalid-feedback">Foto UMKM wajib diunggah</div>
                            </div>
                        </div>
                    </div>

                   <!-- BUTTON AKSI -->
                    <div class="text-end mt-4">
                    <button type="submit" class="btn btn-warning">
                        <i class="mdi mdi-content-save"></i> Update
                    </button>
                       
                    </div>
                    </form>

  
                </div>
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