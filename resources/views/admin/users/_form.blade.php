@if ($errors->any())
    <div class="danger">
        <strong>Se encontraron errores:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@csrf

<div class="field">
    <label for="name">Nombre</label>
    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" maxlength="255" required>
    @error('name') <span class="danger">{{ $message }}</span> @enderror
</div>

<div class="field">
    <label for="email">Correo</label>
    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" maxlength="255" required>
    @error('email') <span class="danger">{{ $message }}</span> @enderror
</div>

<div class="field">
    <label for="role">Rol</label>
    <select id="role" name="role" required>
        @foreach ($roles as $value => $label)
            <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
        @endforeach
    </select>
    @error('role') <span class="danger">{{ $message }}</span> @enderror
</div>

<div class="field">
    <label for="password">{{ $user->exists ? 'Nueva contraseña (opcional)' : 'Contraseña inicial' }}</label>
    <input
        id="password"
        type="password"
        name="password"
        autocomplete="new-password"
        @unless ($user->exists) required @endunless
    >
    @error('password') <span class="danger">{{ $message }}</span> @enderror
</div>

<div class="field">
    <label for="password_confirmation">Confirmar contraseña</label>
    <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
</div>

<button type="submit">{{ $buttonText }}</button>
