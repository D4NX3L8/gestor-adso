# GESTOR ADSO

Proyecto integrador **Gestor ADSO v01** del programa **ADSO – SENA Regional Antioquia (CTMA)**.
Aplicación web desarrollada con **PHP y Laravel** que servirá como base del proyecto ABP del trimestre y crecerá progresivamente durante las siguientes semanas.

- **Resultado de aprendizaje:** RA EL02 · NORMA 220501094 — *Elaborar propuesta técnica del software según especificaciones*.
- **Instructor:** Gustavo Bolaños.
- **Semana 1:** Instalación del entorno y primer arranque.
- **Semana 2:** Routing en `web.php` y controladores REST (módulo Aprendices).
- **Semana 3:** Migraciones, modelos, factories, seeders y Eloquent (persistencia real).

---

## Semana 2 — Routing y Controladores REST (módulo Aprendices)

En esta semana se conectó el flujo **Ruta → Controlador → Vista** (patrón MVC) para dejar operativo el módulo `/aprendices`. El guardado es **simulado**; la persistencia real con Eloquent llega en las semanas 3 y 4.

### Rutas — `routes/web.php`
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AprendizController;

Route::redirect('/', '/aprendices');
Route::resource('aprendices', AprendizController::class);

// TEMP: Rutas de práctica (comentar antes de producción)
// Route::get('/ping', fn() => 'pong');
// Route::get('/hola/{nombre}', fn($nombre) => "Hola, $nombre");
// Route::get('/suma/{a}/{b}', fn($a,$b) => (int)$a + (int)$b);
```

### Controlador — `app/Http/Controllers/AprendizController.php`
Generado con `php artisan make:controller AprendizController --resource`. Implementa `index()`, `create()` y `store()` (validación mínima + redirección con mensaje flash). `show`, `edit`, `update` y `destroy` quedan con `abort(404)` porque no se usan en esta semana.

### Vistas Blade
- `resources/views/layouts/app.blade.php` — plantilla maestra (menú con `route('aprendices.index')`, `@yield`, mensajes flash).
- `resources/views/aprendices/index.blade.php` — listado temporal + enlace «+ Nuevo».
- `resources/views/aprendices/create.blade.php` — formulario POST con `@csrf` (nombre, documento, correo).

### Mapa de rutas
| Método | URI | Nombre | Acción | Respuesta |
|---|---|---|---|---|
| GET,HEAD | `/` | — | RedirectController | 302 → `/aprendices` |
| GET,HEAD | `/aprendices` | `aprendices.index` | `AprendizController@index` | `view` listado |
| GET,HEAD | `/aprendices/create` | `aprendices.create` | `AprendizController@create` | `view` formulario |
| POST | `/aprendices` | `aprendices.store` | `AprendizController@store` | Validación → redirect + flash |
| GET,HEAD | `/aprendices/{aprendice}` | `aprendices.show` | `AprendizController@show` | `abort(404)` |
| GET,HEAD | `/aprendices/{aprendice}/edit` | `aprendices.edit` | `AprendizController@edit` | `abort(404)` |
| PUT,PATCH | `/aprendices/{aprendice}` | `aprendices.update` | `AprendizController@update` | `abort(404)` |
| DELETE | `/aprendices/{aprendice}` | `aprendices.destroy` | `AprendizController@destroy` | `abort(404)` |

### Cómo verificar
```bash
php artisan route:clear
php artisan route:list          # ver las rutas aprendices.*
php artisan serve               # http://127.0.0.1:8000/aprendices
```
Al enviar el formulario se redirige a `/aprendices` con el mensaje **"Aprendiz recibido (simulado)"**.

---

## Semana 3 — Migraciones, Modelos, Seeders y Eloquent (persistencia real)

En esta semana el módulo `/aprendices` dejó de ser una simulación: la tabla `aprendices`
existe en la base de datos `gestor_adso`, el modelo usa Eloquent y el formulario guarda
registros reales. También se controlan los duplicados de `documento` y `correo`.

### Archivos principales

| Archivo | Descripción |
|---|---|
| `app/Models/Aprendiz.php` | Modelo Eloquent con `HasFactory`, `$table` y `$fillable`. |
| `database/migrations/2026_10_23_200417_create_aprendices_table.php` | Tabla `aprendices` con `documento` y `correo` únicos, `ficha_id` nullable y `timestamps`. |
| `database/factories/AprendizFactory.php` | Datos realistas con Faker y valores únicos. |
| `database/seeders/AprendizSeeder.php` | Genera **25 aprendices** de prueba. |
| `database/seeders/DatabaseSeeder.php` | Registra `AprendizSeeder` para ejecutar la carga inicial con `db:seed`. |
| `app/Http/Controllers/AprendizController.php` | `index()` con búsqueda por nombre y paginación de 10; `store()` valida duplicados y persiste con Eloquent. |
| `resources/views/aprendices/index.blade.php` | Listado real con búsqueda por nombre, tabla y `{{ $aprendices->links() }}` conservando el filtro. |
| `resources/views/aprendices/create.blade.php` | Formulario con `@if ($errors->any())` y `old()` para conservar valores. |

### Actividad complementaria: búsqueda por nombre

El listado permite buscar por nombre completo o parcial mediante el campo «Buscar por nombre». La paginación conserva el criterio de búsqueda y el enlace «Limpiar búsqueda» vuelve al listado completo. La funcionalidad se verifica con `php artisan test`.

### Comandos ejecutados

```bash
sudo /opt/lampp/lampp startmysql
cd ~/Documents/gestor-adso
php artisan make:model Aprendiz -mfs
php artisan migrate
php artisan db:seed --class=AprendizSeeder
php artisan tinker --execute="echo App\Models\Aprendiz::count();"   # 25
php artisan serve
```

### Estructura de la tabla `aprendices`

| Campo | Tipo | Restricción |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK autoincremental |
| `nombre` | VARCHAR(120) | NOT NULL |
| `documento` | VARCHAR(40) | UNIQUE, NOT NULL |
| `correo` | VARCHAR(120) | UNIQUE, NOT NULL |
| `ficha_id` | BIGINT UNSIGNED | NULLABLE |
| `created_at` / `updated_at` | DATETIME | NULLABLE |

### Pruebas realizadas

- Listado paginado con los 25 registros sembrados.
- Creación real de un aprendiz desde `/aprendices/create` con mensaje «Aprendiz creado».
- Verificación del registro creado en phpMyAdmin.
- Prueba de **documento duplicado** → error de validación.
- Prueba de **correo duplicado** → error de validación.

> Resultado: **25 registros sembrados** y persistencia real comprobada en `gestor_adso.aprendices`.

---

## 1. Propósito del proyecto

Construir un entorno de desarrollo reproducible para el proyecto **Gestor ADSO** y dejar funcionando la base del proyecto Laravel con:

- Servidor local levantado (`php artisan serve`).
- Archivo `.env` configurado y `APP_KEY` generada.
- Base de datos `gestor_adso` creada en MariaDB.
- Migraciones ejecutadas (tablas de Laravel).
- Control de versiones con Git y primer commit.

---

## 2. Entorno y herramientas (versiones reales del equipo)

| Herramienta | Versión real | Ruta / detalle |
|---|---|---|
| Sistema operativo | Ubuntu 26.04.1 LTS | Host: `d4nx3l-Vivobook-Go-E1504FA-E1504FA` |
| XAMPP for Linux | 8.2.12-0 | `/opt/lampp` |
| Apache | 2.4.58 | Puerto 80 |
| MariaDB | 10.4.32 | Puerto 3306 |
| PHP | 8.2.12 (cli) | `/opt/lampp/bin/php` |
| php.ini activo | — | `/opt/lampp/etc/php.ini` |
| phpMyAdmin | 5.2.1 | `http://localhost/phpmyadmin` |
| Composer | 2.10.3 | `~/.local/bin/composer` |
| Laravel Framework | 12.69.3 | Proyecto `gestor-adso` |
| Git | 2.53.0 | `/usr/bin/git` |
| Visual Studio Code | 1.141.0 | Comando `code` |
| Node.js | v24.20.0 | `/home/d4nx3l/.nvm/...` |

**Extensiones PHP requeridas (verificadas con `php -m`):** `openssl`, `PDO`, `pdo_mysql`, `mbstring`, `fileinfo`, `zip`.

> Nota: en este equipo el PDF (originalmente para Windows/XAMPP) se adaptó a Linux. En lugar de `C:\xampp` se usa `/opt/lampp`, y en lugar de CMD/PowerShell se usa la terminal de Linux (zsh).

---

## 3. Pasos de instalación y configuración

### 3.1 Arrancar XAMPP
```bash
sudo /opt/lampp/lampp start        # Apache + MariaDB (+ ProFTPD)
sudo /opt/lampp/lampp status       # verificar servicios
ss -ltnp | grep -E ':80|:3306'     # comprobar puertos
```

### 3.2 PHP en el PATH
En `~/.zshrc`:
```bash
export PATH="/opt/lampp/bin:$PATH"
```
Recargar y verificar:
```bash
source ~/.zshrc
php -v          # PHP 8.2.12 (XAMPP)
which php       # /opt/lampp/bin/php
```

### 3.3 Verificar extensiones de PHP
```bash
php --ini        # /opt/lampp/etc/php.ini
php -m | grep -E 'openssl|pdo_mysql|mbstring|fileinfo|^zip$|^PDO$'
```

### 3.4 Composer (instalado con el PHP de XAMPP)
```bash
php -r "copy('https://getcomposer.org/installer','composer-setup.php');"
php composer-setup.php --install-dir="$HOME/.local/bin" --filename=composer
php -r "unlink('composer-setup.php');"
composer -V
```

### 3.5 Git y VS Code
```bash
git config --global user.name "D4NX3L8"
git config --global user.email "danielochoataborda8@gmail.com"
git --version
code --version
```

### 3.6 Crear el proyecto Laravel
```bash
cd ~/Documents
composer create-project laravel/laravel gestor-adso
cd gestor-adso
php artisan --version
```

### 3.7 Configuración del archivo `.env`
```bash
cp .env.example .env
php artisan key:generate
code .env
```
Valores de conexión a la base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestor_adso
DB_USERNAME=root
DB_PASSWORD=
```

> Credenciales sin contraseña corresponden únicamente al entorno educativo/local. No usar así en producción.

---

## 4. Base de datos y migraciones

### 4.1 Crear la base de datos `gestor_adso`
Opción consola:
```bash
/opt/lampp/bin/mysql -h 127.0.0.1 -P 3306 -u root -e \
"CREATE DATABASE gestor_adso CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```
Opción gráfica: `http://localhost/phpmyadmin` → *Nueva base de datos* → `gestor_adso`, cotejamiento `utf8mb4_unicode_ci` → Crear.

### 4.2 Ejecutar migraciones
```bash
php artisan config:clear
php artisan migrate
```
Tablas creadas: `users`, `password_reset_tokens`, `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `migrations`.

---

## 5. Cómo ejecutar el servidor

```bash
cd ~/Documents/gestor-adso
php artisan serve
```
Abrir en el navegador: **http://127.0.0.1:8000**

> La terminal del servidor queda ocupada; para otros comandos abrir una segunda terminal dentro del proyecto.

### 5.1. Datos de prueba (Semana 3)
```bash
php artisan migrate                                # crea la tabla aprendices
php artisan db:seed --class=AprendizSeeder         # siembra 25 aprendices
php artisan tinker --execute="echo App\Models\Aprendiz::count();"
```
Si necesitas recrear la base de datos desde cero en desarrollo: `php artisan migrate:fresh --seed` (borra todos los datos).

Comandos útiles de Artisan:
```bash
php artisan --version     # versión de Laravel
php artisan serve         # servidor local de desarrollo
php artisan migrate       # ejecutar migraciones
php artisan config:clear  # limpiar caché de configuración
```

---

## 6. Estructura del proyecto

```
gestor-adso
├── app/            Lógica principal (modelos, controladores, middleware)
├── bootstrap/
├── config/         Archivos de configuración de Laravel
├── database/       Migraciones, seeders y factories
├── public/         Punto de entrada público
├── resources/      Vistas Blade, CSS, JavaScript
├── routes/         Rutas web, API y consola
├── storage/        Logs y archivos generados
├── tests/
├── .env            Configuración del entorno (no se sube a Git)
├── artisan         CLI de Laravel
└── composer.json   Dependencias PHP del proyecto
```

---

## 7. Control de versiones

```bash
git init
git add .
git status
git commit -m "Semana 1: Entorno listo + Laravel base"
git log --oneline
```
El archivo `.gitignore` excluye `vendor/`, `.env` y demás archivos sensibles/generados.

---

## 8. Evidencias del proceso

Cada captura incluye: comando ejecutado, para qué sirve, resultado obtenido y por qué demuestra que la configuración funciona.

| # | Evidencia | Descripción |
|---|---|---|
| 1 | Servicios XAMPP activos | Apache y MariaDB en ejecución |
| 2 | `php -v` + `which php` | PHP apunta a la instalación de XAMPP (`/opt/lampp/bin/php`) |
| 3 | `php -m` | Extensiones `openssl`, `PDO`, `pdo_mysql`, `mbstring`, `fileinfo`, `zip` presentes |
| 4 | `composer -V` | Composer instalado (2.10.3) |
| 5 | `git --version` | Git instalado (2.53.0) |
| 6 | `code --version` | VS Code instalado (1.141.0) |
| 7 | Creación del proyecto | `composer create-project` + `php artisan --version` (12.69.3) |
| 8 | `.env` + `key:generate` | Configuración del entorno y `APP_KEY` |
| 9 | phpMyAdmin | Base de datos `gestor_adso` creada |
| 10 | `php artisan migrate` | Migraciones ejecutadas + tablas en phpMyAdmin |
| 11 | `http://127.0.0.1:8000` | Pantalla de bienvenida de Laravel en el navegador |
| 12 | `git commit` | Primer commit del proyecto |
| 13 | VS Code | Proyecto abierto (`code .`) |
| 14 | Versiones | Salida real de `php -v`, `composer -V`, `php artisan --version`, `git --version`, `code --version` |

---

## 9. Problemas encontrados y solución

| Problema | Causa | Solución aplicada |
|---|---|---|
| `ERROR 2002 Can't connect to MySQL server` | MariaDB apagado | `sudo /opt/lampp/lampp startmysql` |
| MySQL iniciaba (`ok.`) pero moría al instante | La carpeta de datos pertenecía a `root` y MySQL arranca como usuario `mysql` | `sudo chown -R mysql:mysql /opt/lampp/var/mysql` |
| phpMyAdmin: *Error during session start* | `/opt/lampp/temp` no era escribible por Apache (`daemon`) | `sudo chown -R daemon:daemon /opt/lampp/temp` |

---

**Autor:** D4NX3L8 — Programa ADSO · SENA Regional Antioquia · CTMA

---

## Semana 7 — Administración de usuarios y control por roles

El modelo `User` maneja los roles `admin`, `instructor` y `aprendiz`, con los helpers
`isAdmin()`, `isInstructor()` e `isAprendiz()`. El Gate `manage-users` se define en
`AuthServiceProvider`; el grupo `admin.*` exige autenticación y ese Gate, por lo que un
instructor o aprendiz que escriba directamente una URL `/admin/users` recibe HTTP 403.
El enlace **Usuarios** se muestra únicamente cuando el usuario autenticado supera el
mismo Gate.

El CRUD de `/admin/users` incluye búsqueda por nombre/correo, filtro por rol,
paginación, creación, edición de nombre/correo/rol y contraseña opcional, además de
eliminación. La cuenta actualmente autenticada no tiene botón de eliminación y el
controlador rechaza también una petición DELETE directa, vuelve al listado y muestra:
«No puedes eliminar tu propia cuenta de administrador activa.»

### Administrador inicial

Para evitar cuentas de prueba compartidas, el seeder crea solo el administrador y lee
sus datos privados de variables locales; exige una contraseña de al menos ocho
caracteres. No se imprimen ni se incluyen contraseñas en el repositorio o en la
entrega. Configura en tu `.env` valores propios para
`ADMIN_NAME`, `ADMIN_EMAIL` y `ADMIN_PASSWORD` antes de sembrar. No compartas esos
datos. Luego ejecuta:

```bash
php artisan config:clear
php artisan migrate
php artisan db:seed --class=UserSeeder
php artisan optimize:clear
```

### Contraseña de perfil y pruebas

Desde **Mi perfil**, Breeze valida la contraseña actual, exige que la nueva tenga al
menos ocho caracteres y exige confirmación. La suite automatizada cubre el CRUD,
roles, acceso directo de instructor/aprendiz, protección contra autoeliminación,
seeder y validaciones de contraseña:

```bash
php artisan test
```

Para la verificación manual de entrega, inicia sesión con cuentas de prueba creadas
individualmente, documenta qué acciones permite cada rol y toma capturas propias sin
mostrar contraseñas. El documento de entrega debe explicar cada captura y cada
fragmento de código.
