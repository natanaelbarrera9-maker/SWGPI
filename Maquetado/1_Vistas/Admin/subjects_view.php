<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once __DIR__ . '/../Servidor/db.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['perfil_id'], [1, 2])) { // Admin o Docente
    header('Location: index.html?error=unauthorized');
    exit();
}

$status_msg = '';
if (isset($_GET['status'])) {
    $alert_type = $_GET['status'] == 'success' ? 'success' : 'danger';
    $msg_code = $_GET['msg'] ?? 'unknown';
    $messages = [
        'created' => 'Asignatura creada exitosamente.',
        'updated' => 'Asignatura actualizada exitosamente.',
        'deleted' => 'Asignatura eliminada exitosamente.',
        'deactivated' => 'Asignatura desactivada exitosamente.',
        'error' => 'Ocurrió un error.',
    ];
    $message = $messages[$msg_code] ?? 'Operación desconocida.';
    $status_msg = "<div class='alert alert-{$alert_type} alert-dismissible fade show' role='alert'>{$message}<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Gestión de Asignaturas - SWGPI</title>
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
                <li><a href="admin_view.php">Usuarios</a></li>
                <li><a href="advisors_view.php">Asesores</a></li>
                <li><a href="projects_view.php">Proyectos</a></li>
                <li class="dropdown"><a href="#" class="active"><span>Configuración Académica</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                      
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
                <h1 class="heading-title text-center">Gestión de Asignaturas</h1>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="admin_welcome.php">Admin</a></li>
                    <li class="current">Asignaturas</li>
                </ol>
            </div>
        </nav>
    </div>

    <section id="subjects-panel" class="admin-panel section">
        <div class="container">
            <div id="status-messages"><?php echo $status_msg; ?></div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Lista de Asignaturas</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                    <i class="bi bi-plus-circle"></i> Nueva Asignatura
                </button>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Clave</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT id, nombre, clave FROM asignaturas ORDER BY nombre ASC";
                                $result = $conn->query($sql);
                                if ($result && $result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['clave'] ?? '') . "</td>";
                                        echo "<td class='text-center' data-subject-id='" . $row['id'] . "'>
                                                <a href='gestionar_competencias.php?asignatura_id=" . $row['id'] . "' class='btn btn-success btn-sm' title='Gestionar Competencias'><i class='bi bi-card-checklist'></i></a>
                                                <button class='btn btn-warning btn-sm' title='Editar'><i class='bi bi-pencil'></i></button>
                                                <button class='btn btn-danger btn-sm' title='Eliminar'><i class='bi bi-trash'></i></button>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>No hay asignaturas registradas.</td></tr>";
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

<!-- Modal Añadir Asignatura -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubjectModalLabel">Añadir Nueva Asignatura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addSubjectForm" method="post" action="subject_actions.php">
                    <input type="hidden" name="action" value="create">
                    <div class="mb-3">
                        <label for="subjectName" class="form-label">Nombre de la Asignatura</label>
                        <input type="text" class="form-control" id="subjectName" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="subjectKey" class="form-label">Clave (Opcional)</label>
                        <input type="text" class="form-control" id="subjectKey" name="clave">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" form="addSubjectForm" class="btn btn-primary">Guardar Asignatura</button>
            </div>
        </div>
    </div>
</div>

<!-- Formulario oculto para la desactivación -->
<form id="deactivateSubjectForm" method="post" action="subject_actions.php" style="display: none;">
    <input type="hidden" name="action" value="deactivate">
    <input type="hidden" name="subject_id" id="deactivateSubjectId">
</form>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('.table');
    if (table) {
        table.addEventListener('click', function (e) {
            // Buscamos el botón de eliminar que fue presionado
            const deleteButton = e.target.closest('.btn-danger');
            if (deleteButton) {
                e.preventDefault();
                
                // Obtenemos el ID de la asignatura desde el atributo data en la fila <td>
                const subjectId = deleteButton.closest('td').dataset.subjectId;
                
                if (confirm('¿Estás seguro de que quieres desactivar esta asignatura? No se borrará permanentemente.')) {
                    // Asignamos el ID al formulario oculto y lo enviamos
                    const form = document.getElementById('deactivateSubjectForm');
                    const subjectIdInput = document.getElementById('deactivateSubjectId');
                    
                    subjectIdInput.value = subjectId;
                    form.submit();
                }
            }
        });
    }
});
</script>

</body>
</html>