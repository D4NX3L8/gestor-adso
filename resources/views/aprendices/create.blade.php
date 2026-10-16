@extends('layouts.app')
@section('title','Nuevo Aprendiz')
@section('content')
<h1>Nuevo Aprendiz</h1>
<form action="{{ route('aprendices.store') }}" method="POST">
    @csrf
    <label>Nombre <input name="nombre" required></label><br>
    <label>Documento <input name="documento" required></label><br>
    <label>Correo <input type="email" name="correo" required></label><br>
    <button>Guardar</button>
</form>
@endsection

