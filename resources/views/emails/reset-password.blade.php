<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recuperar Contraseña</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #F59E0B; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .button { background: #EF4444; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Recuperar Contraseña</h1>
        </div>
        <div class="content">
            <h2>Hola {{ $user->name }},</h2>
            <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta en PubliClick.</p>
            
            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ $resetUrl }}" class="button">Restablecer Contraseña</a>
            </p>
            
            <p><strong>Importante:</strong></p>
            <ul>
                <li>Este enlace expira en 1 hora</li>
                <li>Solo puedes usarlo una vez</li>
                <li>Si no solicitaste este cambio, ignora este email</li>
            </ul>
            
            <p><small>Si no puedes hacer click en el botón, copia y pega este enlace:<br>
            {{ $resetUrl }}</small></p>
        </div>
    </div>
</body>
</html>