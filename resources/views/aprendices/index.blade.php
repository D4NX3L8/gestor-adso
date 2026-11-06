@extends('layouts.app')

@section('title', 'Aprendices')

@section('content')
    <h1>Listado de Aprendices</h1>

    <div class="toolbar">
        <a class="button" href="{{ route('aprendices.create') }}">
            + Nuevo aprendiz
        </a>

        <form
            class="search-form"
            action="{{ route('aprendices.index') }}"
            method="GET"
        >
            <input
                type="text"
                name="q"
                value="{{ $q }}"
                placeholder="Buscar por nombre o documento"
            >
            <button type="submit">Buscar</button>

            @if ($q !== '')
                <a href="{{ route('aprendices.index') }}">Limpiar</a>
            @endif
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Documento</th>
                <th>Correo</th>
                <th>Ficha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($aprendices as $aprendiz)
                <tr>
                    <td>{{ $aprendiz->id }}</td>
                    <td>{{ $aprendiz->nombre }}</td>
                    <td>{{ $aprendiz->documento }}</td>
                    <td>{{ $aprendiz->correo }}</td>
                    <td>{{ $aprendiz->ficha_id ?? 'Sin ficha' }}</td>
                    <td class="actions">
                        <a href="{{ route('aprendices.edit', $aprendiz) }}">
                            Editar
                        </a>

                        <form
                            action="{{ route('aprendices.destroy', $aprendiz) }}"
                            method="POST"
                            onsubmit="return confirm('¿Eliminar este aprendiz?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="danger-button">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        {{ $q !== '' ? 'No se encontraron aprendices.' : 'No hay aprendices registrados.' }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $aprendices->links() }}
    </div>
@endsection