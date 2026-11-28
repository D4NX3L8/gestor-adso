@extends('layouts.app')

@section('title', 'Editar usuario')

@section('content')
    <div class="page-intro">
        <span class="eyebrow">ADMINISTRACIÓN</span>
        <h1>Editar usuario</h1>
        <p>Actualiza el nombre, correo, rol o establece una nueva contraseña.</p>
    </div>

    <div class="form-card">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @method('PUT')
            @include('admin.users._form', ['buttonText' => 'Guardar cambios'])
        </form>
    </div>

    <a class="back-link" href="{{ route('admin.users.index') }}">← Volver a usuarios</a>
@endsection
