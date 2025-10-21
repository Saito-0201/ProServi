{{-- resources/views/layouts/landing.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="PROSERVI — Oferta de servicios locales en Sacaba">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','PROSERVI — Servicios en Sacaba')</title>

  {{-- Bootstrap / Icons / Fonts --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

  {{-- AOS + CSS landing --}}
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/landing-proservi.css') }}">
  <link rel="icon" href="{{ asset('images/favicon.ico') }}">
  @stack('styles')
</head>

@php
  // Variables por defecto para cuando no se pasen desde el controller
  $servicesIndex = $servicesIndex ?? (auth()->check() ? 
      (auth()->user()->hasRole('Prestador') ? route('prestador.servicios.index') : route('cliente.servicios.index')) : 
      route('public.servicios.index'));
  
  $servicesSearch = $servicesSearch ?? (auth()->check() ? 
      (auth()->user()->hasRole('Prestador') ? route('prestador.servicios.index') : route('cliente.servicios.buscar')) : 
      route('public.servicios.index'));
@endphp

<body class="lp-body">

  {{-- NAVBAR --}}
  <nav class="landing-navbar fixed-top">
    <div class="container nav-inner">
      <a class="brand d-flex align-items-center" href="{{ url('/') }}">
        <span class="brand-text">PROSERVI</span>
      </a>

      {{-- Botones móviles --}}
      <div class="d-lg-none ms-auto">
          @auth
              @if(auth()->user()->hasRole('Prestador'))
                  <a href="{{ route('prestador.index') }}" class="btn btn-sm btn-light-outline me-2">
                      <i class="bi bi-grid me-1"></i> Ir a la app
                  </a>
              @else
                  <a href="{{ route('cliente.index') }}" class="btn btn-sm btn-light-outline me-2">
                      <i class="bi bi-grid me-1"></i> Ir a la app
                  </a>
              @endif
          @else
              <a href="{{ route('login') }}" class="btn btn-sm btn-light-outline me-2">Iniciar sesión</a>
          @endauth
      </div>

      {{-- Hamburguesa móvil --}}
      <button class="nav-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
        <i class="bi bi-list"></i>
      </button>

      {{-- Menú desktop --}}
      <div class="d-none d-lg-flex align-items-center flex-grow-1">
        <ul class="nav-links list-unstyled d-lg-flex align-items-center mb-0 mx-auto">
          <li><a href="{{ url('/') }}#inicio" class="nav-link">Inicio</a></li>
          <li><a href="{{ url('/') }}#como-funciona" class="nav-link">Cómo funciona</a></li>
          <li><a href="{{ $servicesIndex }}" class="nav-link">Servicios</a></li>
          <li><a href="{{ url('/') }}#contacto" class="nav-link">Contacto</a></li>
        </ul>

        <div class="nav-actions ms-lg-2">
          @auth
            @if(auth()->user()->hasRole('Prestador'))
              <a href="{{ route('prestador.index') }}" class="btn btn-pill btn-light-outline me-lg-2">
                <i class="bi bi-grid me-1"></i> Ir a la app
              </a>
            @else
              <a href="{{ route('cliente.index') }}" class="btn btn-pill btn-light-outline me-lg-2">
                <i class="bi bi-grid me-1"></i> Ir a la app
              </a>
            @endif
          @else
            <a href="{{ route('login') }}" class="btn btn-pill btn-light-outline me-lg-2">Iniciar sesión</a>
            <a href="{{ route('register') }}" class="btn btn-pill btn-light-solid">Registrarse</a>
          @endauth
        </div>
      </div>
    </div>
  </nav>

  {{-- Drawer móvil --}}
  <div class="offcanvas offcanvas-start mobile-drawer" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
      <div class="offcanvas-header">
          <a class="brand d-flex align-items-center me-auto" href="{{ url('/') }}">
              <span class="brand-text">PROSERVI</span>
          </a>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
      </div>

      <div class="offcanvas-body d-flex flex-column">
          <ul class="drawer-links list-unstyled flex-grow-1">
              <li><a href="{{ url('/') }}#inicio" class="drawer-link">Inicio</a></li>
              <li><a href="{{ url('/') }}#como-funciona" class="drawer-link">Cómo funciona</a></li>
              <li><a href="{{ $servicesIndex }}" class="drawer-link">Servicios</a></li>
              <li><a href="{{ url('/') }}#contacto" class="drawer-link">Contacto</a></li>
          </ul>

          <div class="drawer-actions mt-auto">
              @auth
                  @if(auth()->user()->hasRole('Prestador'))
                      <a href="{{ route('prestador.index') }}" class="btn drawer-btn-primary w-100 mb-2">
                          <i class="bi bi-grid me-1"></i> Ir a la app
                      </a>
                  @else
                      <a href="{{ route('cliente.index') }}" class="btn drawer-btn-primary w-100 mb-2">
                          <i class="bi bi-grid me-1"></i> Ir a la app
                      </a>
                  @endif
              @else
                  <a href="{{ route('login') }}" class="btn drawer-btn-primary w-100 mb-2">Iniciar sesión</a>
                  <a href="{{ route('register') }}" class="btn drawer-btn-ghost w-100">Registrarse</a>
              @endauth
          </div>
      </div>
  </div>

  {{-- CONTENIDO ESPECÍFICO --}}
  @yield('content')

  {{-- FOOTER --}}
  <footer class="footer mt-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-4">
          <div class="footer-logo mb-3">PROSERVI</div>
          <p class="footer-description mb-4">Conectando necesidades con profesionales confiables en Sacaba.</p>
          <div class="social-links">
            <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
            <a href="https://wa.me/63867252" class="social-link"><i class="fab fa-whatsapp"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-md-4">
          <h4 class="footer-title mb-3">Enlaces</h4>
          <ul class="list-unstyled footer-links">
            <li><a href="{{ url('/') }}">Inicio</a></li>
            <li><a href="{{ $servicesIndex }}">Servicios</a></li>
            <li><a href="{{ url('/') }}#como-funciona">Cómo funciona</a></li>
            <li><a href="#">Preguntas frecuentes</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-4">
          <h4 class="footer-title mb-3">Legal</h4>
          <ul class="list-unstyled footer-links">
              <li><a href="{{ route('terms') }}">Términos y condiciones</a></li>
              <li><a href="{{ route('privacy') }}">Política de privacidad</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-4">
          <h4 class="footer-title mb-3">Contacto</h4>
          <div class="footer-contact">
            <p><i class="fas fa-map-marker-alt me-2"></i> Sacaba, Cochabamba</p>
            <p><i class="fas fa-envelope me-2"></i> contacto@proservi.com</p>
            <p><i class="fas fa-phone me-2"></i> +591 63867252</p>
          </div>
        </div>
      </div>
      <div class="footer-bottom text-center mt-5 pt-4">
        <p class="mb-0">© {{ date('Y') }} PROSERVI. Todos los derechos reservados.</p>
      </div>
    </div>
  </footer>

  {{-- JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

  <script>
// Cerrar drawer automáticamente al hacer clic en enlaces
document.addEventListener('DOMContentLoaded', function() {
    const drawerLinks = document.querySelectorAll('.drawer-link');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileMenu) {
        const bsOffcanvas = bootstrap.Offcanvas.getInstance(mobileMenu);
        
        drawerLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (bsOffcanvas) {
                    bsOffcanvas.hide();
                }
            });
        });
        
        // Para enlaces con hash (scroll suave)
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href.startsWith('#')) {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        if (bsOffcanvas) {
                            bsOffcanvas.hide();
                        }
                        // Pequeño delay para permitir que el drawer se cierre
                        setTimeout(() => {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }, 300);
                    }
                }
            });
        });
    }
});
</script>
<script>
// Inicializar AOS
AOS.init({ 
  duration: 800, 
  once: true, 
  offset: 80,
  disable: window.innerWidth < 768
});

// Navbar scroll behavior
document.addEventListener("DOMContentLoaded", () => {
  const navbar = document.querySelector(".landing-navbar");
  let lastScrollY = window.scrollY;

  window.addEventListener("scroll", () => {
    const currentY = window.scrollY;

    if (currentY === 0) {
      navbar.classList.remove("scrolled", "hidden");
    } else if (currentY > lastScrollY && currentY > 100) {
      navbar.classList.add("hidden");
    } else {
      navbar.classList.remove("hidden");
      navbar.classList.add("scrolled");
    }

    lastScrollY = currentY;
  });

  // Manejo del drawer móvil
  const mobileMenu = document.getElementById('mobileMenu');
  let bsOffcanvas = null;
  
  if (mobileMenu) {
    // Inicializar offcanvas de Bootstrap
    bsOffcanvas = new bootstrap.Offcanvas(mobileMenu);
    
    // Cerrar drawer al hacer clic en enlaces
    const drawerLinks = document.querySelectorAll('.drawer-link');
    
    drawerLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        
        // Si es un enlace interno (#), manejar scroll suave
        if (href && href.startsWith('#')) {
          e.preventDefault();
          
          // Cerrar drawer
          if (bsOffcanvas) {
            bsOffcanvas.hide();
          }
          
          // Scroll suave después de cerrar el drawer
          setTimeout(() => {
            const target = document.querySelector(href);
            if (target) {
              target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
              });
            }
          }, 300);
        } else {
          // Para enlaces normales, solo cerrar drawer
          if (bsOffcanvas) {
            bsOffcanvas.hide();
          }
          // La navegación normal continuará
        }
      });
    });
  }

  // Scroll suave para enlaces internos en toda la página (excepto drawer)
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    // Excluir enlaces del drawer que ya tienen su propio manejo
    if (!anchor.classList.contains('drawer-link')) {
      anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#') {
          e.preventDefault();
          const target = document.querySelector(href);
          if (target) {
            target.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
          }
        }
      });
    }
  });
});

// Recargar AOS en resize
window.addEventListener('resize', function() {
  AOS.refresh();
});
</script>
  @stack('scripts')
</body>
</html>