@echo off
echo ========================================
echo   PUBLIHAZCLICK - Iniciando Servidor
echo ========================================
echo.

REM Verificar si el puerto 8000 está en uso
netstat -ano | findstr :8000 > nul
if %errorlevel% equ 0 (
    echo [ADVERTENCIA] El puerto 8000 ya esta en uso
    echo Intentando con puerto 8001...
    php artisan serve --host=0.0.0.0 --port=8001
) else (
    echo Iniciando servidor en puerto 8000...
    php artisan serve --host=0.0.0.0 --port=8000
)

pause
