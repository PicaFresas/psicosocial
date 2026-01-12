@extends('layouts.app')

@section('content')
<style>
    .imss-title {
        font-weight: 900;
        color: #13322B;
        letter-spacing: 1px;
    }

    .imss-subtitle {
        color: #6B7280;
        font-size: 14px;
    }

    .imss-card {
        border-radius: 18px;
        border: none;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        background: white;
    }

    .nav-tabs .nav-link {
        border: none;
        font-weight: bold;
        color: #6B7280;
        padding: 12px 20px;
    }

    .nav-tabs .nav-link.active {
        color: #13322B;
        border-bottom: 3px solid #98984A;
        background: transparent;
    }

    .imss-label {
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: 1px;
        color: #13322B;
    }

    .imss-input {
        border-radius: 10px;
        padding: 12px;
        border: 1px solid #D1D5DB;
    }

    .imss-btn {
        background: #13322B;
        color: white;
        font-weight: bold;
        border-radius: 10px;
        padding: 10px 18px;
    }

    .imss-btn:hover {
        background: #1A463D;
        color: white;
    }

    .table thead {
        background: #F4F7F6;
    }

    .table th {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #374151;
    }

    .badge-imss {
        background: #13322B;
    }

    .badge-inactivo {
        background: #9CA3AF;
    }
</style>

<div class="container py-4">

    {{-- ENCABEZADO --}}
    <div class="mb-4">
        <h2 class="imss-title">Panel de Administración</h2>
        <p class="imss-subtitle">
            Bienvenido, <strong>{{ auth()->user()->name }}</strong> ·
            Sistema NOM-035-STPS-2018
        </p>
    </div>

    {{-- MENSAJES --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- TABS --}}
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#crear">
                Crear encargado
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#lista">
                Encargados registrados
            </button>
        </li>
    </ul>

    <div class="tab-content">

        {{-- CREAR ENCARGADO --}}
        <div class="tab-pane fade show active" id="crear">
            <div class="card imss-card">
                <div class="card-body p-4">

                    <h5 class="imss-title mb-3">Alta de encargado autorizado</h5>

                    <form method="POST" action="{{ route('admin.encargados.store') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="imss-label">Nombre completo</label>
                                <input type="text" name="name" class="form-control imss-input" required>
                            </div>

                            <div class="col-md-6">
                                <label class="imss-label">Correo institucional</label>
                                <input type="email" name="email" class="form-control imss-input" required>
                            </div>

                            <div class="col-md-6">
                                <label class="imss-label">Contraseña</label>
                                <input type="password" name="password" class="form-control imss-input" required>
                            </div>

                            <div class="col-md-6">
                                <label class="imss-label">Confirmar contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control imss-input" required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button class="btn imss-btn">
                                Registrar encargado
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- LISTA --}}
        <div class="tab-pane fade" id="lista">
            <div class="card imss-card">
                <div class="card-body p-4">

                    <h5 class="imss-title mb-3">Encargados registrados</h5>

                    @if($encargados->count())
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($encargados as $encargado)
                                    <tr>
                                        <td>{{ $encargado->name }}</td>
                                        <td>{{ $encargado->email }}</td>
                                        <td>
                                            @if($encargado->active)
                                                <span class="badge badge-imss">Activo</span>
                                            @else
                                                <span class="badge badge-inactivo">Inactivo</span>
                                            @endif
                                        </td>

                                        <td class="d-flex gap-2 justify-content-center">

                                            <form method="POST"
                                                  action="{{ route('admin.users.toggle', $encargado) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm {{ $encargado->active ? 'btn-warning' : 'btn-success' }}">
                                                    {{ $encargado->active ? 'Desactivar' : 'Activar' }}
                                                </button>
                                            </form>

                                            <a href="{{ route('admin.encargados.edit', $encargado) }}"
                                               class="btn btn-sm btn-primary">
                                                Editar
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('admin.encargados.destroy', $encargado) }}"
                                                  onsubmit="return confirm('¿Eliminar este encargado?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    Eliminar
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted mb-0">
                            No hay encargados registrados.
                        </p>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
