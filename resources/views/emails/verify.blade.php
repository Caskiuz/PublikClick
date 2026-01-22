<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verifica tu Email</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #EF4444; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .button { background: #10B981; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Verifica tu Email</h1>
        </div>
        <div class="content">
            <h2>Hola {{ $user->name }},</h2>
            <p>Para completar tu registro en PubliClick, necesitas verificar tu dirección de email.</p>
            
            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ $verificationUrl }}" class="button">Verificar Email</a>
            </p>
            
            <p><strong>¿Por qué verificar tu email?</strong></p>
            <ul>
                <li>Seguridad de tu cuenta</li>
                <li>Recibir notificaciones importantes</li>
                <li>Recuperar tu contraseña si la olvidas</li>
                <li>Confirmaciones de retiros</li>
            </ul>
            
            <p><small>Si no puedes hacer click en el botón, copia y pega este enlace en tu navegador:<br>
            {{ $verificationUrl }}</small></p>
            
            <p><small>Este enlace expira en 24 horas.</small></p>
        </div>
    </div>
</body>
</html>