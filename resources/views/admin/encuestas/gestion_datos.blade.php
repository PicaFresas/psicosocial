@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- TÍTULO --}}
    <div class="mb-4">
        <h2 class="fw-bold">Gestión de Datos</h2>
        <p class="text-muted">
            Auditoría del sistema, depuración y control administrativo.
        </p>
    </div>

    {{-- ACCIONES --}}
    <div class="d-flex gap-2 mb-4">

        {{-- EXPORTAR JSON --}}
        <a href="{{ route('admin.encuestas.auditoria.json') }}"
           class="btn btn-outline-primary">
            📥 Exportar auditoría (JSON)
        </a>

        {{-- LIMPIAR --}}
        <form method="POST"
              action="{{ route('admin.encuestas.auditoria.limpiar') }}"
              onsubmit="return confirm('¿Eliminar registros de auditoría antiguos?')">
            @csrf
            <button class="btn btn-outline-danger">
                Depurar auditoría antigua
            </button>
        </form>

    </div>

    {{-- TABLA --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-striped mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Acción</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>

                @forelse($auditorias as $auditoria)
                    <tr>
                        <td>
                            {{ $auditoria->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            {{ $auditoria->user->name ?? 'Sistema' }}
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                {{ strtoupper($auditoria->user->role ?? 'SYS') }}
                            </span>
                        </td>

                        <td>
                            <span class="badge bg-info text-dark">
                                {{ $auditoria->accion }}
                            </span>
                        </td>
                        <td>{{ $auditoria->descripcion }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No hay registros de auditoría.
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection
