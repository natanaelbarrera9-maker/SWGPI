<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once __DIR__ . '/../Servidor/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['perfil_id'] != 1) {
    header("Location: index.html");
    exit();
}

$status_msg = '';
if (isset($_GET['status'])) {
    $alert_type = $_GET['status'] == 'success' ? 'success' : 'danger';
    $msg_code = $_GET['msg'] ?? 'operacion_desconocida';
    $messages = [
        'usuario_reactivado' => 'Usuario reactivado exitosamente.',
        'operacion_fallida' => 'La operación falló. Por favor, inténtalo de nuevo.',
        'error' => 'Ocurrió un error.',
    ];
    $message = $messages[$msg_code] ?? 'Acción desconocida.';

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
  <title>Usuarios Inactivos - SWGPI</title>
  <link href="assets/img/ITSSMT/ITSSMT.png" rel="icon">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
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
          <li><a href="admin_view.php" class="active">Usuarios</a></li>
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

    <div class="page-title">
      <div class="heading">
        <div class="container">
          <h1 class="heading-title text-center">Usuarios Inactivos</h1>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="admin_welcome.php">Admin</a></li>
            <li><a href="admin_view.php">Usuarios</a></li>
            <li class="current">Inactivos</li>
          </ol>
        </div>
      </nav>
    </div>

    <section id="admin-panel" class="admin-panel section">
      <div class="container">
        <div id="status-messages"><?php echo $status_msg; ?></div>

        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2>Usuarios para Reactivar</h2>
          <a href="admin_view.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Volver a Usuarios Activos
          </a>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead class="table-dark">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Correo Electrónico</th>
                    <th scope="col">Rol</th>
                    <th scope="col" class="text-center">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $sql = "SELECT id, nombres, apa, ama, email, perfil_id FROM users WHERE activo = 0 ORDER BY nombres ASC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $nombre_completo = htmlspecialchars(trim($row['nombres'] . ' ' . $row['apa'] . ' ' . $row['ama']));
                            $rol_texto = 'Desconocido';
                            switch ($row['perfil_id']) {
                                case 1: $rol_texto = 'Administrador'; break;
                                case 2: $rol_texto = 'Docente'; break;
                                case 3: $rol_texto = 'Estudiante'; break;
                            }
                            echo "<tr>";
                            echo "<th scope='row'>" . htmlspecialchars($row['id']) . "</th>";
                            echo "<td>" . $nombre_completo . "</td>";
                            echo "<td>" . htmlspecialchars($row['email'] ?? 'N/A') . "</td>";
                            echo "<td>" . $rol_texto . "</td>";
                            echo "<td class='text-center'>
                                    <form method='post' action='user_actions.php' class='d-inline'>
                                        <input type='hidden' name='action' value='reactivate'>
                                        <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                                        <button type='submit' class='btn btn-success btn-sm' title='Reactivar Usuario'><i class='bi bi-check-circle'></i> Reactivar</button>
                                    </form>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No se encontraron usuarios inactivos.</td></tr>";
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

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

</body>
</html>