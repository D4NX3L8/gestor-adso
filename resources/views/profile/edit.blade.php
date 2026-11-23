<x-app-layout>
    <x-slot name="header">
        <div class="page-intro">
            <span class="eyebrow">TU CUENTA</span>
            <h1 class="page-title">Mi perfil</h1>
            <p class="page-subtitle">Administra tus datos y preferencias de seguridad.</p>
        </div>
    </x-slot>

    <div class="profile-grid">
        <section class="profile-card">
            @include('profile.partials.update-profile-information-form')
        </section>

        <section class="profile-card">
            @include('profile.partials.update-password-form')
        </section>

        <section class="profile-card profile-card--danger">
            @include('profile.partials.delete-user-form')
        </section>
    </div>
</x-app-layout>
