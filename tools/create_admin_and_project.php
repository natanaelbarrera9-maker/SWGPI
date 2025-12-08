<?php
// Script ligero para comprobar/crear usuario 'admin' y reintentar insertar proyecto
// No arranca Laravel completo para evitar problemas de bootstrap en CLI.

function env($key, $default = null) {
    static $env = null;
    if ($env === null) {
        $env = [];
        $path = __DIR__ . '/../.env';
        if (file_exists($path)) {
            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                if (!strpos($line, '=')) continue;
                [$k, $v] = explode('=', $line, 2);
                $k = trim($k);
                $v = trim($v);
                // remove surrounding quotes
                if ((str_starts_with($v, '"') && str_ends_with($v, '"')) || (str_starts_with($v, "'") && str_ends_with($v, "'"))) {
                    $v = substr($v, 1, -1);
                }
                $env[$k] = $v;
            }
        }
    }
    return $env[$key] ?? $default;
}

$dbHost = env('DB_HOST', '127.0.0.1');
$dbPort = env('DB_PORT', '3306');
$dbName = env('DB_DATABASE', 'myschool_integrator');
$dbUser = env('DB_USERNAME', 'root');
$dbPass = env('DB_PASSWORD', '');

$dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    echo "ERROR: No pude conectar a la base de datos: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

echo "Conectado a la base de datos {$dbName} en {$dbHost}:{$dbPort}\n";

// 1) Listar algunos usuarios
$stmt = $pdo->query("SELECT id, email, nombres FROM users LIMIT 10");
$users = $stmt->fetchAll();
if (!$users) {
    echo "No se encontraron usuarios en la tabla 'users'.\n";
} else {
    echo "Usuarios (hasta 10):\n";
    foreach ($users as $u) {
        printf(" - id=%s, email=%s, nombres=%s\n", $u['id'], $u['email'] ?? '(null)', $u['nombres'] ?? '(null)');
    }
}

// Check if 'admin' id exists
$adminId = 'admin';
$stmt = $pdo->prepare('SELECT id FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$adminId]);
$found = $stmt->fetchColumn();

if ($found) {
    echo "Usuario con id='{$adminId}' ya existe.\n";
} else {
    echo "Usuario 'admin' no existe. Lo crearé ahora...\n";
    // Insert with minimal required fields based on migration
    $passwordPlain = 'Admin1234!';
    // Use password_hash bcrypt
    $passwordHash = password_hash($passwordPlain, PASSWORD_BCRYPT);

    $insertStmt = $pdo->prepare('INSERT INTO users (id, password, nombres, email, perfil_id, created_at, activo) VALUES (?, ?, ?, ?, ?, NOW(), ?)');
    try {
        $insertStmt->execute([$adminId, $passwordHash, 'Administrador', 'admin@local.local', 1, 1]);
        echo "Usuario 'admin' creado con password '{$passwordPlain}'. Cambia la contraseña al entrar.\n";
    } catch (Exception $e) {
        echo "ERROR al crear usuario 'admin': " . $e->getMessage() . PHP_EOL;
        exit(1);
    }
}

// 3) Intentar insertar proyecto con created_by = 'admin'
try {
    $projStmt = $pdo->prepare('INSERT INTO projects (title, description, created_by, created_at) VALUES (?, ?, ?, NOW())');
    $projStmt->execute(['Sistema web para la gestion de ventas', 'Sistema web y app movil', $adminId]);
    echo "Proyecto insertado exitosamente con created_by = '{$adminId}'\n";
} catch (Exception $e) {
    echo "ERROR al insertar proyecto: " . $e->getMessage() . PHP_EOL;
    // Mostrar estado de foreign key en tabla projects para diagnostico
    try {
        $fkStmt = $pdo->query("SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = '".addslashes($dbName)."' AND TABLE_NAME = 'projects' AND REFERENCED_TABLE_NAME IS NOT NULL");
        $fks = $fkStmt->fetchAll();
        if ($fks) {
            echo "Foreign keys en 'projects':\n";
            foreach ($fks as $fk) {
                printf(" - %s: %s -> %s(%s)\n", $fk['CONSTRAINT_NAME'], $fk['COLUMN_NAME'], $fk['REFERENCED_TABLE_NAME'], $fk['REFERENCED_COLUMN_NAME']);
            }
        }
    } catch (Exception $ex) {
        // ignore
    }
    exit(1);
}

exit(0);
