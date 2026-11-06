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
    <label for="nombre">Nombre:</label>
    <input
        id="nombre"
        type="text"
        name="nombre"
        value="{{ old('nombre', $aprendiz->nombre) }}"
        maxlength="120"
    >
</div>

<div class="field">
    <label for="documento">Documento:</label>
    <input
        id="documento"
        type="text"
        name="documento"
        value="{{ old('documento', $aprendiz->documento) }}"
        maxlength="40"
    >
</div>

<div class="field">
    <label for="correo">Correo:</label>
    <input
        id="correo"
        type="email"
        name="correo"
        value="{{ old('correo', $aprendiz->correo) }}"
        maxlength="120"
    >
</div>

<div class="field">
    <label for="ficha_id">Ficha:</label>
    <input
        id="ficha_id"
        type="number"
        name="ficha_id"
        value="{{ old('ficha_id', $aprendiz->ficha_id) }}"
    >
</div>

<button type="submit">
    {{ $buttonText ?? 'Guardar' }}
</button>