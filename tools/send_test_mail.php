<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('Prueba directa desde script: si recibes esto, SMTP funciona', function($m) {
        $m->to('pruebastecnm7@gmail.com')->subject('Prueba SMTP SendGrid - script');
    });
    echo "Mail enviado o procesado sin excepción.\n";
} catch (Exception $e) {
    echo "Error al enviar: " . $e->getMessage() . "\n";
}
