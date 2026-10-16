# GESTOR ADSO

Proyecto integrador **Gestor ADSO v01** del programa **ADSO – SENA Regional Antioquia (CTMA)**.
Aplicación web desarrollada con **PHP y Laravel** que servirá como base del proyecto ABP del trimestre y crecerá progresivamente durante las siguientes semanas.

- **Resultado de aprendizaje:** RA EL02 · NORMA 220501094 — *Elaborar propuesta técnica del software según especificaciones*.
- **Instructor:** Gustavo Bolaños.
- **Semana 1:** Instalación del entorno y primer arranque.
- **Semana 2:** Routing en `web.php` y controladores REST (módulo Aprendices).

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
