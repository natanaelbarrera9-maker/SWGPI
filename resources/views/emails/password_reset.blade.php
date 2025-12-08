@extends('layouts.email')

@section('content')
<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; background-color: #f9f9f9; padding: 20px; border-radius: 8px;">
    <div style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; padding: 30px; border-radius: 8px 8px 0 0; text-align: center;">
        <h1 style="margin: 0; font-size: 28px;">SWGPI</h1>
        <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Sistema de Gestión de Proyectos Integrador</p>
    </div>

    <div style="background-color: white; padding: 30px; border-radius: 0 0 8px 8px;">
        <h2 style="color: #1B396A; margin-top: 0;">Recuperación de Contraseña</h2>
        
        <p style="margin-bottom: 15px;">¡Hola!</p>
        
        <p style="margin-bottom: 15px;">
            Has solicitado restablecer tu contraseña en SWGPI. Aquí está tu <strong>código de verificación</strong>:
        </p>

        <div style="background-color: #f5f7fb; padding: 20px; border-radius: 8px; border-left: 4px solid #1B396A; margin: 20px 0; text-align: center;">
            <p style="margin: 0; font-size: 12px; color: #666; margin-bottom: 10px;">CÓDIGO DE VERIFICACIÓN</p>
            <p style="margin: 0; font-size: 24px; font-weight: bold; color: #1B396A; letter-spacing: 2px; font-family: 'Courier New', monospace;">
                {{ $token }}
            </p>
        </div>

        <p style="margin: 20px 0; font-size: 14px; color: #666;">
            <strong>O usa este enlace:</strong>
        </p>

        <div style="text-align: center; margin: 25px 0;">
            <a href="{{ $resetUrl }}" style="display: inline-block; background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 8px; font-weight: bold;">
                Restablecer Mi Contraseña
            </a>
        </div>

        <p style="margin: 20px 0; font-size: 12px; color: #999; border-top: 1px solid #f0f0f0; padding-top: 15px;">
            <strong>⏰ Tiempo de validez:</strong> Este código expira en 1 hora.
        </p>

        <p style="margin: 10px 0; font-size: 12px; color: #999;">
            <strong>🔒 Seguridad:</strong> Si no solicitaste esta recuperación, puedes ignorar este correo de forma segura. Tu contraseña no ha cambiado.
        </p>

        <hr style="border: none; border-top: 1px solid #f0f0f0; margin: 25px 0;">

        <p style="margin: 10px 0; font-size: 11px; color: #999; text-align: center;">
            © {{ date('Y') }} SWGPI - Sistema de Gestión de Proyectos Integrador<br>
            Todos los derechos reservados.
        </p>
    </div>
</div>
@endsection
