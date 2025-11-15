<?php
// Archivo de conexión reutilizable para main_view
// Usa el archivo config.ini en la misma carpeta para obtener credenciales

function dbConectar()
{
    static $conexion = null;

    if ($conexion === null) {
        $configFile = __DIR__ . DIRECTORY_SEPARATOR . 'config.ini';
        if (!file_exists($configFile)) {
            error_log("Archivo de configuración no encontrado: $configFile");
            return false;
        }

        $config = parse_ini_file($configFile, true);
        if ($config === false || !isset($config['database'])) {
            error_log("No se pudo leer la configuración de la base de datos desde: $configFile");
            return false;
        }

        $db = $config['database'];
        $servidor = trim($db['servidor'], " \t\n\r\0\x0B\'\"");
        $usuario  = trim($db['usuario'], " \t\n\r\0\x0B\'\"");
        $pass     = trim($db['pass'], " \t\n\r\0\x0B\'\"");
        $bbdd     = trim($db['bbdd'], " \t\n\r\0\x0B\'\"");
        $puerto   = isset($db['puerto']) ? (int) trim($db['puerto'], " \t\n\r\0\x0B\'\"") : null;

        if ($puerto) {
            $conexion = new mysqli($servidor, $usuario, $pass, $bbdd, $puerto);
        } else {
            $conexion = new mysqli($servidor, $usuario, $pass, $bbdd);
        }

        if ($conexion->connect_error) {
            error_log("Error de conexión: " . $conexion->connect_error);
            return false;
        }

        // Establecer charset
        $conexion->set_charset('utf8mb4');
    }

    return $conexion;
}

?>