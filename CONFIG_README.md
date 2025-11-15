# Configuración de main_view - SWGPI ITSSMT

## Estructura

```
main_view/
├── Cliente/
│   ├── login.php
│   ├── admin_view.php
│   ├── docente_view.php
│   ├── estudiante_view.php
│   ├── user_actions.php
│   ├── assets/
│   └── ...
└── Servidor/
    ├── db.php
    └── config/
        ├── config.ini (legacy, para compatibilidad)
        ├── config.json (NUEVO - configuración centralizada)
        ├── conexion.php (conexión a BD con soporte puerto)
        └── AuthValidator.php (NUEVO - validador centralizado de autenticación)
```

## Cambios Realizados

### 1. Base de Datos
- **Ubicación**: Puerto 3306 (servidor local)
- **Base de datos**: `myschool_integrator`
- **Tabla**: `users` con estructura compatible con bcrypt

### 2. Configuración Centralizada (config.json)

El archivo `main_view/Servidor/config/config.json` centraliza:
- Credenciales de base de datos (servidor, usuario, contraseña, puerto)
- Configuración de autenticación (validación flexible de contraseñas)
- Configuración de la aplicación

```json
{
  "database": {
    "servidor": "localhost",
    "usuario": "root",
    "pass": "",
    "bbdd": "myschool_integrator",
    "puerto": 3306,
    "charset": "utf8mb4"
  },
  "auth": {
    "password_validation": "flexible",
    "allow_plaintext": true,
    "allow_bcrypt": true,
    "hash_algo": "PASSWORD_DEFAULT",
    "hash_cost": 10
  }
}
```

### 3. Validación Centralizada de Credenciales (AuthValidator.php)

La clase `AuthValidator` proporciona:

#### Métodos Disponibles

- **`init($configJsonPath = null)`** - Inicializa la configuración desde JSON
  
- **`validatePassword($rawPassword, $storedPassword)`** - Valida contraseña contra almacenada
  - Soporta bcrypt (formato $2y$, $2b$, $2a$)
  - Soporta texto plano (para compatibilidad)
  - Retorna array con `['valid' => bool, 'method' => 'bcrypt|plaintext|unknown']`

- **`hashPassword($password)`** - Genera hash bcrypt de contraseña
  - Usa `PASSWORD_DEFAULT` con cost configurado
  - Retorna string con hash bcrypt

- **`getConfig()`** - Retorna configuración actual

#### Ejemplo de Uso

```php
require_once __DIR__ . '/../Servidor/config/AuthValidator.php';

// Validar contraseña
$result = AuthValidator::validatePassword($inputPassword, $dbPassword);
if ($result['valid']) {
    // Login exitoso
} else {
    // Login fallido
}

// Generar hash al registrar usuario
$hashed = AuthValidator::hashPassword($newPassword);
```

### 4. Autenticación (login.php)

El archivo `main_view/Cliente/login.php` ahora:
- Incluye `AuthValidator.php`
- Usa `AuthValidator::validatePassword()` para validar credenciales
- Soporta tanto contraseñas en texto plano como hasheadas
- Redirige según el rol del usuario (admin, docente, estudiante)

### 5. Registro de Usuarios (user_actions.php)

El archivo `main_view/Cliente/user_actions.php` ahora:
- Incluye `AuthValidator.php`
- Usa `AuthValidator::hashPassword()` para generar hashes seguros
- Almacena contraseñas hasheadas con bcrypt en la BD

## Credenciales de Prueba

Tras ejecutar `setup_test_users.php`, los siguientes usuarios están disponibles:

| ID | Rol | Contraseña |
|---|---|---|
| 0000000001 | Administrador | admin123 |
| 0000000002 | Docente | docente123 |
| 0000000003 | Estudiante | estudiante123 |
| 22240022 | Administrador | natanel123 |

## Archivos de Prueba

- **`test_db_main.php`** - Prueba conexión a BD
- **`conn_test_ports.php`** - Prueba puertos 3306 y 3307
- **`inspect_users_table.php`** - Inspecciona estructura tabla users
- **`test_auth_validator.php`** - Prueba el validador
- **`setup_test_users.php`** - Configura contraseñas de prueba
- **`test_final_login.php`** - Prueba flujo completo de login

## Migración de Contraseñas (Opcional)

Para migrar todas las contraseñas existentes a bcrypt:

```php
// Leer todas las contraseñas en texto plano
// Hashearlas con AuthValidator::hashPassword()
// Actualizar BD
```

Ver script `setup_test_users.php` como referencia.

## Seguridad

- ✓ Contraseñas hasheadas con bcrypt (cost: 10)
- ✓ Validación flexible (soporta transición gradual a bcrypt)
- ✓ Configuración centralizada en JSON
- ✓ Protección de sesiones
- ✓ Validación de roles en páginas protegidas

## Próximos Pasos

1. Migrar todas las contraseñas existentes a bcrypt
2. Remover soporte de texto plano (cuando esté hecho)
3. Implementar recuperación de contraseña
4. Añadir logs de auditoría de autenticación

