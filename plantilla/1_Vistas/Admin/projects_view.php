<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once __DIR__ . '/../Servidor/db.php';

// Sólo administradores y docentes
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['perfil_id'], [1, 2])) {
  header('Location: index.html?error=unauthorized');
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
    // Consulta modificada para incluir los nombres de los autores
    $q = "SELECT 
            p.id, 
            p.title as Titulo, 
            p.description as Descripcion,
            (SELECT GROUP_CONCAT(CONCAT(u.nombres, ' ', u.apa) SEPARATOR ', ') 
             FROM users u 
             JOIN project_user pu_inner ON u.id = pu_inner.user_id
             WHERE pu_inner.project_id = p.id AND u.perfil_id = 3 AND u.activo = 1) AS autores,
            p.created_at AS 'fecha de registro'
          FROM `$t` p WHERE p.activo = 1 ORDER BY p.created_at DESC LIMIT 100";
    $res = $conn->query($q);
    if ($res !== false) {
        $found = true;
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

  <!-- Estilos para la lista de autores -->
  <style>
    #student-list .list-group-item {
      padding: 0.5rem 1rem;
    }
  </style>
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
            <a href="inactive_projects_view.php" class="btn btn-outline-secondary">
              <i class="bi bi-archive"></i> Ver Proyectos Inactivos
            </a>
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
                          <th><?php echo htmlspecialchars($col ?? ''); ?></th>
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
                          <td><?php echo htmlspecialchars($c ?? ''); ?></td>
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
                          <button class="btn btn-info btn-sm view-project-btn" title="Ver" data-project-id="<?php echo htmlspecialchars($projId ?? ''); ?>" data-bs-toggle="modal" data-bs-target="#viewProjectModal"><i class="bi bi-eye"></i></button>
                          <?php if ($_SESSION['perfil_id'] == 1): // Solo para Admin ?>
                          <button class="btn btn-warning btn-sm edit-project-btn" title="Editar" data-project-id="<?php echo htmlspecialchars($projId ?? ''); ?>" data-bs-toggle="modal" data-bs-target="#editProjectModal"><i class="bi bi-pencil"></i></button>
                          <button class="btn btn-danger btn-sm delete-project-btn" title="Eliminar" data-project-id="<?php echo htmlspecialchars($projId ?? ''); ?>" data-bs-toggle="modal" data-bs-target="#deleteProjectModal"><i class="bi bi-trash"></i></button>
                          <?php endif; ?>
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
          <form id="editProjectForm" method="post" action="projects_actions.php" novalidate>
            <input type="hidden" name="action" value="update">
            <input type="hidden" id="editProjectId" name="id">
            <div class="mb-3">
              <label for="editProjectTitle" class="form-label">Título del Proyecto</label>
              <input type="text" class="form-control" id="editProjectTitle" name="title" required>
            </div>
            <div class="mb-3">
              <label for="editProjectDescription" class="form-label">Descripción</label>
              <textarea class="form-control" id="editProjectDescription" name="description" rows="4"></textarea>
            </div>
            <div class="mb-3">
              <label for="editAddStudentId" class="form-label">Añadir Autor (por ID)</label>
              <div class="input-group">
                <input type="text" class="form-control" id="editAddStudentId" placeholder="Escribe el No. de Control">
                <button class="btn btn-outline-secondary" type="button" id="editAddStudentBtn">Añadir</button>
              </div>
              <div id="edit-student-feedback" class="form-text"></div>
            </div>
            <div id="edit-authors-container">
              <p>Autores asignados:</p>
              <ul id="edit-student-list" class="list-group mb-3"></ul>
              <div id="edit-hidden-student-inputs"></div>
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

  <!-- Modal Registrar Proyecto (Unificado) -->
  <div class="modal fade" id="registerProjectModal" tabindex="-1" aria-labelledby="registerProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="registerProjectModalLabel">Registrar Nuevo Proyecto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="registerProjectForm" method="post" action="projects_actions.php">
            <input type="hidden" name="action" value="register">
            <div class="mb-3">
              <label for="projectTitle" class="form-label">Título del Proyecto</label>
              <input type="text" class="form-control" id="projectTitle" name="title" required>
            </div>
            <div class="mb-3">
              <label for="projectDescription" class="form-label">Descripción</label>
              <textarea class="form-control" id="projectDescription" name="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
              <label for="addStudentId" class="form-label">Añadir Autor (por ID)</label>
              <div class="input-group">
                <input type="text" class="form-control" id="addStudentId" placeholder="Escribe el No. de Control o Empleado">
                <button class="btn btn-outline-secondary" type="button" id="addStudentBtn">Añadir</button>
              </div>
              <div id="student-feedback" class="form-text"></div>
            </div>
            <div id="authors-container">
              <p>Autores añadidos:</p>
              <ul id="student-list" class="list-group mb-3"></ul>
              <div id="hidden-student-inputs"></div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" form="registerProjectForm" class="btn btn-primary">Registrar Proyecto</button>
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
              const fechaRegistro = p.created_at ? new Date(p.created_at).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A';
              let html = `<ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>ID:</strong> ${escapeHtml(p.id || '')}</li>
                            <li class="list-group-item"><strong>Título:</strong> ${escapeHtml(p.title || '')}</li>
                            <li class="list-group-item"><strong>Autores (Alumnos):</strong> ${escapeHtml(p.student_authors || 'No asignados')}</li>`;
              
              if (p.advisor_1) {
                html += `<li class="list-group-item"><strong>Asesor Primario:</strong> ${escapeHtml(p.advisor_1)}</li>`;
              }
              if (p.advisor_2) {
                html += `<li class="list-group-item"><strong>Asesor Secundario:</strong> ${escapeHtml(p.advisor_2)}</li>`;
              }
              if (p.subjects) {
                html += `<li class="list-group-item"><strong>Asignaturas:</strong> ${escapeHtml(p.subjects)}</li>`;
              }
              html += `   <li class="list-group-item"><strong>Descripción:</strong><br/>${escapeHtml(p.description || 'Sin descripción.')}</li>
                            <li class="list-group-item"><strong>Fecha de Registro:</strong> ${escapeHtml(fechaRegistro)}</li>
                        </ul>`;
              body.innerHTML = html;
            } else {
              body.innerHTML = '<p>Error al cargar el proyecto.</p>';
            }
          }).catch(err => { body.innerHTML = '<p>Error de conexión.</p>'; });
      });
    });

    // Edit project
    document.querySelectorAll('.edit-project-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        // Limpiar el modal antes de llenarlo
        document.getElementById('editProjectForm').reset();
        document.getElementById('edit-student-list').innerHTML = '';
        document.getElementById('edit-hidden-student-inputs').innerHTML = '';
        document.getElementById('edit-student-feedback').textContent = '';

        const id = this.getAttribute('data-project-id');
        fetch(`projects_actions.php?action=get_project&id=${encodeURIComponent(id)}`)
          .then(r => r.json())
          .then(res => {
            if (res.status === 'success') {
              const p = res.data;
              // Llenar campos básicos
              document.getElementById('editProjectId').value = p.id || id;
              document.getElementById('editProjectTitle').value = p.title || '';
              document.getElementById('editProjectDescription').value = p.description || '';

              // Llenar la lista de autores
              const studentList = document.getElementById('edit-student-list');
              const hiddenInputsContainer = document.getElementById('edit-hidden-student-inputs');
              let addedStudents = new Set();

              if (p.student_authors_json) {
                const authors = JSON.parse(p.student_authors_json);
                if (authors && authors.length > 0) {
                  authors.forEach(author => {
                    if (!addedStudents.has(author.id)) {
                      addedStudents.add(author.id);
                      
                      const listItem = document.createElement('li');
                      listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                      listItem.innerHTML = `<span>${escapeHtml(author.name)} (${escapeHtml(author.id)})</span> <button type="button" class="btn-close" aria-label="Remove"></button>`;
                      studentList.appendChild(listItem);

                      const hiddenInput = document.createElement('input');
                      hiddenInput.type = 'hidden';
                      hiddenInput.name = 'students[]';
                      hiddenInput.value = author.id;
                      hiddenInputsContainer.appendChild(hiddenInput);

                      listItem.querySelector('.btn-close').addEventListener('click', function() {
                        addedStudents.delete(author.id);
                        listItem.remove();
                        hiddenInput.remove();
                      });
                    }
                  });
                }
              }
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

    // Lógica para añadir autores dinámicamente EN EL MODAL DE EDICIÓN
    const editAddStudentBtn = document.getElementById('editAddStudentBtn');
    const editStudentIdInput = document.getElementById('editAddStudentId');
    const editStudentList = document.getElementById('edit-student-list');
    const editHiddenInputsContainer = document.getElementById('edit-hidden-student-inputs');
    const editFeedbackDiv = document.getElementById('edit-student-feedback');
    let editAddedStudents = new Set();

    // Actualizar el Set de estudiantes cuando se abre el modal
    const editModal = document.getElementById('editProjectModal');
    editModal.addEventListener('show.bs.modal', function () {
        editAddedStudents.clear();
        editHiddenInputsContainer.querySelectorAll('input[name="students[]"]').forEach(input => {
            editAddedStudents.add(input.value);
        });
    });

    editAddStudentBtn.addEventListener('click', function() {
      const studentId = editStudentIdInput.value.trim();
      if (!studentId) {
        showEditFeedback('Por favor, introduce un ID.', 'danger');
        return;
      }
      if (editAddedStudents.has(studentId)) {
        showEditFeedback('Este usuario ya ha sido añadido.', 'warning');
        return;
      }
      fetch(`get_user_name.php?id=${studentId}`)
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            editAddedStudents.add(studentId);
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            listItem.innerHTML = `<span>${escapeHtml(data.name)} (${escapeHtml(studentId)})</span> <button type="button" class="btn-close" aria-label="Remove"></button>`;
            editStudentList.appendChild(listItem);
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'students[]';
            hiddenInput.value = studentId;
            editHiddenInputsContainer.appendChild(hiddenInput);
            listItem.querySelector('.btn-close').addEventListener('click', function() {
              editAddedStudents.delete(studentId);
              listItem.remove();
              hiddenInput.remove();
            });
            editStudentIdInput.value = '';
            showEditFeedback(`'${data.name}' añadido correctamente.`, 'success');
          } else { showEditFeedback(data.message, 'danger'); }
        }).catch(() => showEditFeedback('Error de conexión.', 'danger'));
    });
    function showEditFeedback(message, type) {
      editFeedbackDiv.textContent = message;
      editFeedbackDiv.className = `form-text text-${type}`;
    }
  });
  </script>

  <script>
  // Lógica para añadir autores dinámicamente
  document.addEventListener('DOMContentLoaded', function() {
    const addStudentBtn = document.getElementById('addStudentBtn');
    const studentIdInput = document.getElementById('addStudentId');
    const studentList = document.getElementById('student-list');
    const hiddenInputsContainer = document.getElementById('hidden-student-inputs');
    const feedbackDiv = document.getElementById('student-feedback');
    let addedStudents = new Set();

    if (!addStudentBtn) return; // Si el modal no está en la página, no hacer nada

    addStudentBtn.addEventListener('click', function() {
      const studentId = studentIdInput.value.trim();
      if (!studentId) {
        showFeedback('Por favor, introduce un ID.', 'danger');
        return;
      }

      if (addedStudents.has(studentId)) {
        showFeedback('Este usuario ya ha sido añadido.', 'warning');
        return;
      }

      fetch(`get_user_name.php?id=${studentId}`)
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            addStudentToList(studentId, data.name);
            studentIdInput.value = '';
            showFeedback(`'${data.name}' añadido correctamente.`, 'success');
          } else {
            showFeedback(data.message, 'danger');
          }
        })
        .catch(() => showFeedback('Error de conexión al buscar el usuario.', 'danger'));
    });

    function addStudentToList(id, name) {
      addedStudents.add(id);
      const listItem = document.createElement('li');
      listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
      listItem.innerHTML = `<span>${name} (${id})</span> <button type="button" class="btn-close" aria-label="Remove"></button>`;
      studentList.appendChild(listItem);

      const hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = 'students[]';
      hiddenInput.value = id;
      hiddenInputsContainer.appendChild(hiddenInput);

      listItem.querySelector('.btn-close').addEventListener('click', function() {
        addedStudents.delete(id);
        listItem.remove();
        hiddenInput.remove();
        showFeedback(`'${name}' eliminado de la lista.`, 'info');
      });
    }

    function showFeedback(message, type) {
      feedbackDiv.textContent = message;
      feedbackDiv.className = `form-text text-${type}`;
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
