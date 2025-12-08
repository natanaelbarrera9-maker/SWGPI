<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once __DIR__ . '/../Servidor/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['perfil_id'] != 1) {
    header('Location: ../index.html');
    exit();
}

$status_msg = '';
if (isset($_GET['status'])) {
    $alert_type = $_GET['status'] == 'success' ? 'success' : 'danger';
    $msg_code = $_GET['msg'] ?? 'unknown';
    $messages = [
        'assigned' => 'Asignatura asociada al proyecto correctamente.',
        'removed' => 'Asignatura desvinculada del proyecto.',
        'error' => 'Ocurrió un error.',
        'already_assigned' => 'Esta asignatura ya está asociada al proyecto.'
    ];
    $message = $messages[$msg_code] ?? 'Operación desconocida.';
    $status_msg = "<div class='alert alert-{$alert_type} alert-dismissible fade show' role='alert'>{$message}<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
}

// Obtener todos los proyectos
$proyectos = [];
$sql_proyectos = "SELECT id, title FROM projects WHERE activo = 1 ORDER BY created_at DESC";
$result_proyectos = $conn->query($sql_proyectos);
if ($result_proyectos) {
    while ($row = $result_proyectos->fetch_assoc()) {
        $proyectos[] = $row;
    }
}

// Obtener todas las asignaturas
$asignaturas = [];
$sql_asignaturas = "SELECT id, nombre, clave FROM asignaturas ORDER BY nombre ASC";
$result_asignaturas = $conn->query($sql_asignaturas);
if ($result_asignaturas) {
    while ($row = $result_asignaturas->fetch_assoc()) {
        $asignaturas[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Asignar Asignaturas a Proyectos - SWGPI</title>
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
                <li><a href="projects_view.php">Proyectos</a></li>
                <li class="dropdown"><a href="#" class="active"><span>Configuración Académica</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                      <li><a href="subjects_view.php">Gestionar Asignaturas</a></li>
                      
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
            <div class="container"><h1 class="heading-title text-center">Asignar Asignaturas a Proyectos</h1></div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="admin_welcome.php">Admin</a></li>
                    <li class="current">Asignar Materias</li>
                </ol>
            </div>
        </nav>
    </div>

    <section id="project-subjects-panel" class="admin-panel section">
        <div class="container">
            <div id="status-messages"><?php echo $status_msg; ?></div>
            
            <div class="accordion" id="projectsAccordion">
                <?php foreach ($proyectos as $index => $proyecto): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-<?php echo $proyecto['id']; ?>">
                            <button class="accordion-button <?php echo $index > 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $proyecto['id']; ?>" aria-expanded="<?php echo $index == 0 ? 'true' : 'false'; ?>">
                                <?php echo htmlspecialchars($proyecto['title']); ?>
                            </button>
                        </h2>
                        <div id="collapse-<?php echo $proyecto['id']; ?>" class="accordion-collapse collapse <?php echo $index == 0 ? 'show' : ''; ?>" data-bs-parent="#projectsAccordion">
                            <div class="accordion-body">
                                <h5>Asignaturas Asociadas</h5>
                                <?php
                                $sql_asociadas = "SELECT a.id, a.nombre, a.clave FROM asignaturas a JOIN project_asignatura pa ON a.id = pa.asignatura_id WHERE pa.project_id = ?";
                                $stmt = $conn->prepare($sql_asociadas);
                                $stmt->bind_param("i", $proyecto['id']);
                                $stmt->execute();
                                $result_asociadas = $stmt->get_result();
                                if ($result_asociadas->num_rows > 0) {
                                    echo "<ul class='list-group list-group-flush mb-3'>";
                                    while($asignatura = $result_asociadas->fetch_assoc()) {
                                        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>" . htmlspecialchars($asignatura['nombre']) . " <span class='badge bg-secondary rounded-pill'>" . htmlspecialchars($asignatura['clave'] ?? '') . "</span>
                                            <form action='project_subject_actions.php' method='POST' style='display: inline;'>
                                                <input type='hidden' name='action' value='remove'>
                                                <input type='hidden' name='project_id' value='{$proyecto['id']}'>
                                                <input type='hidden' name='asignatura_id' value='{$asignatura['id']}'>
                                                <button type='submit' class='btn btn-danger btn-sm' title='Desvincular'><i class='bi bi-trash'></i></button>
                                            </form>
                                        </li>";
                                    }
                                    echo "</ul>";
                                } else {
                                    echo "<p class='text-muted'>No hay asignaturas asociadas a este proyecto.</p>";
                                }
                                ?>

                                <h6>Asociar Nueva Asignatura</h6>
                                <form action="project_subject_actions.php" method="POST" class="row g-3 align-items-end">
                                    <input type="hidden" name="action" value="assign">
                                    <input type="hidden" name="project_id" value="<?php echo $proyecto['id']; ?>">
                                    <div class="col-md-9">
                                        <label class="form-label">Asignatura</label>
                                        <select name="asignatura_id" class="form-select" required>
                                            <option value="">Seleccionar asignatura...</option>
                                            <?php foreach ($asignaturas as $asignatura): ?>
                                                <option value="<?php echo $asignatura['id']; ?>"><?php echo htmlspecialchars($asignatura['nombre']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary w-100">Asociar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($proyectos)): ?>
                    <div class="alert alert-info">No hay proyectos registrados para asociar asignaturas.</div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>