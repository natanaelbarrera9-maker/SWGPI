<?php
require_once __DIR__ . '/../Servidor/db.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['perfil_id'], [1, 2])) { // Admin o Docente
    header('Location: index.html?error=unauthorized');
    exit();
}

$docente_id = $_SESSION['user_id'];
$perfil_id = $_SESSION['perfil_id'];

// Si es admin, obtiene todos los proyectos. Si es docente, solo los suyos.
if ($perfil_id == 1) {
    $sql_proyectos = "SELECT id, title FROM projects ORDER BY created_at DESC";
    $stmt_proyectos = $conn->prepare($sql_proyectos);
} else {
    $sql_proyectos = "SELECT p.id, p.title FROM projects p JOIN project_user pu ON p.id = pu.project_id WHERE pu.user_id = ? ORDER BY p.created_at DESC";
    $stmt_proyectos = $conn->prepare($sql_proyectos);
    $stmt_proyectos->bind_param("s", $docente_id);
}
$stmt_proyectos->execute();
$result_proyectos = $stmt_proyectos->get_result();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Revisión de Entregables - SWGPI</title>
    <link href="assets/img/ITSSMT/ITSSMT.png" rel="icon">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">
</head>
<body class="news-page">

<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
        <a href="<?php echo $perfil_id == 1 ? 'admin_welcome.php' : 'docente_view.php'; ?>" class="logo d-flex align-items-center">
            <i class="bi bi-buildings"></i>
            <h1 class="sitename">Gestion de Proyectos Integradores ITSSMT</h1>
        </a>
        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="<?php echo $perfil_id == 1 ? 'admin_welcome.php' : 'docente_view.php'; ?>">Inicio</a></li>
                <li class="dropdown"><a href="#" class="active"><span>Gestión Académica</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
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
    <div class="page-title">
        <div class="heading">
            <div class="container"><h1 class="heading-title text-center">Revisión de Entregables</h1></div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="<?php echo $perfil_id == 1 ? 'admin_welcome.php' : 'docente_view.php'; ?>">Inicio</a></li>
                    <li class="current">Revisión de Entregables</li>
                </ol>
            </div>
        </nav>
    </div>

    <section id="revision-panel" class="admin-panel section">
        <div class="container">
            <div class="accordion" id="proyectosAccordion">
                <?php while($proyecto = $result_proyectos->fetch_assoc()): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-proj-<?php echo $proyecto['id']; ?>">
                            Proyecto: <?php echo htmlspecialchars($proyecto['title']); ?>
                        </button>
                    </h2>
                    <div id="collapse-proj-<?php echo $proyecto['id']; ?>" class="accordion-collapse collapse" data-bs-parent="#proyectosAccordion">
                        <div class="accordion-body">
                            <?php
                            // Obtener asignaturas del proyecto
                            $stmt_asig = $conn->prepare("SELECT a.id, a.nombre FROM asignaturas a JOIN project_asignatura pa ON a.id = pa.asignatura_id WHERE pa.project_id = ?");
                            $stmt_asig->bind_param("i", $proyecto['id']);
                            $stmt_asig->execute();
                            $result_asig = $stmt_asig->get_result();
                            ?>
                            <div class="accordion" id="asignaturasAccordion-<?php echo $proyecto['id']; ?>">
                                <?php while($asignatura = $result_asig->fetch_assoc()): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-asig-<?php echo $proyecto['id']; ?>-<?php echo $asignatura['id']; ?>">
                                            Asignatura: <?php echo htmlspecialchars($asignatura['nombre']); ?>
                                        </button>
                                    </h2>
                                    <div id="collapse-asig-<?php echo $proyecto['id']; ?>-<?php echo $asignatura['id']; ?>" class="accordion-collapse collapse" data-bs-parent="#asignaturasAccordion-<?php echo $proyecto['id']; ?>">
                                        <div class="accordion-body">
                                            <?php
                                            // Obtener competencias de la asignatura
                                            $stmt_comp = $conn->prepare("SELECT * FROM competencias WHERE asignatura_id = ? ORDER BY fecha_inicio ASC");
                                            $stmt_comp->bind_param("i", $asignatura['id']);
                                            $stmt_comp->execute();
                                            $result_comp = $stmt_comp->get_result();
                                            ?>
                                            <?php while($competencia = $result_comp->fetch_assoc()): ?>
                                                <h5>Competencia: <?php echo htmlspecialchars($competencia['nombre']); ?></h5>
                                                <table class="table table-sm table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Entregable</th>
                                                            <th>Estudiante</th>
                                                            <th>Fecha de Entrega</th>
                                                            <th>Archivo</th>
                                                            <th>Calificación</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    // Obtener entregables y las entregas de los estudiantes para este proyecto
                                                    $sql_entregas = "SELECT 
                                                                        e.nombre as entregable_nombre,
                                                                        u.nombres, u.apa,
                                                                        ee.fecha_entrega, ee.nombre_archivo, ee.calificacion, ee.id as entrega_id
                                                                    FROM entregables e
                                                                    JOIN entregas_estudiantes ee ON e.id = ee.entregable_id
                                                                    JOIN users u ON ee.user_id = u.id
                                                                    WHERE e.competencia_id = ? AND ee.project_id = ? AND u.activo = 1 AND ee.activo = 1
                                                                    ORDER BY e.fecha_limite, u.apa";
                                                    $stmt_entregas = $conn->prepare($sql_entregas);
                                                    $stmt_entregas->bind_param("ii", $competencia['id'], $proyecto['id']);
                                                    $stmt_entregas->execute();
                                                    $result_entregas = $stmt_entregas->get_result();
                                                    if ($result_entregas->num_rows > 0):
                                                        while($entrega = $result_entregas->fetch_assoc()):
                                                    ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($entrega['entregable_nombre']); ?></td>
                                                            <td><?php echo htmlspecialchars($entrega['nombres'] . ' ' . $entrega['apa']); ?></td>
                                                            <td><?php echo date('d/m/Y h:i A', strtotime($entrega['fecha_entrega'])); ?></td>
                                                            <td><a href="uploads/entregas/<?php echo htmlspecialchars($entrega['nombre_archivo']); ?>" target="_blank" class="btn btn-link btn-sm">Ver Archivo</a></td>
                                                            <td>
                                                                <form action="calificar_action.php" method="POST" class="d-flex">
                                                                    <input type="hidden" name="entrega_id" value="<?php echo $entrega['entrega_id']; ?>">
                                                                    <input type="number" name="calificacion" class="form-control form-control-sm" value="<?php echo htmlspecialchars($entrega['calificacion']); ?>" step="0.1" min="0" max="100" style="width: 80px;">
                                                            </td>
                                                            <td>
                                                                    <button type="submit" class="btn btn-success btn-sm ms-2">Calificar</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    <?php 
                                                        endwhile;
                                                    else:
                                                        echo "<tr><td colspan='6' class='text-center text-muted'>No hay entregas para esta competencia en este proyecto.</td></tr>";
                                                    endif;
                                                    $stmt_entregas->close();
                                                    ?>
                                                    </tbody>
                                                </table>
                                            <?php endwhile; $stmt_comp->close(); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; $stmt_asig->close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; $stmt_proyectos->close(); ?>
            </div>
        </div>
    </section>
</main>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>