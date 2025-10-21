@extends('layouts.app')

@section('title','Cliente — PROSERVI')
@section('page_title','  Inicio')

{{-- ===== Menu lateral (desktop) ===== --}}
@section('sidebar')
  <ul class="ui-menu">
    <li>
      <a href="{{ route('cliente.index') }}"
         class="ui-menu-link {{ request()->routeIs('cliente.index') ? 'active' : '' }}"
         title="Inicio">
        <i class="bi bi-house-door"></i> <span>Inicio</span>
      </a>
    </li>

    <li class="ui-menu-section">Servicios</li>
    <li>
      <a href="{{ route('cliente.servicios.index') }}"
         class="ui-menu-link {{ request()->routeIs('cliente.servicios.*') ? 'active' : '' }}"
         title="Explorar">
        <i class="bi bi-grid"></i> <span>Explorar</span>
      </a>
    </li>
    <li>
      <a href="{{ route('cliente.favoritos.index') }}"
         class="ui-menu-link {{ request()->routeIs('cliente.favoritos.*') ? 'active' : '' }}"
         title="Favoritos">
        <i class="bi bi-heart"></i> <span>Favoritos</span>
      </a>
    </li>

    <li class="ui-menu-section">Cuenta</li>
    <li>
      <a href="{{ route('cliente.perfil.show') }}"
         class="ui-menu-link {{ request()->routeIs('cliente.perfil.*') ? 'active' : '' }}"
         title="Mi perfil">
        <i class="bi bi-person"></i> <span>Mi perfil</span>
      </a>
    </li>
  </ul>
@endsection

{{-- ===== Menú drawer (móvil) ===== --}}
@section('drawer')
  <ul class="ui-drawer-menu">
    <li><a href="{{ route('cliente.index') }}"><i class="bi bi-house-door"></i> Inicio</a></li>
    <li><a href="{{ route('cliente.servicios.index') }}"><i class="bi bi-grid"></i> Explorar servicios</a></li>
    <li><a href="{{ route('cliente.favoritos.index') }}"><i class="bi bi-heart"></i> Favoritos</a></li>
    <li><a href="{{ route('cliente.perfil.show') }}"><i class="bi bi-person"></i> Mi perfil</a></li>
  </ul>
@endsection

{{-- ===== Tabs inferiores (móvil) ===== --}}
@section('bottombar')
  <a href="{{ route('cliente.index') }}"
     class="ui-tab {{ request()->routeIs('cliente.index') ? 'active' : '' }}">
    <i class="bi bi-house-door"></i><span>Inicio</span>
  </a>
  <a href="{{ route('cliente.servicios.index') }}"
     class="ui-tab {{ request()->routeIs('cliente.servicios.*') ? 'active' : '' }}">
    <i class="bi bi-grid"></i><span>Servicios</span>
  </a>
  <a href="{{ route('cliente.favoritos.index') }}"
     class="ui-tab {{ request()->routeIs('cliente.favoritos.*') ? 'active' : '' }}">
    <i class="bi bi-heart"></i><span>Favoritos</span>
  </a>
  <a href="{{ route('cliente.perfil.show') }}"
     class="ui-tab {{ request()->routeIs('cliente.perfil.*') ? 'active' : '' }}">
    <i class="bi bi-person"></i><span>Perfil</span>
  </a>
@endsection

{{-- ===== Dónde caerá el contenido de cada página del cliente ===== --}}
@section('content')
  <div class="container-fluid">
    @yield('cliente-content')
  </div>
@endsection