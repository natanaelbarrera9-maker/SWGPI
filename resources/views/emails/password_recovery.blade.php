@extends('layouts.email')

@section('content')
<div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; line-height: 1.6;">
    <h2 style="color: #1B396A; text-align: center; margin-bottom: 20px;">Recuperación de Contraseña</h2>
    
    <p style="font-size: 16px; margin-bottom: 15px;">
        Hola,
    </p>
    
    <p style="font-size: 14px; margin-bottom: 20px; color: #555;">
        Has solicitado restablecer tu contraseña en <strong>SWGPI</strong>. Usa el siguiente código para completar el proceso de recuperación.
    </p>
    
    <div style="background: linear-gradient(135deg, #1B396A 0%, #2D5A96 100%); padding: 20px; border-radius: 8px; text-align: center; margin: 25px 0;">
        <p style="margin: 0; color: white; font-size: 12px; margin-bottom: 10px;">Código de Verificación</p>
        <p style="margin: 0; color: white; font-size: 24px; font-family: 'Courier New', monospace; letter-spacing: 3px; font-weight: bold;">
            {{ $token }}
        </p>
    </div>
    
    <p style="font-size: 14px; margin-bottom: 20px; color: #555;">
        Este código es válido por <strong>1 hora</strong>. Si no solicitaste esta recuperación, puedes ignorar este correo.
    </p>
    
    <div style="background-color: #f5f7fb; border-left: 4px solid #1B396A; padding: 15px; margin: 20px 0; border-radius: 4px;">
        <p style="margin: 0; font-size: 13px; color: #666;">
            <strong>Pasos a seguir:</strong><br>
            1. Copia el código de arriba<br>
            2. Ingresa a tu cuenta en SWGPI<br>
            3. Selecciona "Recuperar Contraseña"<br>
            4. Pega el código y establece tu nueva contraseña<br>
        </p>
    </div>
    
    <p style="font-size: 12px; color: #999; margin-top: 25px; border-top: 1px solid #ddd; padding-top: 15px;">
        Este es un correo automático. Por favor, no respondas a este mensaje.
    </p>
    
    <p style="font-size: 12px; color: #999; margin: 5px 0;">
        © {{ date('Y') }} SWGPI - Soporte Técnico
    </p>
</div>
@endsection
