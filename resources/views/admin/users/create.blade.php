@extends('layouts.app')

@section('title', 'Crear usuario')

@section('content')
    <div class="page-intro">
        <span class="eyebrow">ADMINISTRACIÓN</span>
        <h1>Crear usuario</h1>
        <p>Registra una cuenta y asigna su rol de acceso.</p>
    </div>

    <div class="form-card">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @include('admin.users._form', ['buttonText' => 'Crear usuario'])
        </form>
    </div>

    <a class="back-link" href="{{ route('admin.users.index') }}">← Volver a usuarios</a>
@endsection
