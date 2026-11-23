<x-guest-layout>
    <h2 class="auth-heading">Verifica tu correo</h2>
    <p class="auth-description">Gracias por registrarte. Revisa tu correo y abre el enlace de verificación para continuar. Si no lo recibiste, puedes solicitar uno nuevo.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="auth-status">
            Enviamos un nuevo enlace de verificación al correo que registraste.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    Reenviar enlace de verificación
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cerrar sesión
            </button>
        </form>
    </div>
</x-guest-layout>
