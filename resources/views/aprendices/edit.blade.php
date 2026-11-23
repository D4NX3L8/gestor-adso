@extends('layouts.app')

@section('title', 'Editar Aprendiz')

@section('content')
    <div class="page-intro">
        <span class="eyebrow">ACTUALIZAR REGISTRO</span>
        <h1>Editar aprendiz</h1>
        <p>Actualiza la información y guarda los cambios del registro.</p>
    </div>

    <div class="form-card">
        <form
            action="{{ route('aprendices.update', $aprendiz) }}"
            method="POST"
        >
            @method('PUT')

            @include('aprendices._form', [
                'aprendiz' => $aprendiz,
                'buttonText' => 'Guardar cambios',
            ])
        </form>
    </div>

    <a class="back-link" href="{{ route('aprendices.index') }}">
        <span aria-hidden="true">←</span> Volver al listado
    </a>
@endsection