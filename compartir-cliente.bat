@echo off
title PubliHazClick - Servidor con Cloudflare Tunnel
color 0A

echo ========================================
echo   PUBLIHAZCLICK - Iniciando Sistema
echo ========================================
echo.
echo [1/2] Iniciando servidor Laravel...
echo.

REM Iniciar servidor Laravel en segundo plano
start /B php artisan serve --host=127.0.0.1 --port=8000

REM Esperar 3 segundos para que el servidor inicie
timeout /t 3 /nobreak > nul

echo [OK] Servidor Laravel iniciado en http://127.0.0.1:8000
echo.
echo [2/2] Creando tunel publico con Cloudflare...
echo.
echo ========================================
echo   COMPARTIR CON EL CLIENTE
echo ========================================
echo.
echo Copiando la URL que aparece abajo...
echo.

REM Iniciar cloudflared tunnel
cloudflared tunnel --url http://localhost:8000

pause
