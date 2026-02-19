# PubliHazClick

Sistema de publicidad PTC (Paid-To-Click) con programa de referidos multinivel.

## 🚀 Características

- Sistema de anuncios (Principales, Mini, Mega)
- Programa de referidos multinivel
- Sistema de categorías (Jade, Perla, Zafiro, Rubí, Esmeralda, Diamante)
- Múltiples métodos de pago (Nequi, Bancolombia, PayPal, etc.)
- Panel de administración completo
- Sistema de validación con CAPTCHA

## 📋 Requisitos

- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Node.js & NPM

## 🔧 Instalación

```bash
# Clonar repositorio
git clone <repository-url>
cd publiclik

# Instalar dependencias
composer install
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env
# DB_DATABASE=publiclik
# DB_USERNAME=root
# DB_PASSWORD=

# Migrar base de datos
php artisan migrate --seed

# Compilar assets
npm run build

# Iniciar servidor
php artisan serve
```

## 🌐 Deployment

```bash
# Producción
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

## 📝 Licencia

Propietario - Todos los derechos reservados
