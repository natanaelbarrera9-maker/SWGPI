<?php
require_once __DIR__ . '/../Servidor/db.php'; // Conexión a la BD
$projects = [];
$sql = "SELECT 
            p.id, 
            p.title, 
            p.description,
            (SELECT GROUP_CONCAT(CONCAT(u.nombres, ' ', u.apa) SEPARATOR ', ') 
             FROM users u 
             JOIN project_user pu ON u.id = pu.user_id 
             WHERE pu.project_id = p.id AND u.activo = 1) AS autores
        FROM projects p
        WHERE p.activo = 1
        ORDER BY p.created_at DESC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Repositorio por Temas - SWGPI</title>
  <meta name="description" content="Repositorio de proyectos integradores filtrado por temas.">
  <meta name="keywords" content="repositorio, temas, itssmt, proyectos">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="faculty-staff-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center">
        <i class="bi bi-buildings"></i>
        <h1 class="sitename">Gestion de Proyectos Integradores ITSSMT</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.html">Inicio</a></li>
          <li class="dropdown"><a href="#" class="active"><span>Repositorio</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="about.html">Repositorio general</a></li>
              <li><a href="admissions.html">Por Carrera</a></li>
              <li><a href="academics.html">Por Generacion</a></li>
              <li><a href="faculty-staff.php">Temas</a></li>
            </ul>
          </li>
          <li><a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Iniciar Sesion</a></li>
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
              <h1 class="heading-title">Repositorio por Temas</h1>
              <p class="mb-0">
                Busca proyectos integradores por áreas de interés o temas específicos.
              </p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="index.html">Inicio</a></li>
            <li><a href="#">Repositorio</a></li>
            <li class="current">Temas</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Content Section -->
    <section id="faculty-staff" class="faculty-staff section">
      <div class="container">
        <div class="row gy-4">
          <?php if (empty($projects)): ?>
            <div class="col-12">
              <div class="alert alert-info text-center">No hay proyectos disponibles en el repositorio.</div>
            </div>
          <?php else: ?>
            <?php foreach ($projects as $project): ?>
              <div class="col-lg-6">
                <div class="card h-100">
                  <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($project['title']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($project['description'] ?? 'Sin descripción.'); ?></p>
                    <p class="small text-muted"><strong>Autores:</strong> <?php echo htmlspecialchars($project['autores'] ?? 'No asignados'); ?></p>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </section><!-- /Content Section -->

  </main>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>