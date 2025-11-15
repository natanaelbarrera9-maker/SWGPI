<?php
// Export users as CSV adapted to main_view schema
require_once __DIR__ . '/config/conexion.php';
$conexion = dbConectar();

header('Content-Type: text/csv; charset=latin1');
header('Content-Disposition: attachment; filename="ReporteUsuarios.csv"');

$salida = fopen('php://output', 'w');

fputcsv($salida, array('REPORTE DE USUARIOS EXISTENTES'));
fputcsv($salida, array());
fputcsv($salida, array('Nombre', 'Apellido Paterno', 'Apellido Materno', 'Telefono'));

$consulta = "SELECT nombres, apa, ama, telefonos FROM users";
$resultado = $conexion->query($consulta);

while ($fila = $resultado->fetch_assoc()) {
    fputcsv($salida, array(
        utf8_decode($fila['nombres']),
        utf8_decode($fila['apa']),
        utf8_decode($fila['ama']),
        utf8_decode($fila['telefonos'])
    ));
}

fclose($salida);
?>
