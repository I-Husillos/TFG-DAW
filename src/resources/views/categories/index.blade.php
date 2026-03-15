@extends('layouts.app')

@section('title', 'Categorías')

@push('breadcrumb')
    <li class="breadcrumb-item active">Categorías</li>
@endpush

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-tags mr-1"></i> Categorías
        </h3>
        <div class="card-tools">
            <a href="{{ route('categories.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus mr-1"></i> Nueva categoría
            </a>
        </div>
    </div>
    <div class="card-body">

        <div class="row mb-3">
            <div class="col-md-3 col-sm-6 mb-2">
                <select id="filter-type" class="form-control">
                    <option value="">Tipo: Todos</option>
                    <option value="income">Ingresos</option>
                    <option value="expense">Gastos</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <button id="clear-filters" class="btn btn-secondary btn-block">
                    <i class="fas fa-times mr-1"></i> Limpiar filtros
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="tabla-categorias"
                   class="table table-hover table-striped table-bordered mb-0 text-center dt-responsive"
                   data-api-url="{{ route('api.categories.index') }}">
                <thead class="text-center bg-white font-weight-bold">
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Subcategorías</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</div>

@endsection