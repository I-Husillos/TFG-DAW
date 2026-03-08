{{--
    Vista del dashboard principal.
    Extiende layouts/app.blade.php que incluye navbar y sidebar.
    Solo define el contenido específico de esta página.
--}}
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="row">

        {{-- Tarjeta: Total ingresos (placeholder hasta implementar lógica) --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>0,00 €</h3>
                    <p>Ingresos este mes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Ver detalle <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Tarjeta: Total gastos --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>0,00 €</h3>
                    <p>Gastos este mes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Ver detalle <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Tarjeta: Balance --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>0,00 €</h3>
                    <p>Balance del mes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Ver cuentas <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Tarjeta: Transacciones --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>0</h3>
                    <p>Transacciones este mes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Ver todas <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

    </div>

    {{-- Fila de placeholders para gráficos (se rellenarán en la Fase F) --}}
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Ingresos vs Gastos — últimos 6 meses
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted text-center py-5">
                        <i class="fas fa-chart-line fa-3x mb-3 d-block"></i>
                        Los gráficos se implementarán en la fase de Dashboard e Informes.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tags mr-1"></i>
                        Top categorías de gasto
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted text-center py-5">
                        <i class="fas fa-tags fa-3x mb-3 d-block"></i>
                        Disponible cuando haya transacciones registradas.
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection