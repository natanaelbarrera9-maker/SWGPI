#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\PasswordRecoveryMail;

echo "=== Test de Recuperacion de Contrasena ===\n\n";

// Verificar que existe un usuario de prueba
$testEmail = 'natanaelbarrera@hotmail.com';
$user = DB::table('users')->where('email', $testEmail)->first();

if (!$user) {
    echo "Error: Usuario no encontrado con email: $testEmail\n";
    exit(1);
}

echo "Usuario encontrado: ID={$user->id}, Email={$user->email}\n\n";

// Paso 1: Generar token y guardar en BD
echo "Paso 1: Generando token...\n";
$token = bin2hex(random_bytes(32));
$expiresAt = now()->addHour();

DB::table('password_resets')->where('user_id', $user->id)->delete();
DB::table('password_resets')->insert([
    'user_id' => $user->id,
    'token' => $token,
    'expires_at' => $expiresAt,
    'created_at' => now(),
]);

echo "Token generado: $token\n";
echo "Expira a: $expiresAt\n\n";

// Paso 2: Enviar email
echo "Paso 2: Enviando email...\n";
try {
    Mail::mailer('smtp')->to($testEmail)->send(new PasswordRecoveryMail($token, $testEmail));
    echo "Email enviado exitosamente\n\n";
} catch (Exception $e) {
    echo "Error al enviar: " . $e->getMessage() . "\n";
    exit(1);
}

// Paso 3: Simular verificación de token
echo "Paso 3: Verificando token...\n";
$reset = DB::table('password_resets')
    ->where('user_id', $user->id)
    ->where('token', $token)
    ->where('expires_at', '>', now())
    ->first();

if (!$reset) {
    echo "Error: Token no encontrado o expirado\n";
    exit(1);
}

echo "Token verificado correctamente\n\n";

// Paso 4: Cambiar contraseña
echo "Paso 4: Cambiando contrasena...\n";
$newPassword = 'NuevaContrasena123';
DB::table('users')->where('id', $user->id)->update([
    'password' => Hash::make($newPassword),
]);

echo "Contrasena actualizada a: $newPassword\n\n";

// Paso 5: Eliminar token
echo "Paso 5: Eliminando token usado...\n";
DB::table('password_resets')->where('id', $reset->id)->delete();
echo "Token eliminado\n\n";

echo "=== Test Completado Exitosamente ===\n";
echo "Logs: storage/logs/laravel.log\n";
