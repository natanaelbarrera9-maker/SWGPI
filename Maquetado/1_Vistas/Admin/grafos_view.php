<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once __DIR__ . '/../Servidor/db.php'; // Inicia la sesión y conecta a la BD

// Proteger la página: si no hay sesión o el perfil no es de admin, redirigir al inicio
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['perfil_id'], [1, 2])) { // Admin o Docente
    header('Location: index.html?error=unauthorized');
    exit();
}

// Lógica para mostrar mensajes de estado (éxito o error)
$status_msg = '';
if (isset($_GET['status'])) {
    $alert_type = $_GET['status'] == 'success' ? 'success' : 'danger';
    $msg_code = $_GET['msg'] ?? 'operacion_desconocida';
    $messages = [
        'grafo_cargado' => 'Grafo cargado exitosamente.',
        'error_subida' => 'Error al mover el archivo al servidor.',
        'archivo_muy_grande' => 'Error: El archivo es demasiado grande (límite 5MB).',
        'tipo_archivo_invalido' => 'Error: Tipo de archivo no permitido (solo JPG, JPEG).',
        'faltan_datos_grafo' => 'Error: Faltan el título o el archivo del grafo.',
    ];
    $message = $messages[$msg_code] ?? 'Ocurrió un error.';

    $status_msg = "
    <div class='alert alert-{$alert_type} alert-dismissible fade show' role='alert'>
        {$message}
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
    ";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Gestión de Grafos - SWGPI</title>
  <meta name="description" content="Gestión de Grafos para el Sistema de Gestión de Proyectos Integradores.">

  <!-- Favicons -->
  <link href="assets/img/ITSSMT/ITSSMT.png" rel="icon">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

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
          <li><a href="admin_welcome.php">Inicio</a></li>
          <li><a href="admin_view.php">Usuarios</a></li>
          <li><a href="advisors_view.php">Asesores</a></li>
          <li><a href="projects_view.php">Proyectos</a></li>
          <li class="dropdown"><a href="#" class="active"><span>Configuración Académica</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="subjects_view.php">Gestionar Asignaturas</a></li>
              <li><a href="project_subjects_view.php">Asignar Materias a Proyectos</a></li>
              
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
              <h1 class="heading-title">Gestión de Grafos</h1>
              <p class="mb-0">Administra las imágenes de grafos del sistema.</p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="admin_welcome.php">Admin</a></li>
            <li class="current">Grafos</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <section id="grafos-panel" class="admin-panel section">
      <div class="container">
        <div id="status-messages"><?php echo $status_msg; ?></div>

        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2>Grafos Cargados</h2>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadGrafoModal">
            <i class="bi bi-cloud-upload"></i> Cargar Nuevo Grafo
          </button>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead class="table-dark">
                  <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Archivo</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $sql_grafos = "SELECT id, titulo, nombre_archivo, status FROM grafos ORDER BY fecha_creacion DESC";
                    $result_grafos = $conn->query($sql_grafos);
                    if ($result_grafos && $result_grafos->num_rows > 0) {
                        while($row = $result_grafos->fetch_assoc()) {
                            $status_badge = $row['status'] == 'activo' ? 'bg-success' : 'bg-danger';
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
                            echo "<td><a href='uploads/grafos/" . htmlspecialchars($row['nombre_archivo']) . "' target='_blank'>" . htmlspecialchars($row['nombre_archivo']) . "</a></td>";
                            echo "<td><span class='badge " . $status_badge . "'>" . htmlspecialchars($row['status']) . "</span></td>";
                            echo "<td class='text-center'>
                                    <button class='btn btn-info btn-sm' title='Revisar' data-bs-toggle='modal' data-bs-target='#viewGrafoModal' data-bs-image-url='uploads/grafos/" . htmlspecialchars($row['nombre_archivo']) . "' data-bs-image-title='" . htmlspecialchars($row['titulo']) . "'><i class='bi bi-eye'></i></button>
                                    <a href='editar_grafo.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm' title='Editar'><i class='bi bi-pencil'></i></a>
                                    <a href='desactivar_grafo.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' title='Desactivar' onclick=\"return confirm('¿Estás seguro de que quieres cambiar el estado de este grafo?');\"><i class='bi bi-toggle-off'></i></a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No hay grafos cargados.</td></tr>";
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Modal Cargar Grafo -->
  <div class="modal fade" id="uploadGrafoModal" tabindex="-1" aria-labelledby="uploadGrafoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="uploadGrafoModalLabel">Cargar Nuevo Grafo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="uploadGrafoForm" method="post" action="grafo_actions.php" enctype="multipart/form-data">
            <input type="hidden" name="action" value="upload">
            <div class="mb-3">
              <label for="grafoTitle" class="form-label">Título del Grafo</label>
              <input type="text" class="form-control" id="grafoTitle" name="titulo" required>
            </div>
            <div class="mb-3">
              <label for="grafoDescription" class="form-label">Descripción (Opcional)</label>
              <textarea class="form-control" id="grafoDescription" name="descripcion" rows="2"></textarea>
            </div>
            <div class="mb-3">
              <label for="grafoFile" class="form-label">Archivo de Imagen (JPG, JPEG)</label>
              <input class="form-control" type="file" id="grafoFile" name="grafo_file" accept=".jpg, .jpeg" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" form="uploadGrafoForm" class="btn btn-primary">Cargar Grafo</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para Visualizar Grafo -->
  <div class="modal fade" id="viewGrafoModal" tabindex="-1" aria-labelledby="viewGrafoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewGrafoModalLabel">Visualizador de Grafo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <img id="grafoModalImage" src="" class="img-fluid" alt="Grafo seleccionado">
        </div>
      </div>
    </div>
  </div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const viewGrafoModal = document.getElementById('viewGrafoModal');
    viewGrafoModal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget; // Botón que activó el modal
      const imageUrl = button.getAttribute('data-bs-image-url');
      const imageTitle = button.getAttribute('data-bs-image-title');

      const modalTitle = viewGrafoModal.querySelector('.modal-title');
      const modalImage = viewGrafoModal.querySelector('#grafoModalImage');

      modalTitle.textContent = imageTitle;
      modalImage.src = imageUrl;
    });
  });
  </script>
</body>
</html>