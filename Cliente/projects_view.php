<?php
require_once __DIR__ . '/../Servidor/db.php';

// Sólo administradores
if (!isset($_SESSION['user_id']) || $_SESSION['perfil_id'] != 1) {
  header('Location: index.html');
  exit();
}

// Mensajes de estado (registro/actualización/eliminación)
$status_msg = '';
if (isset($_GET['status'])) {
  $alert_type = $_GET['status'] == 'success' ? 'success' : 'danger';
  $msg_code = isset($_GET['msg']) ? $_GET['msg'] : 'operacion_desconocida';
  $messages = [
    'created' => 'Proyecto registrado correctamente.',
    'updated' => 'Proyecto actualizado correctamente.',
    'deleted' => 'Proyecto eliminado correctamente.',
    'error' => 'Ocurrió un error durante la operación.'
  ];
  $message = isset($messages[$msg_code]) ? $messages[$msg_code] : 'Operación finalizada.';
  $status_msg = "\n    <div class='alert alert-{$alert_type} alert-dismissible fade show' role='alert'>{$message}<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>\n";
}

$tablesToTry = ['projects', 'proyectos', 'proyecto'];
$found = false;
$rows = [];
$usedTable = null;

foreach ($tablesToTry as $t) {
    $q = "SELECT * FROM `$t` LIMIT 100";
    $res = $conn->query($q);
    if ($res !== false) {
        $found = true;
        $usedTable = $t;
        while ($r = $res->fetch_assoc()) $rows[] = $r;
        break;
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Proyectos - Panel Admin</title>
  <meta name="description" content="Gestión de Proyectos Integradores">

  <!-- Favicons -->
  <link href="assets/img/ITSSMT/ITSSMT.png" rel="icon">

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
          <li><a href="admin_welcome.php">Inicio</a></li>
          <li class="dropdown"><a href="admin_view.php"><span>Usuarios</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#" data-bs-toggle="modal" data-bs-target="#registerUserModal">Registrar Usuario</a></li>
              <li><a href="admin_view.php">Ver Usuarios</a></li>
            </ul>
          </li>
          <li class="dropdown"><a href="projects_view.php"><span>Proyectos</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="projects_view.php">Ver Proyectos</a></li>
              <li><a href="projects_view.php?action=register">Registrar Proyecto</a></li>
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
              <h1 class="heading-title">Gestión de Proyectos</h1>
              <p class="mb-0">Administra los proyectos integradores del repositorio.</p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="admin_welcome.php">Inicio</a></li>
            <li class="current">Proyectos</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <section id="projects-panel" class="admin-panel section">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2>Proyectos</h2>
          <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerProjectModal"><i class="bi bi-plus-circle"></i> Registrar Proyecto</button>
          </div>
        </div>

        <!-- Mensajes de estado -->
        <div id="status-messages"><?php echo $status_msg; ?></div>

        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead class="table-dark">
                  <tr>
                    <?php if (!$found): ?>
                      <th>Estado</th>
                    <?php else: ?>
                      <?php if (!empty($rows)): ?>
                        <?php foreach (array_keys($rows[0]) as $col): ?>
                          <th><?php echo htmlspecialchars($col); ?></th>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <th>No hay columnas</th>
                      <?php endif; ?>
                    <?php endif; ?>
                    <th class="text-center">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!$found): ?>
                    <tr><td colspan="100">No se encontró tabla de proyectos. Cree la tabla <code>projects</code> o <code>proyectos</code> para listar proyectos.</td></tr>
                  <?php else: ?>
                    <?php if (!empty($rows)): foreach ($rows as $r): ?>
                      <tr>
                        <?php foreach ($r as $c): ?>
                          <td><?php echo htmlspecialchars($c); ?></td>
                        <?php endforeach; ?>
                        <?php
                          // Determine a project identifier (try common column names)
                          $projId = null;
                          if (isset($r['id'])) $projId = $r['id'];
                          elseif (isset($r['project_id'])) $projId = $r['project_id'];
                          elseif (isset($r['idproyecto'])) $projId = $r['idproyecto'];
                          elseif (isset($r['id_proyecto'])) $projId = $r['id_proyecto'];
                          else { $vals = array_values($r); $projId = $vals[0]; }
                        ?>
                        <td class="text-center">
                          <button class="btn btn-info btn-sm view-project-btn" title="Ver" data-project-id="<?php echo htmlspecialchars($projId); ?>" data-bs-toggle="modal" data-bs-target="#viewProjectModal"><i class="bi bi-eye"></i></button>
                          <button class="btn btn-warning btn-sm edit-project-btn" title="Editar" data-project-id="<?php echo htmlspecialchars($projId); ?>" data-bs-toggle="modal" data-bs-target="#editProjectModal"><i class="bi bi-pencil"></i></button>
                          <button class="btn btn-danger btn-sm delete-project-btn" title="Eliminar" data-project-id="<?php echo htmlspecialchars($projId); ?>" data-bs-toggle="modal" data-bs-target="#deleteProjectModal"><i class="bi bi-trash"></i></button>
                        </td>
                      </tr>
                    <?php endforeach; else: ?>
                      <tr><td colspan="100">No hay proyectos registrados.</td></tr>
                    <?php endif; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="mt-3">
          <a href="admin_view.php" class="btn btn-secondary">Volver a Admin</a>
        </div>
      </div>
    </section>

  <!-- MODALES PARA PROYECTOS -->
  <!-- Ver Proyecto -->
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

  <!-- Editar Proyecto -->
  <div class="modal fade" id="editProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Proyecto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editProjectForm" method="post" action="projects_actions.php">
            <input type="hidden" name="action" value="update">
            <input type="hidden" id="editProjectId" name="id">
            <div class="mb-3">
              <label class="form-label">Título</label>
              <input type="text" class="form-control" id="editProjectTitle" name="title" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Autores</label>
              <input type="text" class="form-control" id="editProjectAuthors" name="authors">
            </div>
            <div class="mb-3">
              <label class="form-label">Año</label>
              <input type="number" class="form-control" id="editProjectYear" name="year">
            </div>
            <div class="mb-3">
              <label class="form-label">Descripción</label>
              <textarea class="form-control" id="editProjectDescription" name="description" rows="4"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" onclick="document.getElementById('editProjectForm').submit();">Guardar Cambios</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Eliminar Proyecto -->
  <div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmar Eliminación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>¿Está seguro que desea eliminar este proyecto?</p>
        </div>
        <div class="modal-footer">
          <form id="deleteProjectForm" method="post" action="projects_actions.php">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" id="deleteProjectId" name="id">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-danger">Eliminar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

        <!-- Registrar Proyecto -->
        <div class="modal fade" id="registerProjectModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Registrar Nuevo Proyecto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="registerProjectForm" method="post" action="projects_actions.php">
                  <input type="hidden" name="action" value="register">
                  <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input type="text" class="form-control" id="regProjectTitle" name="title" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Autores</label>
                    <input type="text" class="form-control" id="regProjectAuthors" name="authors">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Año</label>
                    <input type="number" class="form-control" id="regProjectYear" name="year">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" id="regProjectDescription" name="description" rows="4"></textarea>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('registerProjectForm').submit();">Registrar</button>
              </div>
            </div>
          </div>
        </div>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    // View project
    document.querySelectorAll('.view-project-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.getAttribute('data-project-id');
        const body = document.getElementById('viewProjectBody');
        body.innerHTML = 'Cargando...';
        fetch(`projects_actions.php?action=get_project&id=${encodeURIComponent(id)}`)
          .then(r => r.json())
          .then(res => {
            if (res.status === 'success') {
              const p = res.data;
              body.innerHTML = `<p><strong>Título:</strong> ${escapeHtml(p.title || '')}</p>
                                <p><strong>Autores:</strong> ${escapeHtml(p.authors || '')}</p>
                                <p><strong>Año:</strong> ${escapeHtml(p.year || '')}</p>
                                <p><strong>Descripción:</strong><br/>${escapeHtml(p.description || '')}</p>`;
            } else {
              body.innerHTML = '<p>Error al cargar el proyecto.</p>';
            }
          }).catch(err => { body.innerHTML = '<p>Error de conexión.</p>'; });
      });
    });

    // Edit project
    document.querySelectorAll('.edit-project-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.getAttribute('data-project-id');
        fetch(`projects_actions.php?action=get_project&id=${encodeURIComponent(id)}`)
          .then(r => r.json())
          .then(res => {
            if (res.status === 'success') {
              const p = res.data;
              document.getElementById('editProjectId').value = p.id || id;
              document.getElementById('editProjectTitle').value = p.title || '';
              document.getElementById('editProjectAuthors').value = p.authors || '';
              document.getElementById('editProjectYear').value = p.year || '';
              document.getElementById('editProjectDescription').value = p.description || '';
            }
          }).catch(console.error);
      });
    });

    // Delete project
    document.querySelectorAll('.delete-project-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.getAttribute('data-project-id');
        document.getElementById('deleteProjectId').value = id;
      });
    });

    function escapeHtml(str) {
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    }
  });
  </script>

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
