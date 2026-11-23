<x-app-layout>
    <x-slot name="header">
        <div class="page-intro">
            <span class="eyebrow">GESTOR ADSO</span>
            <h1 class="page-title">Panel de inicio</h1>
            <p class="page-subtitle">Tu espacio de trabajo está listo.</p>
        </div>
    </x-slot>

    <section class="dashboard-card">
        <span class="dashboard-card__icon" aria-hidden="true">✦</span>
        <div>
            <h2>¡Qué bueno tenerte aquí, {{ auth()->user()->name }}!</h2>
            <p>Ya iniciaste sesión. Desde aquí puedes entrar al módulo de aprendices y continuar gestionando la información.</p>
            <a class="back-link" href="{{ route('aprendices.index') }}">Ir a aprendices <span aria-hidden="true">→</span></a>
        </div>
</x-app-layout>
