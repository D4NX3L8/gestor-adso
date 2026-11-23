<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestor ADSO | Acceso</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="guest-body">
    <main class="guest-layout">
        <section class="guest-aside">
            <a class="brand brand--light" href="/">
                <span class="brand__mark" aria-hidden="true">A</span>
                <span class="brand__text">
                    <strong>Gestor ADSO</strong>
                    <small>Formación que avanza</small>
                </span>
            </a>

            <div class="guest-aside__message">
                <span class="eyebrow eyebrow--light">APRENDER · CREAR · AVANZAR</span>
                <h1>Tu camino de aprendizaje, en un solo lugar.</h1>
                <p>Ingresa al Gestor ADSO para continuar con tu proceso de formación.</p>
            </div>

            <div class="guest-aside__footer">
                <span class="guest-aside__spark" aria-hidden="true">✦</span>
                <span>Una herramienta para crecer en comunidad.</span>
            </div>
        </section>

        <section class="guest-content">
            <div class="auth-card">
                <div class="auth-card__topline">
                    <span class="eyebrow">ESPACIO DE USUARIO</span>
                    <span class="auth-card__accent" aria-hidden="true"></span>
                </div>
                {{ $slot }}
            </div>
            <p class="guest-content__footnote">Gestor ADSO <span>·</span> Acceso seguro a tu espacio</p>
        </section>
    </main>
</body>
</html>
