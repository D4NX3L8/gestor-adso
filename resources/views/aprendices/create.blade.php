@extends('layouts.app')

@section('title', 'Crear Aprendiz')

@section('content')
    <h1>Crear Aprendiz</h1>

    <form action="{{ route('aprendices.store') }}" method="POST">
        @include('aprendices._form', [
            'aprendiz' => $aprendiz,
            'buttonText' => 'Guardar aprendiz',
        ])
    </form>

    <p>
        <a href="{{ route('aprendices.index') }}">Volver al listado</a>
    </p>
@endsection