<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;

$token = bin2hex(random_bytes(16));
$userId = 'TESTUSER';
$to = 'natanaelbarrera@hotmail.com'; // test destination provided by user

try {
    // Forzar uso del mailer SMTP nativo de Laravel
    Mail::mailer('smtp')->to($to)->send(new PasswordResetMail($token, $userId));
    echo "Mail enviado (smtp)\n";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
