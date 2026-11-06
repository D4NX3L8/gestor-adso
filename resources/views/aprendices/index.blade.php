@extends('layouts.app')
@section('title','Aprendices')
@section('content')
<h1>Aprendices</h1>
<p><a href="{{ route('aprendices.create') }}">+ Nuevo</a></p>

<form action="{{ route('aprendices.index') }}" method="GET">
    <label for="q">Buscar por nombre</label>
    <input id="q" name="q" value="{{ $q }}">
    <button type="submit">Buscar</button>
    @if($q !== '')
        <a href="{{ route('aprendices.index') }}">Limpiar búsqueda</a>
    @endif
</form>

@if($aprendices->isEmpty())
    <p>{{ $q !== '' ? 'No se encontraron aprendices.' : 'No hay aprendices registrados.' }}</p>
@else
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Documento</th>
                <th>Correo</th>
                <th>Ficha</th>
                <th>Creado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($aprendices as $a)
                <tr>
                    <td>{{ $a->id }}</td>
                    <td>{{ $a->nombre }}</td>
                    <td>{{ $a->documento }}</td>
                    <td>{{ $a->correo }}</td>
                    <td>{{ $a->ficha_id ?? '-' }}</td>
                    <td>{{ $a->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $aprendices->links() }}
@endif
@endsection
