<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestor ADSO')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-body">
    <header class="site-header">
        <div class="site-header__inner">
            <a class="brand" href="{{ route('aprendices.index') }}">
                <span class="brand__mark" aria-hidden="true">A</span>
                <span class="brand__text">
                    <strong>Gestor ADSO</strong>
                    <small>Formación que avanza</small>
                </span>
            </a>

            <nav class="site-nav" aria-label="Navegación principal">
                <a class="site-nav__link {{ request()->routeIs('aprendices.*') ? 'is-active' : '' }}"
                    href="{{ route('aprendices.index') }}">Aprendices</a>

                @auth
                    <span class="user-greeting">
                        <span class="user-greeting__dot" aria-hidden="true"></span>
                        Hola, {{ auth()->user()->name }}
                    </span>

                    @if (Route::has('profile.edit'))
                        <a class="site-nav__link {{ request()->routeIs('profile.*') ? 'is-active' : '' }}"
                            href="{{ route('profile.edit') }}">Mi perfil</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="button button--quiet" type="submit">Cerrar sesión</button>
                    </form>
                @endauth

                @guest
                    <a class="site-nav__link" href="{{ route('login') }}">Iniciar sesión</a>
                    <a class="button button--small" href="{{ route('register') }}">Crear cuenta</a>
                @endguest
            </nav>
        </div>
    </header>

    <main class="page-shell">
        @if (session('ok'))
            <div class="notice notice--success" role="status">
                <span class="notice__icon" aria-hidden="true">✓</span>
                {{ session('ok') }}
            </div>
        @endif

        @if (session('error'))
            <div class="notice notice--error" role="alert">{{ session('error') }}</div>
        @endif

        @isset($header)
            <div class="page-slot-header">{{ $header }}</div>
        @endisset

        @isset($slot)
            {{ $slot }}
        @else
            @yield('content')
        @endisset
    </main>

    <footer class="site-footer">
        <span>Gestor ADSO</span>
        <span>Aprender · Crear · Avanzar</span>
    </footer>
</body>
</html>
