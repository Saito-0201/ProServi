{{-- resources/views/layouts/prestador.blade.php --}}
@extends('layouts.app')

@section('title', 'Prestador — PROSERVI')
@section('page_title', trim($__env->yieldContent('page_title')))

{{-- Sidebar --}}
@section('sidebar')
  <ul class="ui-menu">
    <li>
      <a href="{{ route('prestador.index') }}"
         class="ui-menu-link {{ request()->routeIs('prestador.index') ? 'active' : '' }}">
        <i class="bi bi-house-door"></i> <span>Inicio</span>
      </a>
    </li>

    <li class="ui-menu-section">Servicios</li>
    <li>
      <a href="{{ route('prestador.servicios.index') }}"
         class="ui-menu-link {{ request()->routeIs('prestador.servicios.index') ? 'active' : '' }}">
        <i class="bi bi-card-checklist"></i> <span>Mis servicios</span>
      </a>
    </li>
    <li>
      <a href="{{ route('prestador.servicios.create') }}"
         class="ui-menu-link {{ request()->routeIs('prestador.servicios.create') ? 'active' : '' }}">
        <i class="bi bi-plus-circle"></i> <span>Crear servicio</span>
      </a>
    </li>

    <li class="ui-menu-section">Cuenta</li>
    <li>
      <a href="{{ route('prestador.perfil.show') }}"
         class="ui-menu-link {{ request()->routeIs('prestador.perfil.*') ? 'active' : '' }}">
        <i class="bi bi-person"></i> <span>Mi perfil</span>
      </a>
    </li>
  </ul>
@endsection

{{-- Drawer móvil --}}
@section('drawer')
  <ul class="ui-drawer-menu">
    <li><a href="{{ route('prestador.index') }}"><i class="bi bi-house-door"></i> Inicio</a></li>
    <li><a href="{{ route('prestador.servicios.index') }}"><i class="bi bi-card-checklist"></i> Mis servicios</a></li>
    <li><a href="{{ route('prestador.servicios.create') }}"><i class="bi bi-plus-circle"></i> Crear servicio</a></li>
    <li><a href="{{ route('prestador.perfil.show') }}"><i class="bi bi-person"></i> Mi perfil</a></li>
  </ul>
@endsection

{{-- Tabs móvil --}}
@section('bottombar')
  <a href="{{ route('prestador.index') }}" class="ui-tab {{ request()->routeIs('prestador.index') ? 'active' : '' }}">
    <i class="bi bi-house-door"></i><span>Inicio</span>
  </a>
  <a href="{{ route('prestador.servicios.index') }}" class="ui-tab {{ request()->routeIs('prestador.servicios.*') ? 'active' : '' }}">
    <i class="bi bi-card-checklist"></i><span>Servicios</span>
  </a>
  <a href="{{ route('prestador.servicios.create') }}" class="ui-tab {{ request()->routeIs('prestador.servicios.create') ? 'active' : '' }}">
    <i class="bi bi-plus-circle"></i><span>Crear</span>
  </a>
  <a href="{{ route('prestador.perfil.show') }}" class="ui-tab {{ request()->routeIs('prestador.perfil.*') ? 'active' : '' }}">
    <i class="bi bi-person"></i><span>Perfil</span>
  </a>
@endsection

@section('content')
  <div class="container-fluid">
    @yield('prestador-content')
  </div>
@endsection
