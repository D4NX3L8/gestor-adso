@extends('layouts.app')

@section('title', 'Editar Aprendiz')

@section('content')
    <h1>Editar Aprendiz</h1>

    <form
        action="{{ route('aprendices.update', $aprendiz) }}"
        method="POST"
    >
        @method('PUT')

        @include('aprendices._form', [
            'aprendiz' => $aprendiz,
            'buttonText' => 'Actualizar aprendiz',
        ])
    </form>

    <p>
        <a href="{{ route('aprendices.index') }}">Volver al listado</a>
    </p>
@endsection