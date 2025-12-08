<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once __DIR__ . '/../Servidor/db.php'; // Inicia la sesión y conecta a la BD

// Proteger la página: si no hay sesión o el perfil no es de docente, redirigir al inicio
if (!isset($_SESSION['user_id']) || $_SESSION['perfil_id'] != 2) {
    header("Location: index.html");
    exit();
}

$docente_id = $_SESSION['user_id'];
$proyectos = [];

// Consulta para obtener los proyectos del docente y los nombres de los estudiantes en cada proyecto.
$sql = "SELECT 
            p.id, 
            p.title, 
            p.description,
            (SELECT GROUP_CONCAT(CONCAT(u.nombres, ' ', u.apa) SEPARATOR ', ') 
             FROM users u 
             JOIN project_user pu_students ON u.id = pu_students.user_id
             WHERE pu_students.project_id = p.id AND u.perfil_id = 3 AND u.activo = 1) AS estudiantes
        FROM projects p
        JOIN project_user pu ON p.id = pu.project_id
        WHERE pu.user_id = ? AND p.activo = 1
        ORDER BY p.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $docente_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $proyectos[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Panel de Docente - SWGPI</title>
  <meta name="description" content="Panel de docente para el Sistema de Gestión de Proyectos Integradores.">
  <meta name="keywords" content="docente, sgpi, itssmt">

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
          <li><a href="docente_view.php" class="active">Inicio</a></li>
          <li class="dropdown"><a href="#"><span>Gestión Académica</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="projects_view.php">Gestionar Proyectos</a></li>
              <li><a href="subjects_view.php">Gestionar Competencias</a></li>
              <li><a href="revision_entregables.php">Revisar Entregables</a></li>
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
              <h1 class="heading-title">Panel de Docente</h1>
              <p class="mb-0">
                Bienvenido/a al panel de gestión para docentes.
              </p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="../index.html">Inicio</a></li>
            <li class="current">Docente</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="container mb-5">
          <div class="row">
              <div class="col-md-4 mb-3">
                  <div class="card text-center h-100">
                      <div class="card-body">
                          <i class="bi bi-card-checklist fs-1 text-primary"></i>
                          <h5 class="card-title mt-2">Gestionar Competencias</h5>
                          <p class="card-text">Define y edita las competencias y entregables de tus asignaturas.</p>
                          <a href="subjects_view.php" class="btn btn-primary">Ir a Competencias</a>
                      </div>
                  </div>
              </div>
              <div class="col-md-4 mb-3">
                  <div class="card text-center h-100">
                      <div class="card-body">
                          <i class="bi bi-clipboard-check fs-1 text-success"></i>
                          <h5 class="card-title mt-2">Revisar Entregables</h5>
                          <p class="card-text">Visualiza, califica y comenta las entregas de los estudiantes.</p>
                          <a href="revision_entregables.php" class="btn btn-success">Ir a Revisiones</a>
                      </div>
                  </div>
              </div>
              <div class="col-md-4 mb-3">
                  <div class="card text-center h-100">
                      <div class="card-body">
                          <i class="bi bi-folder-symlink fs-1 text-info"></i>
                          <h5 class="card-title mt-2">Gestionar Proyectos</h5>
                          <p class="card-text">Consulta los detalles de los proyectos y registra nuevos.</p>
                          <a href="projects_view.php" class="btn btn-info text-white">Ir a Proyectos</a>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <div class="container">
        <h2 class="mb-4">Mis Proyectos Asignados</h2>
        <div class="row">
          <?php if (empty($proyectos)): ?>
            <div class="col-12">
              <div class="alert alert-info text-center">
                <i class="bi bi-info-circle-fill me-2"></i>
                Actualmente no tienes proyectos asignados.
              </div>
            </div>
          <?php else: ?>
            <?php foreach ($proyectos as $proyecto): ?>
              <div class="col-lg-6 mb-4">
                <div class="card h-100">
                  <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($proyecto['title']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($proyecto['description'] ?? 'Sin descripción.'); ?></p>
                    <p><strong>Estudiantes:</strong> <?php echo htmlspecialchars($proyecto['estudiantes'] ?? 'No asignados'); ?></p>
                  </div>
                  <div class="card-footer bg-transparent border-top-0 d-flex justify-content-start gap-2">
                    <button class="btn btn-primary btn-sm view-project-btn" data-project-id="<?php echo $proyecto['id']; ?>"><i class="bi bi-eye"></i> Ver Detalles</button>
                    <a href="revision_entregables.php#collapse-proj-<?php echo $proyecto['id']; ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-card-checklist"></i> Revisar Avances</a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </section>

  </main>

  <!-- Modal Ver Proyecto -->
  <div class="modal fade" id="viewProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detalle del Proyecto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="viewProjectBody">
          <p>Cargando...</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const viewProjectModal = new bootstrap.Modal(document.getElementById('viewProjectModal'));
    
    function escapeHtml(str) {
      return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    document.querySelectorAll('.view-project-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.getAttribute('data-project-id');
        const body = document.getElementById('viewProjectBody');
        body.innerHTML = '<p>Cargando...</p>';
        viewProjectModal.show();
        
        fetch(`projects_actions.php?action=get_project&id=${encodeURIComponent(id)}`)
          .then(r => r.json())
          .then(res => {
            if (res.status === 'success') {
              const p = res.data;
              const fechaRegistro = p.created_at ? new Date(p.created_at).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A';
              let html = `<ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>ID:</strong> ${escapeHtml(p.id || '')}</li>
                            <li class="list-group-item"><strong>Título:</strong> ${escapeHtml(p.title || '')}</li>
                            <li class="list-group-item"><strong>Estudiantes:</strong> ${escapeHtml(p.student_authors || 'No asignados')}</li>
                            <li class="list-group-item"><strong>Asesor Primario:</strong> ${escapeHtml(p.advisor_1 || 'No asignado')}</li>
                            <li class="list-group-item"><strong>Asesor Secundario:</strong> ${escapeHtml(p.advisor_2 || 'No asignado')}</li>
                            <li class="list-group-item"><strong>Asignaturas:</strong> ${escapeHtml(p.subjects || 'No asignadas')}</li>
                            <li class="list-group-item"><strong>Descripción:</strong><br/>${escapeHtml(p.description || 'Sin descripción.')}</li>
                            <li class="list-group-item"><strong>Fecha de Registro:</strong> ${escapeHtml(fechaRegistro)}</li>
                        </ul>`;
              body.innerHTML = html;
            } else {
              body.innerHTML = '<p class="text-danger">Error al cargar los detalles del proyecto.</p>';
            }
          }).catch(err => { body.innerHTML = '<p class="text-danger">Error de conexión.</p>'; });
      });
    });
  });
  </script>

</body>

</html>