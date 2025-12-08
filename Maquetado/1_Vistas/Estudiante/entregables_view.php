<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once __DIR__ . '/../Servidor/db.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['perfil_id'], [1, 2 , 3])) {
  header('Location: index.html?error=unauthorized');
  exit();
}

$estudiante_id = $_SESSION['user_id'];
$proyecto = null;

// Obtener el proyecto del estudiante
$stmt_proyecto = $conn->prepare("SELECT p.id, p.title FROM projects p JOIN project_user pu ON p.id = pu.project_id WHERE pu.user_id = ? LIMIT 1");
$stmt_proyecto->bind_param("s", $estudiante_id);
$stmt_proyecto->execute();
$result_proyecto = $stmt_proyecto->get_result();
if ($result_proyecto->num_rows > 0) {
    $proyecto = $result_proyecto->fetch_assoc();
} else {
    // Si no tiene proyecto, no puede ver entregables.
    header("Location: estudiante_view.php");
    exit();
}

$status_msg = '';
if (isset($_GET['status'])) {
    $alert_type = $_GET['status'] == 'success' ? 'success' : 'danger';
    $msg_code = $_GET['msg'] ?? 'unknown';
    $messages = [
        'entrega_exitosa' => 'Archivo subido exitosamente.',
        'error_subida' => 'Hubo un error al subir el archivo.',
        'tipo_invalido' => 'Tipo de archivo no permitido.',
        'archivo_grande' => 'El archivo es demasiado grande.',
        'error_db' => 'Error al registrar la entrega en la base de datos.',
    ];
    $message = $messages[$msg_code] ?? 'Ocurrió un error.';
    $status_msg = "<div class='alert alert-{$alert_type} alert-dismissible fade show' role='alert'>{$message}<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Mis Entregables - SWGPI</title>
    <link href="assets/img/ITSSMT/ITSSMT.png" rel="icon">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">
</head>
<body class="news-page">

<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
        <a href="estudiante_view.php" class="logo d-flex align-items-center">
            <i class="bi bi-buildings"></i>
            <h1 class="sitename">Gestion de Proyectos Integradores ITSSMT</h1>
        </a>
        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="estudiante_view.php">Mi Proyecto</a></li>
                <li><a href="entregables_view.php" class="active">Mis Entregables</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
    </div>
</header>

<main class="main">
    <div class="page-title">
        <div class="heading">
            <div class="container"><h1 class="heading-title text-center">Mis Entregables</h1></div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="estudiante_view.php">Estudiante</a></li>
                    <li class="current">Entregables</li>
                </ol>
            </div>
        </nav>
    </div>

    <section id="entregables-section" class="admin-panel section">
        <div class="container">
            <div id="status-messages"><?php echo $status_msg; ?></div>
            
            <?php
            $sql_asignaturas = "SELECT a.id, a.nombre FROM asignaturas a JOIN project_asignatura pa ON a.id = pa.asignatura_id WHERE pa.project_id = ?";
            $stmt_asignaturas = $conn->prepare($sql_asignaturas);
            $stmt_asignaturas->bind_param("i", $proyecto['id']);
            $stmt_asignaturas->execute();
            $result_asignaturas = $stmt_asignaturas->get_result();

            if ($result_asignaturas->num_rows == 0) {
                echo "<div class='alert alert-info'>Tu proyecto no tiene asignaturas asociadas.</div>";
            }
            ?>

            <div class="accordion" id="asignaturasAccordion">
                <?php while($asignatura = $result_asignaturas->fetch_assoc()): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-asig-<?php echo $asignatura['id']; ?>">
                                <?php echo htmlspecialchars($asignatura['nombre']); ?>
                            </button>
                        </h2>
                        <div id="collapse-asig-<?php echo $asignatura['id']; ?>" class="accordion-collapse collapse" data-bs-parent="#asignaturasAccordion">
                            <div class="accordion-body">
                                <?php
                                $stmt_competencias = $conn->prepare("SELECT * FROM competencias WHERE asignatura_id = ? ORDER BY fecha_inicio ASC");
                                $stmt_competencias->bind_param("i", $asignatura['id']);
                                $stmt_competencias->execute();
                                $result_competencias = $stmt_competencias->get_result();
                                if ($result_competencias->num_rows == 0) {
                                    echo "<p class='text-muted'>No hay competencias definidas para esta asignatura.</p>";
                                }
                                ?>
                                <?php while($competencia = $result_competencias->fetch_assoc()): ?>
                                    <h5><?php echo htmlspecialchars($competencia['nombre']); ?></h5>
                                    <ul class="list-group mb-4">
                                        <?php
                                        $stmt_entregables = $conn->prepare(
                                            "SELECT e.*, ee.nombre_archivo, ee.fecha_entrega 
                                             FROM entregables e
                                             LEFT JOIN entregas_estudiantes ee ON e.id = ee.entregable_id AND ee.user_id = ? AND ee.project_id = ?
                                             WHERE e.competencia_id = ? ORDER BY e.fecha_limite ASC"
                                        );
                                        $stmt_entregables->bind_param("sii", $estudiante_id, $proyecto['id'], $competencia['id']);
                                        $stmt_entregables->execute();
                                        $result_entregables = $stmt_entregables->get_result();
                                        if ($result_entregables->num_rows == 0) {
                                            echo "<li class='list-group-item text-muted'>No hay entregables para esta competencia.</li>";
                                        }
                                        ?>
                                        <?php while($entregable = $result_entregables->fetch_assoc()): 
                                            $is_entregado = !empty($entregable['nombre_archivo']);
                                            $is_vencido = new DateTime() > new DateTime($entregable['fecha_limite']);
                                            $formatos_permitidos = !empty($entregable['formatos_aceptados']) ? '.' . str_replace(',', ',.', $entregable['formatos_aceptados']) : '';
                                            $status_badge = '';
                                            if ($is_entregado) {
                                                $status_badge = "<span class='badge bg-success'>Entregado</span>";
                                            } elseif ($is_vencido) {
                                                $status_badge = "<span class='badge bg-danger'>Vencido</span>";
                                            } else {
                                                $status_badge = "<span class='badge bg-warning text-dark'>Pendiente</span>";
                                            }
                                        ?>
                                            <li class="list-group-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($entregable['nombre']); ?></strong> <?php echo $status_badge; ?><br>
                                                        <small class="text-muted">Fecha Límite: <?php echo date('d/m/Y h:i A', strtotime($entregable['fecha_limite'])); ?></small>
                                                        <?php if($is_entregado): ?>
                                                            <br><small>Entregado el: <?php echo date('d/m/Y h:i A', strtotime($entregable['fecha_entrega'])); ?> - <a href="uploads/entregas/<?php echo htmlspecialchars($entregable['nombre_archivo']); ?>" target="_blank">Ver archivo</a></small>
                                                        <?php endif; ?>
                                                    </div>
                                                    <form action="entregable_actions.php" method="POST" enctype="multipart/form-data" class="d-inline-flex align-items-center">
                                                        <input type="hidden" name="action" value="submit_entregable">
                                                        <input type="hidden" name="entregable_id" value="<?php echo $entregable['id']; ?>">
                                                        <input type="hidden" name="project_id" value="<?php echo $proyecto['id']; ?>">
                                                        <input type="file" name="archivo_entrega" class="form-control form-control-sm me-2" required <?php if($is_vencido && !$is_entregado) echo 'disabled'; ?> accept="<?php echo $formatos_permitidos; ?>">
                                                        <button type="submit" class="btn btn-sm <?php echo $is_entregado ? 'btn-secondary' : 'btn-primary'; ?>" <?php if($is_vencido && !$is_entregado) echo 'disabled'; ?>>
                                                            <?php echo $is_entregado ? 'Reemplazar' : 'Subir'; ?>
                                                        </button>
                                                    </form>
                                                </div>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
</main>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>