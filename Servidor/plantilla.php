<?php
// Simple page wrapper adapted to main_view structure
session_start();
// Protect: require login
if (!isset($_SESSION['active'])) {
    header('Location: ../index.php');
    exit();
}

require_once __DIR__ . '/config/conexion.php';
$conexion = dbConectar();

// Load tipos (if the table exists in your schema)
$tipos = [];
$sql = "SELECT perfil_id as idtipo, perfil as tipo FROM perfiles";
$result = $conexion->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tipos[] = $row;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Cliente/css/estgen.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Sistema Web</title>
</head>
<body>
    <?php include_once(__DIR__ . '/../Cliente/include/engen.php'); ?>
    <div class='title'>
        <h2 style="background-color: rgb(33,62,122); text-align:center;">Ingeniería en Sistemas Computacionales</h2>
        <h3 style="background-color: rgb(33,62,122); text-align:center;">Bienvenid@</h3>
    </div>
    <?php include_once(__DIR__ . '/../Cliente/include/menu.php'); ?>

    <div class="container-fluid">
        <?php include_once(__DIR__ . '/../Cliente/include/pie.php'); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
