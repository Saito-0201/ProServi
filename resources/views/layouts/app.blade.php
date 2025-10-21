<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','PROSERVI')</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/app-ui.css') }}">
  <style>
    :root{ 
      --ui-rail: 84px; 
      --ui-primary: #3b82f6;
      --ui-primary-600: #2563eb;
      --ui-border: rgba(15,23,42,.08);
    }
    
    /* transición suave al colapsar */
    .ui-sidebar{ transition: width .2s ease, min-width .2s ease; }
    body.with-sidebar .ui-main{ margin-left: 280px; transition: margin-left .2s ease; }
    body.sidebar-collapsed .ui-main{ margin-left: var(--ui-rail); }
    body.sidebar-collapsed .ui-sidebar{ width: var(--ui-rail); min-width: var(--ui-rail); }
    
    /* ocultar textos en modo colapsado */
    body.sidebar-collapsed .sidebar-brand-text,
    body.sidebar-collapsed .ui-menu-link span,
    body.sidebar-collapsed .ui-menu-section,
    body.sidebar-collapsed .ui-sidebar-footer{ display: none !important; }
    
    body.sidebar-collapsed .ui-menu-link{ 
      justify-content: center; 
      gap: .25rem; 
      padding: .6rem 0; 
    }
    
    /* botón de colapso (desktop) */
    .rail-toggle{
      border: 1px solid var(--ui-border); 
      background: #fff; 
      color: #475569;
      width: 36px; 
      height: 36px; 
      border-radius: .7rem; 
      display: inline-flex; 
      align-items: center; 
      justify-content: center;
      transition: all 0.2s ease;
    }
    .rail-toggle:hover{ background: #f8fafc }
    
    /* hamburguesa (móvil) */
    .btn-hamb{ 
      width: 36px;
      height: 36px; 
      border-radius: .7rem; 
      border: 0; 
      background: #fff; 
      color: #334155; 
    }
    .btn-hamb i{ font-size: 1.1rem }
    
    /* Dropdown arreglado para responsive */
    .dropdown-menu {
      border: 1px solid var(--ui-border);
      box-shadow: 0 10px 30px rgba(2,6,23,.08);
      border-radius: 0.75rem;
    }
    
    .dropdown-item {
      border-radius: 0.5rem;
      margin: 0.15rem 0.5rem;
      width: auto;
    }
    
    .dropdown-item:hover {
      background-color: #f1f5f9;
    }
    
    @media (max-width: 991.98px){
      body.with-sidebar .ui-main{ margin-left: 0 !important; }
      .ui-sidebar{ display: none; }
      
      /* Ajustes para el dropdown en móvil */
      .dropdown-menu {
        position: absolute !important;
        top: 100% !important;
        right: 0 !important;
        left: auto !important;
        transform: none !important;
        width: auto !important;
        max-width: 300px !important;
        margin-top: 0.5rem !important;
      }
    }
  </style>

  @stack('styles')
</head>
<body class="ui-body with-sidebar" data-layout="app">
  {{-- ============== RAIL (desktop) ============== --}}
  <aside class="ui-sidebar d-none d-lg-flex flex-column">
    <div class="px-3 pt-3 pb-2 d-flex align-items-center justify-content-between">
      <a href="{{ url('/') }}" class="sidebar-brand d-flex align-items-center text-decoration-none">
        <img src="{{ asset('uploads/images/logopro.png') }}" class="me-2" width="28" height="28" alt="PROSERVI">
        <span class="fw-bold sidebar-brand-text">PROSERVI</span>
      </a>

      {{-- botón colapsar/expandir --}}
      <button id="railToggle" type="button" class="rail-toggle" title="Colapsar/expandir">
        <i class="bi bi-chevron-left"></i>
      </button>
    </div>

    <div class="ui-sidebar-body flex-grow-1">
      @hasSection('sidebar')
        @yield('sidebar')
      @else
        {{-- Menú por defecto --}}
        <ul class="ui-menu">
          <li>
            <a href="{{ route('cliente.index') }}" class="ui-menu-link" title="Inicio">
              <i class="bi bi-house-door"></i> <span>Inicio</span>
            </a>
          </li>
          <li class="ui-menu-section">Servicios</li>
          <li>
            <a href="{{ route('cliente.servicios.index') }}" class="ui-menu-link" title="Explorar">
              <i class="bi bi-grid"></i> <span>Explorar</span>
            </a>
          </li>
          <li>
            <a href="{{ route('cliente.favoritos.index') }}" class="ui-menu-link" title="Favoritos">
              <i class="bi bi-heart"></i> <span>Favoritos</span>
            </a>
          </li>
          <li class="ui-menu-section">Cuenta</li>
          <li>
            <a href="{{ route('cliente.perfil.show') }}" class="ui-menu-link" title="Mi perfil">
              <i class="bi bi-person"></i> <span>Mi perfil</span>
            </a>
          </li>
        </ul>
      @endif
    </div>

    <div class="ui-sidebar-footer small text-muted px-3 py-2">
      © {{ date('Y') }} PROSERVI
    </div>
  </aside>

  {{-- ============== DRAWER (móvil) ============== --}}
  <aside class="ui-drawer" id="appDrawer" aria-hidden="true">
    <div class="ui-drawer-header d-flex align-items-center justify-content-between">
      <a href="{{ url('/') }}" class="drawer-brand d-flex align-items-center text-decoration-none">
        <img src="{{ asset('uploads/images/logopro.png') }}" width="28" height="28" class="me-2" alt="PROSERVI">
        <span class="fw-bold">PROSERVI</span>
      </a>
      <button class="btn btn-link text-dark p-0" data-ui-close-drawer aria-label="Cerrar">
        <i class="bi bi-x-lg fs-5"></i>
      </button>
    </div>
    <div class="ui-drawer-body">
      @hasSection('drawer')
        @yield('drawer')
      @else
        {{-- Menú por defecto --}}
        <ul class="ui-drawer-menu">
          <li><a href="{{ route('cliente.servicios.index') }}"><i class="bi bi-house-door"></i> Inicio</a></li>
          <li><a href="{{ route('cliente.servicios.index') }}"><i class="bi bi-grid"></i> Explorar servicios</a></li>
          <li><a href="{{ route('cliente.favoritos.index') }}"><i class="bi bi-heart"></i> Favoritos</a></li>
          <li><a href="{{ route('cliente.perfil.show') }}"><i class="bi bi-person"></i> Mi perfil</a></li>
        </ul>
      @endif
    </div>
    <div class="ui-drawer-footer small text-muted">© {{ date('Y') }} PROSERVI</div>
  </aside>
  <div class="ui-drawer-overlay" id="appDrawerOverlay" data-ui-close-drawer></div>

  {{-- ============== CONTENEDOR ============== --}}
  <div class="ui-main">
    {{-- TOPBAR --}}
    <nav class="ui-topbar navbar navbar-expand-lg">
      <div class="container-fluid">
        <div class="d-flex align-items-center gap-2">
          {{-- hamburguesa móvil --}}
          <button class="btn-hamb d-lg-none" data-ui-open-drawer aria-label="Abrir menú">
            <i class="bi bi-list"></i>
          </button>
          <h1 class="ui-page-title m-0">@yield('page_title','')</h1>
        </div>

        {{-- usuario --}}
        <div class="d-flex align-items-center gap-2">
          @auth
            <div class="dropdown">
              <button class="btn btn-avatar dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle me-1"></i> 
                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                
                <li>
                  <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </li>
              </ul>
            </div>
          @else
            <a href="{{ route('login') }}" class="btn btn-light btn-sm">Iniciar sesión</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Registrarse</a>
          @endauth
        </div>
      </div>
    </nav>

    {{-- CONTENIDO --}}
    <main class="ui-content">@yield('content')</main>
  </div>

  {{-- Tabs móviles opcionales --}}
  @hasSection('bottombar')
    <nav class="ui-bottombar d-lg-none">@yield('bottombar')</nav>
  @endif

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // === Drawer móvil ===
    const drawer  = document.getElementById('appDrawer');
    const overlay = document.getElementById('appDrawerOverlay');
    
    document.querySelectorAll('[data-ui-open-drawer]').forEach(b => {
      b.addEventListener('click', () => { 
        drawer.classList.add('open'); 
        overlay.classList.add('show'); 
        document.body.classList.add('no-scroll'); 
      });
    });
    
    document.querySelectorAll('[data-ui-close-drawer]').forEach(b => {
      b.addEventListener('click', () => { 
        drawer.classList.remove('open'); 
        overlay.classList.remove('show'); 
        document.body.classList.remove('no-scroll'); 
      });
    });

    // === Colapso del rail (desktop) con memoria ===
    const railToggle = document.getElementById('railToggle');
    const LS_KEY = 'ui:sidebar-collapsed';
    
    const applyCollapsed = (collapsed) => {
      document.body.classList.toggle('sidebar-collapsed', collapsed);
      // icono flecha
      if (railToggle){
        railToggle.innerHTML = collapsed ? '<i class="bi bi-chevron-right"></i>' : '<i class="bi bi-chevron-left"></i>';
      }
    };
    
    // estado inicial desde localStorage (solo en >=lg)
    const isDesktop = () => window.matchMedia('(min-width: 992px)').matches;
    
    if (isDesktop()) {
      const collapsedState = localStorage.getItem(LS_KEY) === '1';
      applyCollapsed(collapsedState);
    }
    
    // toggle
    if (railToggle) {
      railToggle.addEventListener('click', () => {
        const next = !document.body.classList.contains('sidebar-collapsed');
        applyCollapsed(next);
        localStorage.setItem(LS_KEY, next ? '1' : '0');
      });
    }
    
    // al cambiar de tamaño: no aplicar colapso en móvil
    window.addEventListener('resize', () => {
      if (!isDesktop()) { 
        document.body.classList.remove('sidebar-collapsed'); 
      } else { 
        applyCollapsed(localStorage.getItem(LS_KEY) === '1'); 
      }
    });

    // Solucionar problema de dropdowns en móvil
    document.addEventListener('DOMContentLoaded', function() {
      // Asegurar que los dropdowns funcionen correctamente
      var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
      var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
      });
    });
  </script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  @stack('scripts')
</body>
</html>