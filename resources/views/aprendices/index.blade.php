@extends('layouts.app')

@section('title', 'Aprendices')

@section('content')
    <div class="page-intro">
        <span class="eyebrow">GESTIÓN ACADÉMICA</span>
        <h1>Aprendices</h1>
        <p>Consulta y organiza la información de tu comunidad de formación.</p>
    </div>

    <div class="toolbar">
        @can('create', App\Models\Aprendiz::class)
            <a class="button" href="{{ route('aprendices.create') }}">
                <span aria-hidden="true">＋</span> Nuevo aprendiz
            </a>
        @endcan

        <form
            class="search-form"
            action="{{ route('aprendices.index') }}"
            method="GET"
        >
            <input
                type="text"
                name="q"
                value="{{ $q }}"
                placeholder="Nombre o documento"
                aria-label="Buscar por nombre o documento"
            >
            <button type="submit">Buscar</button>

            @if ($q !== '')
                <a href="{{ route('aprendices.index') }}">Limpiar</a>
            @endif
        </form>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Aprendiz</th>
                    <th>Documento</th>
                    <th>Correo</th>
                    <th>Ficha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($aprendices as $aprendiz)
                    <tr>
                        <td class="record-id">#{{ $aprendiz->id }}</td>
                        <td>
                            <div class="person-cell">
                                <span class="person-cell__avatar" aria-hidden="true">
                                    {{ strtoupper(substr($aprendiz->nombre, 0, 1)) }}
                                </span>
                                {{ $aprendiz->nombre }}
                            </div>
                        </td>
                        <td>{{ $aprendiz->documento }}</td>
                        <td>{{ $aprendiz->correo }}</td>
                        <td class="{{ $aprendiz->ficha_id ? '' : 'data-muted' }}">
                            {{ $aprendiz->ficha_id ?? 'Sin ficha' }}
                        </td>
                        <td class="actions">
                            @can('update', $aprendiz)
                                <a href="{{ route('aprendices.edit', $aprendiz) }}">Editar</a>
                            @endcan

                            @can('delete', $aprendiz)
                                <form
                                    action="{{ route('aprendices.destroy', $aprendiz) }}"
                                    method="POST"
                                    onsubmit="return confirm('¿Eliminar este aprendiz?')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Eliminar</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <strong>{{ $q !== '' ? 'No encontramos coincidencias' : 'Aún no hay aprendices' }}</strong>
                                <span>{{ $q !== '' ? 'Prueba con otro nombre o documento.' : 'Cuando registres aprendices, aparecerán aquí.' }}</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $aprendices->links() }}
    </div>
@endsection