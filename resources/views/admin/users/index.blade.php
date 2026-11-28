@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
    <div class="page-intro">
        <span class="eyebrow">ADMINISTRACIÓN</span>
        <h1>Usuarios</h1>
        <p>Busca cuentas, filtra por rol y administra sus permisos de acceso.</p>
    </div>

    <div class="toolbar">
        <a class="button" href="{{ route('admin.users.create') }}">＋ Nuevo usuario</a>

        <form class="search-form" action="{{ route('admin.users.index') }}" method="GET">
            <input
                type="text"
                name="q"
                value="{{ $q }}"
                placeholder="Nombre o correo"
                aria-label="Buscar por nombre o correo"
            >
            <select name="role" aria-label="Filtrar por rol">
                <option value="">Todos los roles</option>
                @foreach ($roles as $value => $label)
                    <option value="{{ $value }}" @selected($role === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit">Buscar</button>
            @if ($q !== '' || $role !== '')
                <a href="{{ route('admin.users.index') }}">Limpiar</a>
            @endif
        </form>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $listedUser)
                    <tr>
                        <td class="record-id">#{{ $listedUser->id }}</td>
                        <td>{{ $listedUser->name }}</td>
                        <td>{{ $listedUser->email }}</td>
                        <td>{{ $roles[$listedUser->role] ?? $listedUser->role }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.users.edit', $listedUser) }}">Editar</a>
                            @if (auth()->user()->is($listedUser))
                                <span class="data-muted">Cuenta activa</span>
                            @else
                                <form
                                    action="{{ route('admin.users.destroy', $listedUser) }}"
                                    method="POST"
                                    onsubmit="return confirm('¿Eliminar este usuario?')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <strong>No encontramos usuarios</strong>
                                <span>Prueba con otro nombre, correo o rol.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $users->links() }}
    </div>
@endsection
