<?php
/**
 * Validador centralizado de autenticación
 * Soporta contraseñas hasheadas (bcrypt) y en texto plano
 */

class AuthValidator {
    private static $config = null;

    /**
     * Inicializar config desde JSON
     */
    public static function init($configJsonPath = null) {
        if (self::$config !== null) {
            return self::$config;
        }

        if ($configJsonPath === null) {
            $configJsonPath = __DIR__ . '/config.json';
        }

        if (!file_exists($configJsonPath)) {
            error_log("Config JSON not found: $configJsonPath");
            return null;
        }

        $json = file_get_contents($configJsonPath);
        self::$config = json_decode($json, true);

        if (self::$config === null) {
            error_log("Failed to parse JSON config: " . json_last_error_msg());
            return null;
        }

        return self::$config;
    }

    /**
     * Validar contraseña contra valor almacenado
     * Soporta tanto bcrypt como texto plano
     */
    public static function validatePassword($rawPassword, $storedPassword) {
        // Si almacenada empieza con $2y$ o $2b$ es bcrypt
        if (strpos($storedPassword, '$2y$') === 0 || strpos($storedPassword, '$2b$') === 0 || strpos($storedPassword, '$2a$') === 0) {
            // Es hash bcrypt
            if (password_verify($rawPassword, $storedPassword)) {
                return ['valid' => true, 'method' => 'bcrypt'];
            }
            return ['valid' => false, 'method' => 'bcrypt'];
        }

        // Intentar comparación directa (texto plano)
        // NOTA: Esto es para compatibilidad, idealmente migrar a bcrypt
        if ($rawPassword === $storedPassword) {
            return ['valid' => true, 'method' => 'plaintext'];
        }

        return ['valid' => false, 'method' => 'unknown'];
    }

    /**
     * Hash una contraseña usando bcrypt
     */
    public static function hashPassword($password) {
        $config = self::init();
        $options = ['cost' => $config['auth']['hash_cost'] ?? 10];
        return password_hash($password, PASSWORD_DEFAULT, $options);
    }

    /**
     * Obtener config
     */
    public static function getConfig() {
        if (self::$config === null) {
            self::init();
        }
        return self::$config;
    }
}

// Inicializar automáticamente al incluir este archivo
AuthValidator::init();

?>
