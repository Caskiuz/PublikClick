# 🔐 CREDENCIALES DE ACCESO - PUBLICLIK

## 👨‍💼 CUENTA ADMINISTRADOR
```
Email: admin@publiclik.com
Password: admin123
```
**Acceso a:**
- Dashboard de usuario
- Panel administrativo: http://127.0.0.1:8000/admin
- Configuración de pasarelas de pago
- Gestión de usuarios
- Aprobación de retiros

---

## 👤 CUENTA DEMO (Usuario Normal)
```
Email: demo@publiclik.com
Password: demo123
```
**Acceso a:**
- Dashboard de usuario
- Compra de paquetes
- Sistema de clicks
- Referidos
- Billetera

---

## 🚀 CÓMO INICIAR SESIÓN

1. Ve a: http://127.0.0.1:8000
2. Click en "Iniciar Sesión"
3. Ingresa las credenciales
4. Serás redirigido al dashboard

---

## 🔧 COMANDOS ÚTILES

### Ver todos los usuarios:
```bash
php artisan users:list
```

### Resetear base de datos y crear usuarios:
```bash
php artisan migrate:fresh --seed
```

### Crear nuevo usuario admin manualmente:
```bash
php artisan tinker
User::create([
    'name' => 'Nuevo Admin',
    'email' => 'nuevo@admin.com',
    'password' => Hash::make('password'),
    'referral_code' => 'ADMIN002',
    'is_admin' => true,
    'is_active' => true,
    'wallet_balance' => 0
]);
```

---

## ⚠️ IMPORTANTE

- Cambia estas contraseñas en producción
- El campo `is_admin` determina acceso al panel admin
- Todos los usuarios tienen código de referido único
