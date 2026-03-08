{{--
    components/sidebar.blade.php
    Menú lateral de AdminLTE.
    Se incluye desde layouts/app.blade.php con @include('components.sidebar').

    Los elementos del menú usan request()->routeIs() para detectar
    la ruta activa y aplicar la clase 'active' automáticamente,
    sin necesidad de lógica en el controlador.
--}}
<aside class="main-sidebar sidebar-dark-primary elevation-4">

    {{-- Brand / Logo --}}
    <a href="{{ route('dashboard') }}" class="brand-link">
        <i class="fas fa-wallet brand-image ml-2" style="font-size:1.8rem; opacity:.9"></i>
        <span class="brand-text font-weight-bold ml-2">SmartBudget</span>
    </a>

    <div class="sidebar">

        {{-- Panel del usuario autenticado --}}
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <i class="fas fa-user-circle fa-2x text-white ml-1"></i>
            </div>
            <div class="info">
                <span class="d-block text-white">{{ auth()->user()->name }}</span>
                <small class="text-white-50">{{ auth()->user()->email }}</small>
            </div>
        </div>

        {{-- Menú de navegación --}}
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">

                {{-- ── Dashboard ──────────────────────────────────────────── --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- ── Transacciones ──────────────────────────────────────── --}}
                <li class="nav-item">
                    <a href="#"
                       class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>Transacciones</p>
                    </a>
                </li>

                {{-- ── Cuentas ─────────────────────────────────────────────── --}}
                <li class="nav-item">
                    <a href="#"
                       class="nav-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-university"></i>
                        <p>Cuentas</p>
                    </a>
                </li>

                {{-- ── Categorías ──────────────────────────────────────────── --}}
                <li class="nav-item">
                    <a href="#"
                       class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Categorías</p>
                    </a>
                </li>

                {{-- ── Presupuestos ────────────────────────────────────────── --}}
                <li class="nav-item">
                    <a href="#"
                       class="nav-link {{ request()->routeIs('budgets.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-piggy-bank"></i>
                        <p>Presupuestos</p>
                    </a>
                </li>

                {{-- ── Importar CSV ─────────────────────────────────────────── --}}
                <li class="nav-item">
                    <a href="#"
                       class="nav-link {{ request()->routeIs('imports.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-csv"></i>
                        <p>Importar CSV</p>
                    </a>
                </li>

                {{-- ── Informes ─────────────────────────────────────────────── --}}
                <li class="nav-item">
                    <a href="#"
                       class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Informes</p>
                    </a>
                </li>

                {{--
                    Sección exclusiva para administradores.
                    auth()->user()->isAdmin() usa el helper definido en el modelo User.
                    Si el usuario no es admin, este bloque no se renderiza.
                --}}
                @if(auth()->user()->isAdmin())
                    <li class="nav-header">ADMINISTRACIÓN</li>

                    <li class="nav-item">
                        <a href="#"
                           class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>Gestión de usuarios</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#"
                           class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>Categorías globales</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#"
                           class="nav-link {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Logs de auditoría</p>
                        </a>
                    </li>
                @endif

            </ul>
        </nav>
    </div>
</aside>