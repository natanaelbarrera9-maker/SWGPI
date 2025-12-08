<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$token = bin2hex(random_bytes(8));
$to = 'pruebastecnm7@gmail.com';
$resetUrl = url('/password-reset-check');
$htmlBody = "<p>Prueba API SendGrid</p><p>Token: <strong>{$token}</strong></p><p>Ingresa el token en: {$resetUrl}</p>";
$plainBody = "Prueba API SendGrid\nToken: {$token}\nVisita: {$resetUrl}\n";

try {
    $response = Http::withToken(env('SENDGRID_API_KEY'))
        ->post('https://api.sendgrid.com/v3/mail/send', [
            'personalizations' => [
                [
                    'to' => [ ['email' => $to] ],
                    'subject' => 'Prueba API SendGrid desde Laravel',
                ],
            ],
            'from' => [ 'email' => env('MAIL_FROM_ADDRESS', 'pruebastecnm7@gmail.com'), 'name' => env('MAIL_FROM_NAME', 'SWGPI') ],
            'content' => [ ['type' => 'text/plain', 'value' => $plainBody], ['type' => 'text/html', 'value' => $htmlBody] ],
        ]);

    echo "HTTP status: " . $response->status() . "\n";
    echo "Body: " . $response->body() . "\n";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
