@extends('layouts.app')

@section('title', 'Presupuestos')

@push('breadcrumb')
    <li class="breadcrumb-item active">Presupuestos</li>
@endpush

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-wallet mr-1"></i> Presupuestos
        </h3>
        <div class="card-tools">
            <a href="{{ route('budgets.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus mr-1"></i> Nuevo presupuesto
            </a>
        </div>
    </div>
    <div class="card-body">

        <div class="row mb-3">
            <div class="col-md-2 col-sm-6 mb-2">
                <select id="filter-year" class="form-control">
                    @foreach(range(2020, now()->year + 1) as $y)
                        <option value="{{ $y }}"
                            {{ $y == now()->year ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-sm-6 mb-2">
                <select id="filter-month" class="form-control">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}"
                            {{ $m == now()->month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <button id="clear-filters" class="btn btn-secondary btn-block">
                    <i class="fas fa-times mr-1"></i> Limpiar filtros
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="tabla-presupuestos"
                   class="table table-hover table-striped table-bordered mb-0 text-center dt-responsive"
                   data-api-url="{{ route('api.budgets.index') }}">
                <thead class="text-center bg-white font-weight-bold">
                    <tr>
                        <th>Categoría</th>
                        <th>Período</th>
                        <th>Gastado</th>
                        <th>Límite</th>
                        <th>Progreso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</div>

@endsection