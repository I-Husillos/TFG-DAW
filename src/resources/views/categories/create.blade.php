@extends('layouts.app')

@section('title', 'Nueva categoría')

@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('categories.index') }}">Categorías</a>
</li>
<li class="breadcrumb-item active">Nueva</li>
@endpush

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-plus mr-1"></i> Nueva categoría
        </h3>
    </div>
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Ej: Alimentación">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="display_name">Nombre visible</label>
                        <input type="text" name="display_name" id="display_name"
                            class="form-control @error('display_name') is-invalid @enderror"
                            value="{{ old('display_name') }}"
                            placeholder="Ej: Alimentación y supermercado">
                        <small class="form-text text-muted">
                            Nombre que se mostrará en la app. Si se deja vacío se usará el nombre.
                        </small>
                        @error('display_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="type">Tipo <span class="text-danger">*</span></label>
                        <select name="type" id="type"
                            class="form-control @error('type') is-invalid @enderror">
                            <option value="">Selecciona un tipo</option>
                            <option value="income" {{ old('type') === 'income'  ? 'selected' : '' }}>Ingreso</option>
                            <option value="expense" {{ old('type') === 'expense' ? 'selected' : '' }}>Gasto</option>
                        </select>
                        @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="parent_id">Categoría padre</label>
                        <select name="parent_id" id="parent_id"
                            class="form-control @error('parent_id') is-invalid @enderror">
                            <option value="">Sin categoría padre (primer nivel)</option>
                            @foreach($parents as $parent)
                            <option value="{{ $parent->id }}"
                                {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                [{{ $parent->type === 'income' ? 'Ingreso' : 'Gasto' }}]
                                {{ $parent->display_name ?? $parent->name }}
                            </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            Selecciona una categoría padre para crear una subcategoría.
                        </small>
                        @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <textarea name="description" id="description" rows="2"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Descripción opcional de la categoría...">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Guardar categoría
            </button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary ml-2">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection