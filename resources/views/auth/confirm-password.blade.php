<x-guest-layout>
    <h2 class="auth-heading">Confirma tu identidad</h2>
    <p class="auth-description">Esta es una zona segura. Confirma tu contraseña para continuar.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Contraseña" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                Confirmar y continuar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
