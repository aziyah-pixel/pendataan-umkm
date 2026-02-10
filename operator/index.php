<?php
/* ==================================================
   AUTH & KONEKSI (WAJIB PALING ATAS)
================================================== */
require '../config/auth/auth_operator.php';
require '../config/conn.php';

/* ==================================================
   SESSION
================================================== */
$id_penguna  = $_SESSION['id_penguna'] ?? null;
$nama_user   = $_SESSION['nama_penguna'] ?? null;

/* ==================================================
   JUMLAH DATA (CARD DASHBOARD)
================================================== */

// Jumlah UMKM
$stmt = $conn->prepare("SELECT COUNT(*) FROM umkm WHERE id_penguna = ?");
$stmt->execute([$id_penguna]);
$jumlah_umkm = $stmt->fetchColumn();

// Jumlah Wilayah
$stmt = $conn->query("SELECT COUNT(*) FROM wilayah");
$jumlah_wilayah = $stmt->fetchColumn();

// Jumlah Operator
$stmt = $conn->query("SELECT COUNT(*) FROM penguna");
$jumlah_operator = $stmt->fetchColumn();

// Jumlah Jenis Usaha
$stmt = $conn->query("SELECT COUNT(*) FROM jenis_usaha");
$jumlah_jenis = $stmt->fetchColumn();

/* ==================================================
   AREA CHART : UMKM PER WILAYAH (FILTER BY USER)
================================================== */

$sqlWilayahChart = "
  SELECT w.wilayah, COUNT(u.id_umkm) AS total
  FROM wilayah w
  LEFT JOIN umkm u 
    ON u.id_wilayah = w.id_wilayah
    AND u.id_penguna = :id_penguna
  GROUP BY w.id_wilayah
  ORDER BY w.wilayah ASC
";

$stmt = $conn->prepare($sqlWilayahChart);
$stmt->bindParam(':id_penguna', $id_penguna, PDO::PARAM_INT);
$stmt->execute();

$labelWilayah = [];
$dataUMKM     = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $labelWilayah[] = $row['wilayah'];
  $dataUMKM[]     = (int) $row['total'];
}

/* ==================================================
   DOUGHNUT CHART : JENIS USAHA (FILTER BY USER)
================================================== */

$sqlJenisChart = "
  SELECT j.jenis_usaha, COUNT(u.id_umkm) AS total
  FROM jenis_usaha j
  LEFT JOIN umkm u 
    ON u.id_usaha = j.id_usaha
    AND u.id_penguna = :id_penguna
  GROUP BY j.id_usaha
  ORDER BY j.jenis_usaha ASC
";

$stmt = $conn->prepare($sqlJenisChart);
$stmt->bindParam(':id_penguna', $id_penguna, PDO::PARAM_INT);
$stmt->execute();

$labelJenis = [];
$dataJenis  = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $labelJenis[] = $row['jenis_usaha'];
  $dataJenis[]  = (int) ($row['total'] ?? 0);
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SIPUMKM | Beranda</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../assets/vendors/jquery-bar-rating/css-stars.css">
    <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../assets/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile border-bottom">
            <a href="#" class="nav-link flex-column">
              <img class="sidebar-brand-logo" src="../assets/images/logo.png" alt="">
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
            <a class="nav-link" href="index.php">
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
                <li class="nav-item"> <a class="nav-link" href="pages/data-umkm/tambah_umkm.php">Tambah Data</a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/data-umkm/data_umkm.php">Data UMKM</a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/data-umkm/detail_umkm.php">Detail UMKM</a></li>
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
                <li class="nav-item"> <a class="nav-link" href="pages/laporan/lap-umkm.php">Data UMKM</a></li>
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
                <li class="nav-item"> <a class="nav-link" href="pages/profil/profil.php"> Profil </a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/profil/logout.php"> Log Out </a></li>
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
              <a class="navbar-brand brand-logo-mini" href="index.php"><img src="../assets/images/logo-mini.png" alt="logo" /></a>
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
                <a class="nav-link" href="index.php">
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

            <!-- ===== CARD ATAS ===== -->
            <div class="row mb-4">

              <!-- CARD SAMBUTAN -->
              <div class="col-xl-8 col-md-12 grid-margin">
                <div class="card">
                  <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                      <h4 class="fw-bold text-primary">
                        Selamat <?= htmlspecialchars($nama_user) ?> 🎉
                      </h4>
                      <p class="mb-3 text-muted">
                        Telah ada <strong><?= $jumlah_umkm ?></strong> UMKM yang berhasil didata. Cek data UMKM yang telah di input.
                      </p>
                      <a href="pages/data-umkm/data_umkm.php" class=" w-50">
                        <button type="button" class="btn btn-outline-primary btn-fw">Cek Data</button>
                      </a>
                    </div>
                    <img src="../admin/asset/images/umkm.svg" alt="" class="w-50">
                  </div>
                </div>
              </div>

              <div class="col-md-4 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Aksi Cepat</h4>
                    <a href="pages/data-umkm/tambah_umkm.php" class="btn btn-primary w-100 mb-2">
                      <i class="mdi mdi-plus-circle"></i> Tambah UMKM
                    </a>
                    <div class="card-body d-flex align-items-center">
                      <i class="mdi mdi-map-marker-radius mdi-36px text-success me-3"></i>
                      <div>
                        <h5 class="mb-0"><?= $jumlah_wilayah ?></h5>
                        <p class="text-muted mb-0">Wilayah </p>
                      </div>
                    </div>

                    <div class="card-body d-flex align-items-center">
                      <i class="mdi mdi-briefcase mdi-36px text-primary me-3"></i>
                      <div>
                        <h5 class="mb-0"><?= $jumlah_jenis ?></h5>
                        <p class="text-muted mb-0">Jenis Usaha </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>

            <div class="row">
              <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">UMKM per Wilayah</h4>
                    <canvas id="areaChart" height="250"></canvas>
                  </div>
                </div>
              </div>

              <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Distribusi Jenis Usaha</h4>
                    <div class="d-flex justify-content-center">
                      <canvas id="doughnutChart" height="250"></canvas>
                    </div>
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
    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../assets/vendors/jquery-bar-rating/jquery.barrating.min.js"></script>
    <script src="../assets/vendors/chart.js/chart.umd.js"></script>
    <script src="../assets/vendors/flot/jquery.flot.js"></script>
    <script src="../assets/vendors/flot/jquery.flot.resize.js"></script>
    <script src="../assets/vendors/flot/jquery.flot.categories.js"></script>
    <script src="../assets/vendors/flot/jquery.flot.fillbetween.js"></script>
    <script src="../assets/vendors/flot/jquery.flot.stack.js"></script>
    <script src="../assets/js/jquery.cookie.js" type="text/javascript"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../assets/js/off-canvas.js"></script>
    <script src="../assets/js/misc.js"></script>
    <script src="../assets/js/settings.js"></script>
    <script src="../assets/js/todolist.js"></script>
    <script src="../assets/js/hoverable-collapse.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="../assets/js/proBanner.js"></script>
    <script src="../assets/js/dashboard.js"></script>
    <!-- End custom js for this page -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
/* ===== AREA CHART : UMKM PER WILAYAH ===== */
const areaCtx = document.getElementById('areaChart').getContext('2d');
new Chart(areaCtx, {
  type: 'line',
  data: {
    labels: <?= json_encode($labelWilayah) ?>,
    datasets: [{
      label: 'Jumlah UMKM',
      data: <?= json_encode($dataUMKM) ?>,
      fill: true,
      backgroundColor: 'rgba(33, 150, 243, 0.2)', // biru lembut
      borderColor: 'rgba(33, 150, 243, 1)',       // biru utama
      pointBackgroundColor: 'rgba(33, 150, 243, 1)',
      pointBorderColor: '#ffffff',
      pointRadius: 4,
      tension: 0.4
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false }
    },
    scales: {
      y: {
        beginAtZero: true,
        grid: {
          color: 'rgba(0, 0, 0, 0.05)'
        }
      },
      x: {
        grid: {
          display: false
        }
      }
    }
  }
});

/* ===== DOUGHNUT CHART : JENIS USAHA ===== */
const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
new Chart(doughnutCtx, {
  type: 'doughnut',
  data: {
    labels: <?= json_encode($labelJenis) ?>,
    datasets: [{
      data: <?= json_encode($dataJenis) ?>,
      backgroundColor: [
        '#1E88E5', // biru tua
        '#42A5F5', // biru sedang
        '#90CAF9', // biru muda
        '#64B5F6', // biru soft
        '#BBDEFB', // biru sangat muda
        '#0D47A1'  // biru gelap
      ],
      borderWidth: 1,
      borderColor: '#ffffff'
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'bottom',
        labels: {
          padding: 20,
          boxWidth: 12
        }
      }
    },
    cutout: '65%' // bikin doughnut lebih modern
  }
});
</script>

  </body>
</html>