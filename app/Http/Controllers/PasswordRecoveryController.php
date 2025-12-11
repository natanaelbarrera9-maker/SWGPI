<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordRecoveryMail;
use App\Exceptions\RouteRedirectException;
class PasswordRecoveryController extends Controller
{
    public function showRequest()
    {
        return view('auth.password_recovery_request');
    }
    public function handleRequest(Request $request)
    {
        $validated = $request->validate(['email' => 'required|email']);
        $user = DB::table('users')->where('email', $validated['email'])->first();
        if (!$user) {
            Log::warning('Password recovery: email no encontrado');
            return redirect()->route('password-recovery.verify')->with('success', 'Si el correo existe, recibiras instrucciones.');
        }
        Log::info('Password recovery: email encontrado para user_id=' . $user->id);
        $token = bin2hex(random_bytes(32));
        $expiresAt = now()->addHour();
        DB::table('password_resets')->where('user_id', $user->id)->delete();
        DB::table('password_resets')->insert([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => $expiresAt,
            'created_at' => now(),
        ]);
        try {
            Log::info('Password recovery: enviando email a ' . $validated['email']);
            Mail::mailer('smtp')->to($validated['email'])->send(new PasswordRecoveryMail($token, $validated['email']));
            Log::info('Password recovery: email enviado');
        } catch (\Exception $e) {
            Log::error('Password recovery: error: ' . $e->getMessage());
            throw new RouteRedirectException('password-recovery.request', ['error' => 'Error al enviar email.']);
        }
        return redirect()->route('password-recovery.verify')->with('success', 'Instrucciones enviadas.')->with('email', $validated['email']);
    }
    public function showVerify()
    {
        return view('auth.password_recovery_verify');
    }
    public function handleVerify(Request $request)
    {
        $validated = $request->validate(['email' => 'required|email', 'token' => 'required|string']);
        $user = DB::table('users')->where('email', $validated['email'])->first();
        if (!$user) {
            Log::warning('PasswordRecoveryController@handleVerify: email not found ' . $validated['email']);
            throw new RouteRedirectException('password-recovery.verify', ['error' => 'Email no encontrado.'], [], true);
        }
        $reset = DB::table('password_resets')->where('user_id', $user->id)->where('token', $validated['token'])->where('expires_at', '>', now())->first();
        if (!$reset) {
            Log::warning('PasswordRecoveryController@handleVerify: invalid or expired token for email ' . $validated['email']);
            throw new RouteRedirectException('password-recovery.verify', ['error' => 'Codigo invalido o expirado.'], [], true);
        }
        return redirect()->route('password-recovery.reset')->with('email', $validated['email'])->with('user_id', $user->id)->with('token', $validated['token']);
    }
    public function showReset()
    {
        $email = session('email');
        $userId = session('user_id');
        $token = session('token');
        if (!$email || !$userId || !$token) {
            Log::warning('PasswordRecoveryController@showReset: invalid session values');
            throw new RouteRedirectException('password-recovery.request', ['error' => 'Sesion invalida.']);
        }
        return view('auth.password_recovery_reset', ['email' => $email, 'user_id' => $userId, 'token' => $token]);
    }
    public function handleReset(Request $request)
    {
        $validated = $request->validate(['email' => 'required|email', 'user_id' => 'required|string', 'token' => 'required|string', 'password' => 'required|string|min:8|confirmed']);
        $reset = DB::table('password_resets')->where('user_id', $validated['user_id'])->where('token', $validated['token'])->where('expires_at', '>', now())->first();
        if (!$reset) {
            Log::warning('PasswordRecoveryController@handleReset: reset token expired for user_id=' . $validated['user_id']);
            throw new RouteRedirectException('password-recovery.request', ['error' => 'Codigo expirado.']);
        }
        $user = DB::table('users')->where('id', $validated['user_id'])->first();
        if (!$user) {
            Log::warning('PasswordRecoveryController@handleReset: user not found user_id=' . $validated['user_id']);
            throw new RouteRedirectException('password-recovery.request', ['error' => 'Usuario no encontrado.']);
        }
        DB::table('users')->where('id', $user->id)->update(['password' => Hash::make($validated['password'])]);
        Log::info('Password recovery: contrasena actualizada para user_id=' . $user->id);
        DB::table('password_resets')->where('id', $reset->id)->delete();
        return redirect()->route('login')->with('success', 'Contrasena actualizada. Inicia sesion.');
    }
}
