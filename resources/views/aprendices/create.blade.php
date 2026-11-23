@extends('layouts.app')

@section('title', 'Crear Aprendiz')

@section('content')
    <div class="page-intro">
        <span class="eyebrow">NUEVO REGISTRO</span>
        <h1>Agregar aprendiz</h1>
        <p>Completa los datos para incluir a una persona en el listado.</p>
    </div>

    <div class="form-card">
        <form action="{{ route('aprendices.store') }}" method="POST">
            @include('aprendices._form', [
                'aprendiz' => $aprendiz,
                'buttonText' => 'Guardar aprendiz',
            ])
        </form>
    </div>

    <a class="back-link" href="{{ route('aprendices.index') }}">
        <span aria-hidden="true">←</span> Volver al listado
    </a>
@endsection