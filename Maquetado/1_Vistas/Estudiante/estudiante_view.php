<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once __DIR__ . '/../Servidor/db.php'; // Inicia la sesión y conecta a la BD

// Proteger la página: si no hay sesión o el perfil no es de estudiante, redirigir al inicio
if (!isset($_SESSION['user_id']) || $_SESSION['perfil_id'] != 3) {
    header("Location: ../index.html");
    exit();
}

$estudiante_id = $_SESSION['user_id'];
$proyecto = null;
$progreso = 0;

// Consulta para obtener el proyecto del estudiante y los nombres de los participantes.
$sql = "SELECT 
            p.id, 
            p.title as Titulo, 
            p.description as Descripcion,
            (SELECT GROUP_CONCAT(CONCAT(u.nombres, ' ', u.apa) SEPARATOR ', ') 
             FROM users u 
             JOIN project_user pu_team ON u.id = pu_team.user_id 
             WHERE pu_team.project_id = p.id AND u.perfil_id = 3 AND u.id != ?) AS companeros,
            (SELECT GROUP_CONCAT(CONCAT(u.nombres, ' ', u.apa) SEPARATOR ', ') 
             FROM users u 
             JOIN project_user pu_teachers ON u.id = pu_teachers.user_id 
             WHERE pu_teachers.project_id = p.id AND u.perfil_id = 2) AS docentes
        FROM projects p
        JOIN project_user pu ON p.id = pu.project_id
        WHERE pu.user_id = ? AND p.activo = 1
        LIMIT 1";

$stmt = $conn->prepare($sql);
// Se bindea el ID del estudiante dos veces: una para excluirse de la lista de compañeros y otra para encontrar su proyecto.
$stmt->bind_param("ss", $estudiante_id, $estudiante_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $proyecto = $result->fetch_assoc();

    // Calcular progreso
    $total_entregables = 0;
    $entregas_realizadas = 0;

    // Contar el total de entregables para el proyecto
    $sql_total = "SELECT COUNT(e.id) as total FROM entregables e JOIN competencias c ON e.competencia_id = c.id JOIN asignaturas a ON c.asignatura_id = a.id JOIN project_asignatura pa ON a.id = pa.asignatura_id WHERE pa.project_id = ?";
    $stmt_total = $conn->prepare($sql_total);
    $stmt_total->bind_param("i", $proyecto['id']);
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    $total_entregables = $result_total->fetch_assoc()['total'] ?? 0;

    // Contar las entregas realizadas por el estudiante para ese proyecto
    $sql_entregas = "SELECT COUNT(id) as realizadas FROM entregas_estudiantes WHERE user_id = ? AND project_id = ?";
    $stmt_entregas = $conn->prepare($sql_entregas);
    $stmt_entregas->bind_param("si", $estudiante_id, $proyecto['id']);
    $stmt_entregas->execute();
    $result_entregas = $stmt_entregas->get_result();
    $entregas_realizadas = $result_entregas->fetch_assoc()['realizadas'] ?? 0;

    if ($total_entregables > 0) {
        $progreso = ($entregas_realizadas / $total_entregables) * 100;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Panel de Estudiante - SWGPI</title>
  <meta name="description" content="Panel de estudiante para el Sistema de Gestión de Proyectos Integradores.">
  <meta name="keywords" content="estudiante, sgpi, itssmt">

  <!-- Favicons -->
  <link href="assets/img/ITSSMT/ITSSMT.png" rel="icon">
  <link href="assets/img/ITSSMT/ITSSMT.png" rel="apple-touch-icon">

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

<body class="news-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="../index.html" class="logo d-flex align-items-center">
        <i class="bi bi-buildings"></i>
        <h1 class="sitename">Gestion de Proyectos Integradores ITSSMT</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="estudiante_view.php" class="active">Mi Proyecto</a></li>
          <li><a href="entregables_view.php">Mis Entregables</a></li>
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
              <h1 class="heading-title">Panel de Estudiante</h1>
              <p class="mb-0">
                Bienvenido/a a tu panel de proyectos integradores.
              </p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="../index.html">Inicio</a></li>
            <li class="current">Estudiante</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="container">
        <?php if (!$proyecto): ?>
          <div class="alert alert-info text-center">
            <i class="bi bi-info-circle-fill me-2"></i>
            Aún no estás asignado a ningún proyecto.
          </div>
        <?php else: ?>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title mb-0">Mi Proyecto Integrador</h3>
            </div>
            <div class="card-body mt-4">
              <h4><?php echo htmlspecialchars($proyecto['Titulo'] ?? 'Proyecto sin título'); ?></h4>
              <p class="lead"><?php echo htmlspecialchars($proyecto['Descripcion'] ?? 'Este proyecto no tiene una descripción detallada.'); ?></p>
              <hr>
              <h5>Participantes</h5>
              <p><strong>Asesor(es):</strong> <?php echo htmlspecialchars($proyecto['docentes'] ?? 'No asignado'); ?></p>
              <p><strong>Compañeros de equipo:</strong> <?php echo htmlspecialchars($proyecto['companeros'] ?? 'Ninguno'); ?></p>
              <hr>
              <h5>Progreso General</h5>
              <div class="progress" role="progressbar" aria-label="Progreso del proyecto" aria-valuenow="<?php echo round($progreso); ?>" aria-valuemin="0" aria-valuemax="100" style="height: 25px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: <?php echo round($progreso); ?>%"><?php echo round($progreso); ?>%</div>
              </div>
              <div class="text-center mt-2">
                <small><?php echo $entregas_realizadas; ?> de <?php echo $total_entregables; ?> entregables completados.</small>
              </div>
            </div>
            <div class="card-footer text-center">
              <div class="d-flex justify-content-center gap-2">
                <a href="entregables_view.php" class="btn btn-primary"><i class="bi bi-card-checklist"></i> Ver Mis Entregables</a>
                <a href="#" class="btn btn-info text-white"><i class="bi bi-patch-check"></i> Ver Calificaciones</a>
              </div>
            </div>
          </div>
        <?php endif; ?>
        </div>
      </div>
    </section>

  </main>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

</body>

</html>