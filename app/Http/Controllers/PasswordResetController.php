<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;

class PasswordResetController extends Controller
{
    public function showRequestForm()
    {
        return view('auth.password_reset_request');
    }

    public function handleRequest(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $user = DB::table('users')->where('email', $validated['email'])->first();

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expiresAt = now()->addHour();

            DB::table('password_resets')
                ->where('user_id', $user->id)
                ->delete();

            DB::table('password_resets')->insert([
                'user_id' => $user->id,
                'token' => $token,
                'expires_at' => $expiresAt,
                'created_at' => now(),
            ]);

            try {
                // Envío por la API de SendGrid (HTTP)
                $resetUrl = url('/password-reset-check');
                $htmlBody = view('emails.password_reset', [
                    'token' => $token,
                    'resetUrl' => $resetUrl,
                ])->render();

                $plainBody = "Has solicitado restablecer tu contrasena.\n\nCodigo: {$token}\n\nIngresa el codigo en: {$resetUrl}\n";

                // Envío usando el sistema de Mail de Laravel y la clase Mailable
                try {
                    Log::info('PasswordReset: enviando correo a ' . $user->email . ' user_id=' . $user->id);
                    // Forzar uso del mailer SMTP nativo de Laravel en lugar de SendGrid
                    Mail::mailer('smtp')->to($user->email)->send(new \App\Mail\PasswordResetMail($token, $user->id));
                    Log::info('PasswordReset: Mail enviado (smtp) to ' . $user->email . ' token=' . $token);
                } catch (\Exception $e) {
                    Log::error('Mail send error (Laravel Mail): ' . $e->getMessage());
                }
            } catch (\Exception $e) {
                Log::error('Password reset email error: ' . $e->getMessage());
            }

            // Si el usuario existe, redirigimos a la pantalla de verificación
            // y prellenamos el campo user_id para facilitar el siguiente paso.
            return redirect()->route('auth.password-reset-check')
                ->with('success', 'Si el correo existe en el sistema, recibiras instrucciones por email.')
                ->with('user_id', $user->id);
        }

        // Mensaje genérico si no existe el usuario (no revelamos existencia)
        return redirect()->route('auth.password-reset-check')
            ->with('success', 'Si el correo existe en el sistema, recibiras instrucciones por email.');
    }

    public function showCheckTokenForm()
    {
        return view('auth.password_reset_check');
    }

    public function verifyToken(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'user_id' => 'required|string',
        ]);

        $reset = DB::table('password_resets')
            ->where('token', $validated['token'])
            ->where('user_id', $validated['user_id'])
            ->where('expires_at', '>', now())
            ->first();

        if (!$reset) {
            return redirect()->route('auth.password-reset-request')
                ->with('error', 'Token invalido o expirado.');
        }

        return view('auth.password_reset_form', [
            'token' => $validated['token'],
            'user_id' => $validated['user_id'],
        ]);
    }

    public function reset(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'user_id' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $reset = DB::table('password_resets')
            ->where('token', $validated['token'])
            ->where('user_id', $validated['user_id'])
            ->where('expires_at', '>', now())
            ->first();

        if (!$reset) {
            return redirect()->route('auth.password-reset-request')
                ->with('error', 'Token invalido o expirado.');
        }

        $user = DB::table('users')->where('id', $validated['user_id'])->first();
        if (!$user) {
            return redirect()->route('auth.password-reset-request')
                ->with('error', 'Usuario no encontrado.');
        }

        DB::table('users')
            ->where('id', $validated['user_id'])
            ->update([
                'password' => Hash::make($validated['password']),
            ]);

        DB::table('password_resets')
            ->where('id', $reset->id)
            ->delete();

        return redirect()->route('login')
            ->with('success', 'Contrasena restablecida. Inicia sesion con tu nueva contrasena.');
    }
}
