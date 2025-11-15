<?php
require_once __DIR__ . '/../Servidor/db.php'; // Inicia la sesión y conecta a la BD

// Proteger la página: si no hay sesión o el perfil no es de admin, redirigir al inicio
if (!isset($_SESSION['user_id']) || $_SESSION['perfil_id'] != 1) {
    header("Location: index.html");
    exit();
}

// Lógica para mostrar mensajes de estado (éxito o error)
$status_msg = '';
if (isset($_GET['status'])) {
    $alert_type = $_GET['status'] == 'success' ? 'success' : 'danger';
    $msg_code = isset($_GET['msg']) ? $_GET['msg'] : 'operacion_desconocida';
    $messages = [
        'usuario_registrado' => 'Usuario registrado exitosamente.',
        'registro_fallido' => 'Error: No se pudo registrar el usuario.',
        'id_existente' => 'Error: El ID de usuario ya existe.',
        'faltan_campos' => 'Error: Por favor, complete todos los campos obligatorios.',
        // Puedes añadir más mensajes aquí para editar/eliminar
    ];
    $message = isset($messages[$msg_code]) ? $messages[$msg_code] : 'Ocurrió un error.';

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
  <title>Panel de Administrador - SWGPI</title>
  <meta name="description" content="Panel de administración para el Sistema de Gestión de Proyectos Integradores.">
  <meta name="keywords" content="admin, sgpi, itssmt">

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
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

</head>

<body class="news-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center">
        <i class="bi bi-buildings"></i>
        <h1 class="sitename">Gestion de Proyectos Integradores ITSSMT</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
            <li><a href="admin_welcome.php">Inicio</a></li>
          <li class="dropdown"><a href="#" class="active"><span>Usuarios</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
                 <li><a href="#" data-bs-toggle="modal" data-bs-target="#registerUserModal">Registrar Usuario</a></li>
                 <li><a href="#" onclick="document.location='admin_view.php'">Ver Usuarios</a></li>
            </ul>
          </li>
          <li class="dropdown"><a href="#"><span>Proyectos</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
                 <li><a href="projects_view.php">Ver Proyectos</a></li>
                 <li><a href="projects_view.php?action=register">Registrar Proyecto</a></li>
            </ul>
          </li>
          <li><a href="#">Herramientas</a></li>
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
              <h1 class="heading-title">Panel de Administrador</h1>
              <p class="mb-0">
                Gestión centralizada de usuarios, proyectos y herramientas del sistema.
              </p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="index.html">Inicio</a></li>
            <li class="current">Admin</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Admin Section -->
    <section id="admin-panel" class="admin-panel section">
      <div class="container">
        <!-- Contenedor para mensajes de estado -->
        <div id="status-messages"><?php echo $status_msg; ?></div>

        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2>Gestionar Usuarios</h2>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerUserModal">
            <i class="bi bi-plus-circle"></i> Registrar Nuevo Usuario
          </button>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead class="table-dark">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Fecha de Registro</th>
                    <th scope="col" class="text-center">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $sql = "SELECT id, nombres, apa, ama, telefonos, perfil_id, created_at FROM users ORDER BY created_at DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $nombre_completo = htmlspecialchars(trim($row['nombres'] . ' ' . $row['apa'] . ' ' . $row['ama']));
                            $rol_texto = 'Desconocido';
                            $rol_clase = 'bg-dark';
                            switch ($row['perfil_id']) {
                                case 1:
                                    $rol_texto = 'Administrador';
                                    $rol_clase = 'bg-primary';
                                    break;
                                case 2:
                                    $rol_texto = 'Docente';
                                    $rol_clase = 'bg-secondary';
                                    break;
                                case 3:
                                    $rol_texto = 'Estudiante';
                                    $rol_clase = 'bg-success';
                                    break;
                            }
                            echo "<tr>";
                            echo "<th scope='row'>" . htmlspecialchars($row['id']) . "</th>";
                            echo "<td data-field='nombre'>" . $nombre_completo . "</td>";
                            echo "<td data-field='telefonos'>" . htmlspecialchars($row['telefonos'] ?? 'N/A') . "</td>";
                            echo "<td><span class='badge " . $rol_clase . "'>" . $rol_texto . "</span></td>";
                            echo "<td>" . date("d/m/Y", strtotime($row['created_at'])) . "</td>";
                            echo "<td class='text-center'>
                                    <button class='btn btn-info btn-sm view-user-btn' title='Ver' data-bs-toggle='modal' data-bs-target='#viewUserModal' data-user-id='" . htmlspecialchars($row['id']) . "'><i class='bi bi-eye'></i></button>
                                    <button class='btn btn-warning btn-sm' title='Editar' data-bs-toggle='modal' data-bs-target='#editUserModal' data-user-id='" . htmlspecialchars($row['id']) . "'><i class='bi bi-pencil'></i></button>
                                    <button class='btn btn-danger btn-sm' title='Eliminar' data-bs-toggle='modal' data-bs-target='#deleteUserModal' data-user-id='" . htmlspecialchars($row['id']) . "'><i class='bi bi-trash'></i></button>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No se encontraron usuarios.</td></tr>";
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section><!-- /Admin Section -->

  </main>

  <footer id="footer" class="footer-16 footer position-relative dark-background">
    <!-- El contenido del footer puede ir aquí, similar al de index.html -->
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

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- MODALES CRUD -->

  <!-- Modal Registrar Usuario -->
  <div class="modal fade" id="registerUserModal" tabindex="-1" aria-labelledby="registerUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="registerUserModalLabel">Registrar Nuevo Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="user_actions.php">
            <input type="hidden" name="action" value="register">
            <div class="mb-3">
              <label for="registerId" class="form-label">ID (matrícula / nómina)</label>
              <input type="text" class="form-control" id="registerId" name="id" required>
            </div>
            <div class="mb-3">
              <label for="registerNombres" class="form-label">Nombres</label>
              <input type="text" class="form-control" id="registerNombres" name="nombres" required>
            </div>
            <div class="mb-3 row">
              <div class="col">
                <label for="registerApa" class="form-label">Apellido Paterno</label>
                <input type="text" class="form-control" id="registerApa" name="apa" required>
              </div>
              <div class="col">
                <label for="registerAma" class="form-label">Apellido Materno</label>
                <input type="text" class="form-control" id="registerAma" name="ama">
              </div>
            </div>
            <div class="mb-3">
              <label for="registerTelefonos" class="form-label">Teléfono</label>
              <input type="text" class="form-control" id="registerTelefonos" name="telefonos">
            </div>
            <div class="mb-3">
              <label for="registerDireccion" class="form-label">Dirección</label>
              <input type="text" class="form-control" id="registerDireccion" name="direccion">
            </div>
            <div class="mb-3">
              <label for="registerCurp" class="form-label">CURP</label>
              <input type="text" class="form-control" id="registerCurp" name="curp">
            </div>
            <div class="mb-3">
              <label for="registerPassword" class="form-label">Contraseña</label>
              <input type="password" class="form-control" id="registerPassword" name="password" required>
            </div>
            <div class="mb-3">
              <label for="registerRole" class="form-label">Rol</label>
              <select class="form-select" id="registerRole" name="perfil_id" required>
                <option value="">Seleccionar rol...</option>
                <option value="1">Administrador</option>
                <option value="2">Docente</option>
                <option value="3">Estudiante</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" formmethod="post" form="" formaction="user_actions.php" class="btn btn-primary" onclick="document.querySelector('#registerUserModal form').submit();">Registrar Usuario</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Ver Usuario -->
  <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewUserModalLabel">Detalles del Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p><strong>ID:</strong> <span id="viewId">-</span></p>
          <p><strong>Nombre:</strong> <span id="viewNombre">-</span></p>
          <p><strong>CURP:</strong> <span id="viewCurp">-</span></p>
          <p><strong>Dirección:</strong> <span id="viewDireccion">-</span></p>
          <p><strong>Teléfono:</strong> <span id="viewTelefonos">-</span></p>
          <p><strong>Rol:</strong> <span id="viewRol">-</span></p>
          <p><strong>Fecha de Registro:</strong> <span id="viewFecha">-</span></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Editar Usuario -->
  <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editUserForm" method="post" action="user_actions.php">
            <input type="hidden" name="action" value="update">
            <input type="hidden" id="editUserId" name="id">
            <div class="mb-3">
              <label for="editNombres" class="form-label">Nombres</label>
              <input type="text" class="form-control" id="editNombres" name="nombres" required>
            </div>
            <div class="mb-3 row">
              <div class="col">
                <label for="editApa" class="form-label">Apellido Paterno</label>
                <input type="text" class="form-control" id="editApa" name="apa" required>
              </div>
              <div class="col">
                <label for="editAma" class="form-label">Apellido Materno</label>
                <input type="text" class="form-control" id="editAma" name="ama">
              </div>
            </div>
            <div class="mb-3">
              <label for="editTelefonos" class="form-label">Teléfono</label>
              <input type="text" class="form-control" id="editTelefonos" name="telefonos">
            </div>
            <div class="mb-3">
              <label for="editDireccion" class="form-label">Dirección</label>
              <input type="text" class="form-control" id="editDireccion" name="direccion">
            </div>
            <div class="mb-3">
              <label for="editCurp" class="form-label">CURP</label>
              <input type="text" class="form-control" id="editCurp" name="curp">
            </div>
            <div class="mb-3">
              <label for="editPerfil" class="form-label">Rol</label>
              <select class="form-select" id="editPerfil" name="perfil_id" required>
                <option value="1">Administrador</option>
                <option value="2">Docente</option>
                <option value="3">Estudiante</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" onclick="document.getElementById('editUserForm').submit();">Guardar Cambios</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Eliminar Usuario -->
  <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteUserModalLabel">Confirmar Eliminación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>¿Estás seguro de que deseas eliminar al usuario <strong id="deleteTargetName">-</strong>? Esta acción no se puede deshacer.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <form id="deleteUserForm" method="post" action="user_actions.php">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" id="deleteUserId" name="id">
            <button type="submit" class="btn btn-danger">Eliminar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const viewUserModal = document.getElementById('viewUserModal');
    viewUserModal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      const userId = button.getAttribute('data-user-id');
      
      // Elementos del modal para rellenar
      const modalId = viewUserModal.querySelector('#viewId');
      const modalNombre = viewUserModal.querySelector('#viewNombre');
      const modalCurp = viewUserModal.querySelector('#viewCurp');
      const modalDireccion = viewUserModal.querySelector('#viewDireccion');
      const modalTelefonos = viewUserModal.querySelector('#viewTelefonos');
      const modalRol = viewUserModal.querySelector('#viewRol');
      const modalFecha = viewUserModal.querySelector('#viewFecha');

      // Petición para obtener los datos del usuario
      fetch(`user_actions.php?action=get_user&id=${userId}`)
        .then(response => response.json())
        .then(res => {
          if (res.status === 'success') {
            const user = res.data;
            const roles = { 1: 'Administrador', 2: 'Docente', 3: 'Estudiante' };
            
            modalId.textContent = user.id;
            modalNombre.textContent = `${user.nombres} ${user.apa} ${user.ama}`.trim();
            modalCurp.textContent = user.curp || 'No proporcionado';
            modalDireccion.textContent = user.direccion || 'No proporcionada';
            modalTelefonos.textContent = user.telefonos || 'No proporcionado';
            modalRol.textContent = roles[user.perfil_id] || 'Desconocido';
            
            const fecha = new Date(user.created_at);
            modalFecha.textContent = fecha.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' });

          } else {
            // Manejar error si el usuario no se encuentra
            modalNombre.textContent = 'Error al cargar los datos.';
          }
        })
        .catch(error => {
          console.error('Error:', error);
          modalNombre.textContent = 'Error de conexión.';
        });
    });
    
    // Edit modal: rellenar formulario con datos al abrir
    const editUserModal = document.getElementById('editUserModal');
    editUserModal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      const userId = button.getAttribute('data-user-id');
      if (!userId) return;
      fetch(`user_actions.php?action=get_user&id=${userId}`)
        .then(r => r.json())
        .then(res => {
          if (res.status === 'success') {
            const u = res.data;
            document.getElementById('editUserId').value = u.id;
            document.getElementById('editNombres').value = u.nombres || '';
            document.getElementById('editApa').value = u.apa || '';
            document.getElementById('editAma').value = u.ama || '';
            document.getElementById('editTelefonos').value = u.telefonos || '';
            document.getElementById('editDireccion').value = u.direccion || '';
            document.getElementById('editCurp').value = u.curp || '';
            document.getElementById('editPerfil').value = u.perfil_id || '';
          }
        }).catch(console.error);
    });

    // Delete modal: set id and show name
    const deleteUserModal = document.getElementById('deleteUserModal');
    deleteUserModal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      const userId = button.getAttribute('data-user-id');
      if (!userId) return;
      fetch(`user_actions.php?action=get_user&id=${userId}`)
        .then(r => r.json())
        .then(res => {
          if (res.status === 'success') {
            const u = res.data;
            document.getElementById('deleteUserId').value = u.id;
            document.getElementById('deleteTargetName').textContent = (u.nombres || '') + ' ' + (u.apa || '');
          }
        }).catch(console.error);
    });
  });
  </script>

</body>

</html>