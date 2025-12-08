<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once __DIR__ . '/../Servidor/db.php';

// Protege la página: solo usuarios autenticados
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.html');
    exit();
}

// Solo administradores deberían ver esto
if ($_SESSION['perfil_id'] != 1) {
    header('Location: ../index.html');
    exit();
}

// Opcional: estado de mensajes
$status_msg = '';
if (isset($_GET['status'])) {
    $alert_type = $_GET['status'] == 'success' ? 'success' : 'danger';
    $msg = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '';
    $status_msg = "<div class='alert alert-{$alert_type} alert-dismissible fade show' role='alert'>{$msg}<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Bienvenida - Panel de Administrador</title>
  <meta name="description" content="Panel de administración para el Sistema de Gestión de Proyectos Integradores.">

  <!-- Favicons -->
  <link href="assets/img/ITSSMT/ITSSMT.png" rel="icon">
  <link href="assets/img/ITSSMT/ITSSMT.png" rel="apple-touch-icon">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="news-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="admin_welcome.php" class="logo d-flex align-items-center">
        <i class="bi bi-buildings"></i>
        <h1 class="sitename">Gestion de Proyectos Integradores ITSSMT</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="admin_welcome.php" class="active">Inicio</a></li>
          <li><a href="admin_view.php">Usuarios</a></li>
          <li><a href="advisors_view.php">Asesores</a></li>
          <li><a href="projects_view.php">Proyectos</a></li>
          <li class="dropdown"><a href="#"><span>Configuración Académica</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="subjects_view.php">Gestionar Asignaturas</a></li>
              <li><a href="project_subjects_view.php">Asignar Materias a Proyectos</a></li>
              <li><a href="grafos_view.php">Gestionar Grafos</a></li>
            </ul>
          </li>
          <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8">
              <h1 class="heading-title">Bienvenido al Panel de Administración</h1>
              <p class="mb-0">Desde aquí puedes gestionar usuarios, proyectos y herramientas del sistema.</p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="admin_welcome.php">Inicio</a></li>
            <li class="current">Bienvenida</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <section id="admin-panel" class="admin-panel section">
      <div class="container">
        <div id="status-messages"><?php echo $status_msg; ?></div>

        <div class="card">
          <div class="card-body text-center">
            <h2 class="mb-3">Bienvenido, <?php echo htmlspecialchars($_SESSION['user_id']); ?></h2>
            <p class="lead">Accede a las acciones rápidas a continuación.</p>
            <div class="d-flex justify-content-center gap-2 mt-4">
              <a href="admin_view.php" class="btn btn-primary">Gestión de Usuarios</a>
              <a href="projects_view.php" class="btn btn-secondary">Gestión de Proyectos</a>
              <a href="grafos_view.php" class="btn btn-info">Gestión de Grafos</a>
              <a href="logout.php" class="btn btn-outline-danger">Cerrar sesión</a>
            </div>
          </div>
        </div>

      </div>
    </section>

  </main>

  <footer id="footer" class="footer-16 footer position-relative dark-background">
    <div class="footer-bottom">
      <div class="container">
        <div class="bottom-content">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <div class="copyright">
                <p>© <span class="sitename">SWGPI ITSSMT</span>. Todos los derechos reservados.</p>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="legal-links">
                <div class="credits">
                  Diseñado por <a href="https://bootstrapmade.com/">BootstrapMade</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/js/main.js"></script>

</body>
</html>
