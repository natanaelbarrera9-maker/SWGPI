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
        'assigned' => 'Asesor asignado correctamente.',
        'updated' => 'Rol de asesor actualizado.',
        'removed' => 'Asesor desvinculado del proyecto.',
        'error' => 'Ocurrió un error.',
        'already_assigned_no_role' => 'Este asesor ya está asignado al proyecto.',
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

// Obtener todos los docentes
$docentes = [];
$sql_docentes = "SELECT id, CONCAT(nombres, ' ', apa, ' ', ama) AS nombre_completo FROM users WHERE perfil_id = 2 ORDER BY nombres ASC";
$result_docentes = $conn->query($sql_docentes);
if ($result_docentes) {
    while ($row = $result_docentes->fetch_assoc()) {
        $docentes[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Gestión de Asesores - SWGPI</title>
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
            <div class="container"><h1 class="heading-title text-center">Gestión de Asesores</h1></div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="admin_welcome.php">Admin</a></li>
                    <li class="current">Asesores</li>
                </ol>
            </div>
        </nav>
    </div>

    <section id="advisors-panel" class="admin-panel section">
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
                                <h5>Asesores Asignados</h5>
                                <?php
                                $sql_asesores = "SELECT u.id, CONCAT(u.nombres, ' ', u.apa) as nombre, pu.rol_asesor FROM users u JOIN project_user pu ON u.id = pu.user_id WHERE pu.project_id = ? AND u.perfil_id = 2";
                                $stmt = $conn->prepare($sql_asesores);
                                $stmt->bind_param("i", $proyecto['id']);
                                $stmt->execute();
                                $result_asesores = $stmt->get_result();
                                if ($result_asesores->num_rows > 0) {
                                    echo "<ul class='list-group list-group-flush mb-3'>";
                                    while($asesor = $result_asesores->fetch_assoc()) {
                                        $rol = isset($asesor['rol_asesor']) ? ucfirst($asesor['rol_asesor']) : 'Asignado';
                                        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>" . htmlspecialchars($asesor['nombre']) . " <span class='badge bg-info rounded-pill'>{$rol}</span></li>";
                                    }
                                    echo "</ul>";
                                } else {
                                    echo "<p class='text-muted'>No hay asesores asignados a este proyecto.</p>";
                                }
                                ?>

                                <h6>Asignar Nuevo Asesor</h6>
                                <form action="advisor_actions.php" method="POST" class="row g-3 align-items-end">
                                    <input type="hidden" name="action" value="assign">
                                    <input type="hidden" name="project_id" value="<?php echo $proyecto['id']; ?>">
                                    <div class="col-md-5">
                                        <label class="form-label">Docente</label>
                                        <select name="user_id" class="form-select" required>
                                            <option value="">Seleccionar docente...</option>
                                            <?php foreach ($docentes as $docente): ?>
                                                <option value="<?php echo $docente['id']; ?>"><?php echo htmlspecialchars($docente['nombre_completo']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Rol de Asesor</label>
                                        <select name="rol_asesor" class="form-select" required>
                                            <option value="primario">Asesor Primario</option>
                                            <option value="secundario">Asesor Secundario</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary w-100">Asignar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($proyectos)): ?>
                    <div class="alert alert-info">No hay proyectos registrados para asignar asesores.</div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>