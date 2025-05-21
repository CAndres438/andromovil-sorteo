# <img src="./public/favicon.png" alt="Logo" width="30"/> 🚗 Landing Sorteo Andromóvil

Landing page para registrar participantes en un sorteo promocional de una marca de automóviles. 
Incluye autenticación de administrador, selección aleatoria de ganadores, exportación de datos y despliegue funcional en Heroku.

---

## 🌟 Características principales

- Registro de participantes con validación robusta.
- Vista administrativa protegida.
- Elección aleatoria de ganador con restricción de mínimo 5 usuarios.
- Exportación a Excel.
- Interfaz moderna con Vue 3 e Inertia.
- App totalmente funcional desplegada en Heroku.

---

## 🚀 Tecnologías utilizadas

### Backend
- Laravel 10
- Laravel Sanctum (autenticación via cookies)
- Laravel Breeze (auth frontend/backend)
- Laravel Excel (exportación de datos)
- PostgreSQL en producción
- MySQL en desarrollo
- PHP 8.1

### Frontend
- Vue 3 + Vite
- Inertia.js
- Tailwind CSS
- Axios
- Yup + Vee-Validate (validaciones)
- canvas-confetti (efecto visual al elegir ganador)

---

## 📝 Funcionalidades detalladas

### 👥 Registro de participantes
- Ruta: `POST /api/contest/register`
- Validaciones:
    - Nombre y Apellido (solo letras)
    - Cédula y Celular (solo números)
    - Email con @ obligatorio
    - Selección dependiente de Departamento y Ciudad
    - Checkbox de Habeas Data obligatorio

### 🔐 Autenticación de administrador
- Ruta: `https://andromovil-app-ccd90717ed2b.herokuapp.com/login`
- Login exclusivo para admin
- Protegido con middleware Sanctum

### 🏆 Sorteo aleatorio
- Ruta protegida: `POST /api/contest/winner`
- Solo disponible si existen al menos 5 registros
- Solo se puede seleccionar un ganador activo
- Visualmente se muestra el ganador con confeti en el frontend

### 📊 Panel de administración
- Vista protegida para el admin
- Tabla con todos los participantes registrados
- Exportación de datos a Excel con `Laravel Excel`

---

## 🌐 Rutas principales

### Web (Protegidas con auth)
- `/login` - login para admin
- `/dashboard` - panel de administración y sorteo

### API
- `POST /api/contest/register` - registrar participante
- `POST /api/contest/winner` - seleccionar ganador
- `GET /api/clients/export` - exportar registros a Excel

---

## 🔒 Protecciones implementadas
- Middleware `auth:sanctum`
- URL oculta para login
- Validaciones tanto en frontend como backend
- Forzamiento de HTTPS en producción
- Ambiente de Heroku configurado con variables seguras

---

## 🧰 Instalación y configuración local

### ✅ Requisitos previos

Asegúrate de tener instalados los siguientes elementos en tu sistema:

- **PHP >= 8.1**
- **Composer**
- **Node.js >= 18 y NPM**
- **MySQL** (u otra base de datos compatible)
- **Git**

---

### 🔧 Pasos para poner en marcha el proyecto localmente

```bash
# 1. Clona el repositorio
git clone https://github.com/CAndres438/andromovil-sorteo.git
cd andromovil-sorteo

# 2. Instala las dependencias de PHP y JavaScript
composer install
npm install

# 3. Copia y configura tu entorno
cp .env.example .env

# 4. Genera la clave de aplicación
php artisan key:generate

# 5. Configura tu base de datos local (ejemplo para MySQL)
# En tu archivo .env:
#
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=andromovil_draw
# DB_USERNAME=root
# DB_PASSWORD=Admin

# 6. Ejecuta las migraciones y los seeders
php artisan migrate --seed

# 7. Compila los assets de frontend
npm run dev

# 8. Levanta el servidor
php artisan serve
```

---

### 📁 Variables de entorno clave

Estas variables deben ser revisadas y actualizadas según el entorno:

#### 🔧 Comunes

```
APP_NAME=AndroMovil
APP_ENV=local
APP_KEY=...
APP_URL=http://localhost
FRONTEND_URL=http://localhost:8000
```

#### 🛢 Base de datos

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=andromovil_draw
DB_USERNAME=root
DB_PASSWORD=Admin
```


#### 🛡 Admin por defecto


ADMIN_EMAIL=admin@andromovil.com
ADMIN_PASSWORD=admin123.


---

## 🔐 Credenciales por defecto

| Rol   | Correo                   | Contraseña |
|--------|--------------------------|-------------|
| Admin | admin@andromovil.com     | admin123    |

> Acceso solo por ruta: /login
> Recuerda que postman el token se recupera con 

```bash
/api/token
```

---

## 🚀 Despliegue en Heroku
- Proyecto configurado con buildpacks PHP y Node.js
- `heroku-postbuild` ejecuta `npm run build`
- `APP_URL` configurada para forzar `https`
- Assets generados por Vite en carpeta `public/build`

### Archivos relevantes:
- `Procfile`: Apache sirve desde `public/`
- `vite.config.js`: base `/build/`
- `AppServiceProvider.php`: `URL::forceScheme('https')`

---

## 🧪 Probar con Postman

### 🔐 Obtener token
```http
POST /api/token
Content-Type: application/json

{
  "email": "admin@andromovil.com",
  "password": "admin123"
}
```
**Observación:** Devuelve token si se desea hacer pruebas tipo Bearer (aunque en producción se usa cookie auth).

### 📥 Registrar participante
```http
POST /api/contest/register
Content-Type: application/json

{
  "name": "Carlos",
  "last_name": "Ortiz",
  "id_number": "123456789",
  "department": "Bogotá D.C.",
  "city": "Bogotá",
  "phone": "3001234567",
  "email": "carlos@example.com",
  "accept_habeas": true
}
```

### 🏆 Elegir ganador
```http
POST /api/contest/winner
Authorization: Cookie (requiere login previo desde /login)
```

### 📤 Exportar participantes (admin)
```http
GET /api/clients/export
Authorization: Cookie (requiere login previo desde /login)
```

---

## 🔧 Scripts y paquetes clave

### Composer
- `maatwebsite/excel`
- `inertiajs/inertia-laravel`
- `laravel/sanctum`
- `laravel-lang/common`
- `tightenco/ziggy`

### NPM
- `@inertiajs/vue3`
- `@tailwindcss/forms`
- `vee-validate` + `yup`
- `canvas-confetti`
- `axios`, `vite`, `vue`, `laravel-vite-plugin`

---

## 🧠 Autor

**Carlos Andrés Ortiz Peña**  
Senior Fullstack Developer 💻 | Constructor de soluciones 🚀
---

