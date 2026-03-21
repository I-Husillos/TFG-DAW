@extends('layouts.app')

@section('title', 'Nuevo presupuesto')

@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('budgets.index') }}">Presupuestos</a>
</li>
<li class="breadcrumb-item active">Nuevo</li>
@endpush

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-plus mr-1"></i> Nuevo presupuesto
        </h3>
    </div>
    <form action="{{ route('budgets.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="category_id">
                            Categoría <span class="text-danger">*</span>
                        </label>
                        <select name="category_id" id="category_id"
                            data-category-select
                            data-placeholder="Buscar categoría..."
                            class="form-control @error('category_id') is-invalid @enderror">
                            <option value="">Selecciona una categoría de gasto</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->display_name ?? $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="limit_amount">
                            Límite de gasto <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" name="limit_amount" id="limit_amount"
                                step="0.01" min="0.01"
                                class="form-control @error('limit_amount') is-invalid @enderror"
                                value="{{ old('limit_amount') }}"
                                placeholder="0,00">
                            <div class="input-group-append">
                                <span class="input-group-text">€</span>
                            </div>
                            @error('limit_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="period_year">
                            Año <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="period_year" id="period_year"
                            class="form-control @error('period_year') is-invalid @enderror"
                            value="{{ old('period_year', now()->year) }}"
                            min="2000" max="2100">
                        @error('period_year')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="period_month">
                            Mes <span class="text-danger">*</span>
                        </label>
                        <select name="period_month" id="period_month"
                            class="form-control @error('period_month') is-invalid @enderror">
                            @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}"
                                {{ old('period_month', now()->month) == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                            @endforeach
                        </select>
                        @error('period_month')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="alert_threshold">
                            Umbral de alerta
                        </label>
                        <div class="input-group">
                            <input type="number" name="alert_threshold"
                                id="alert_threshold"
                                step="0.05" min="0.05" max="1"
                                class="form-control @error('alert_threshold') is-invalid @enderror"
                                value="{{ old('alert_threshold', 0.80) }}">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    (0.80 = 80%)
                                </span>
                            </div>
                            @error('alert_threshold')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="form-text text-muted">
                            Se enviará una alerta cuando alcances este porcentaje del límite.
                        </small>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Guardar presupuesto
            </button>
            <a href="{{ route('budgets.index') }}" class="btn btn-secondary ml-2">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection