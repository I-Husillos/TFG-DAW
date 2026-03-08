{{--
    components/navbar.blade.php
    Barra de navegación superior de AdminLTE.
    Se incluye desde layouts/app.blade.php con @include('components.navbar').
--}}
<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    {{-- Botón para colapsar/expandir el sidebar --}}
    <ul class="navbar-nav">
        <li class="nav-item">
            {{--
                data-widget="pushmenu" es el atributo de AdminLTE
                que activa el toggle del sidebar. No necesita JS propio.
            --}}
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    {{-- Elementos de la derecha --}}
    <ul class="navbar-nav ml-auto">

        {{-- Nombre y rol del usuario autenticado --}}
        <li class="nav-item d-none d-sm-inline-block">
            <span class="nav-link text-muted">
                <i class="fas fa-user mr-1"></i>
                {{ auth()->user()->name }}
                <span class="badge badge-{{ auth()->user()->isAdmin() ? 'danger' : 'primary' }} ml-1">
                    {{ auth()->user()->role }}
                </span>
            </span>
        </li>

        {{-- Separador visual --}}
        <li class="nav-item d-none d-sm-inline-block">
            <span class="nav-link text-muted">|</span>
        </li>

        {{-- Logout --}}
        <li class="nav-item">
            {{--
                Usamos un formulario POST visible en lugar de JS inline
                (onclick="submit()") que usa tickets-main.
                Es más limpio, no mezcla JS en HTML y es igual de funcional.
                data-dismiss no es necesario aquí — es un submit directo.
            --}}
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="nav-link btn btn-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="d-none d-sm-inline ml-1">Salir</span>
                </button>
            </form>
        </li>

    </ul>
</nav>