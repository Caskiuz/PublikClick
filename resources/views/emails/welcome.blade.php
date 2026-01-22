<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bienvenido a PubliClick</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4F46E5; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .button { background: #10B981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Bienvenido a PubliClick!</h1>
        </div>
        <div class="content">
            <h2>Hola {{ $user->name }},</h2>
            <p>¡Gracias por unirte a PubliClick! Tu cuenta ha sido creada exitosamente.</p>
            
            <h3>Tu información:</h3>
            <ul>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>Código de referido:</strong> {{ $user->referral_code }}</li>
                <li><strong>Rango inicial:</strong> Jade</li>
            </ul>
            
            <h3>Próximos pasos:</h3>
            <ol>
                <li>Compra tu primer paquete publicitario</li>
                <li>Comienza a hacer clicks diarios</li>
                <li>Invita amigos con tu código de referido</li>
                <li>¡Empieza a ganar dinero!</li>
            </ol>
            
            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/dashboard') }}" class="button">Ir al Dashboard</a>
            </p>
            
            <p><small>Si tienes preguntas, contáctanos. ¡Éxito en PubliClick!</small></p>
        </div>
    </div>
</body>
</html>