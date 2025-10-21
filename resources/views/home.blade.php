{{-- resources/views/home.blade.php --}}
@extends('layouts.landing')

@section('title', 'PROSERVI — Servicios en Sacaba')

@section('content')
  <!-- ================= HERO ================= -->
  <header id="inicio" class="hero-section">
    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-lg-6 order-lg-1 order-2">
          <h1 class="hero-title mb-3" data-aos="fade-up">
            Encuentra Servicios Confiables en Sacaba
          </h1>
          <p class="hero-subtitle mb-4" data-aos="fade-up" data-aos-delay="80">
            Conectamos tus necesidades con los mejores profesionales locales.
          </p>

          <form class="search-wrap mb-3" method="GET" action="{{ $servicesSearch }}" data-aos="fade-up" data-aos-delay="140">
            <div class="search-inner">
              <i class="fa-solid fa-magnifying-glass search-icon"></i>
              <input type="text" name="q" class="search-input"
                     placeholder="Buscar servicio (ej. plomería, electricista, jardinería)" autocomplete="on">
              <button class="search-btn" type="submit">Buscar</button>
            </div>
          </form>
        </div>

        <div class="col-lg-6 order-lg-2 order-1" data-aos="fade-left">
          <div class="hero-illustration ratio ratio-4x3 rounded-4 shadow-sm"
               style="background: url('{{ asset('uploads/images/hero.png') }}') center/cover no-repeat;"></div>
        </div>
      </div>
    </div>
  </header>

  <!-- ================= HOW IT WORKS ================= -->
  <section id="como-funciona" class="section">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title" data-aos="fade-up">¿Cómo funciona?</h2>
        <p class="section-subtitle" data-aos="fade-up" data-aos-delay="60">
          Encuentra el servicio que necesitas en 3 pasos
        </p>
      </div>

      <div class="row g-4 mt-2">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="80">
          <div class="step-card text-center h-100">
            <div class="step-number">1</div>
            <div class="step-icon mb-3"><i class="fas fa-search"></i></div>
            <h3 class="step-title mb-2">Busca</h3>
            <p class="text-muted mb-0">Escribe tu necesidad o explora categorías.</p>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="140">
          <div class="step-card text-center h-100">
            <div class="step-number">2</div>
            <div class="step-icon mb-3"><i class="fas fa-clipboard-check"></i></div>
            <h3 class="step-title mb-2">Compara</h3>
            <p class="text-muted mb-0">Revisa perfiles, calificaciones y precios.</p>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
          <div class="step-card text-center h-100">
            <div class="step-number">3</div>
            <div class="step-icon mb-3"><i class="fas fa-comments"></i></div>
            <h3 class="step-title mb-2">Contacta</h3>
            <p class="text-muted mb-0">Habla directo con el prestador y contrata.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= SERVICIOS DESTACADOS ================= -->
  <section id="servicios" class="section services-section">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title" data-aos="fade-up">Servicios destacados</h2>
        <p class="section-subtitle" data-aos="fade-up" data-aos-delay="60">
          Lo mejor valorado por la comunidad (3+ estrellas)
        </p>
      </div>

      <div class="row g-4">
        @forelse($serviciosDestacados as $servicio)
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 + 80 }}">
          <div class="service-card h-100">
            <div class="service-image" style="background-image:url('{{ $servicio->imagen ? asset('storage/' . $servicio->imagen) : asset('uploads/images/default-service.png') }}')"></div>
            <div class="service-content">
              <div class="d-flex align-items-center mb-3">
                <div>
                  <h3 class="h5 mb-1">{{ $servicio->titulo }}</h3>
                  @php
                    $rating = $servicio->calificacion_promedio ?? 0;
                    $totalCalificaciones = $servicio->total_calificaciones ?? 0;
                  @endphp
                  
                  @if($totalCalificaciones > 0)
                    @for($i = 1; $i <= 5; $i++)
                      <i class="bi bi-star{{ $i <= $rating ? '-fill' : '' }} text-warning"></i>
                    @endfor
                    <span class="ms-2 fw-bold">{{ number_format($rating, 1) }}/5</span>
                  @else
                    <span class="text-muted fw-bold">Sin calificaciones</span>
                  @endif
                </div>
              </div>
              <p class="mb-4">{{ Str::limit($servicio->descripcion, 100) }}</p>
              <div class="d-flex justify-content-between align-items-center">
                <a href="{{ $servicesIndex }}?q={{ urlencode($servicio->titulo) }}" class="service-link">Ver detalles <i class="fas fa-arrow-right ms-1"></i></a>
              </div>
            </div>
          </div>
        </div>
        @empty
        <div class="col-12 text-center">
          <p class="text-muted">No hay servicios destacados disponibles en este momento.</p>
        </div>
        @endforelse
      </div>

      <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="240">
        <a href="{{ $servicesIndex }}" class="btn btn-primary btn-lg">
          <i class="fas fa-list me-2"></i> Ver todos los servicios
        </a>
      </div>
    </div>
  </section>

  <!-- ================= TESTIMONIOS ================= -->
  <section class="section testimonials-section">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title text-white" data-aos="fade-up">Lo que dicen nuestros usuarios</h2>
        <p class="section-subtitle text-white-50" data-aos="fade-up" data-aos-delay="60">Historias reales en Sacaba</p>
      </div>

      <div class="row g-4">
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="80">
          <div class="testimonial-card h-100">
            <div class="d-flex align-items-center mb-4">
              <img src="{{ asset('images/testimonio1.jpg') }}" alt="Nicol Adriazola" class="testimonial-avatar me-3" width="60" height="60">
              <div>
                <h4 class="mb-1">Nicol Adriazola</h4>
                <div class="testimonial-rating">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
              </div>
            </div>
            <p class="mb-0">"Encontré un electricista confiable en minutos. ¡Recomendado!"</p>
          </div>
        </div>

        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="140">
          <div class="testimonial-card h-100">
            <div class="d-flex align-items-center mb-4">
              <img src="{{ asset('images/testimonio3.jpg') }}" alt="Gonzalo Felipez" class="testimonial-avatar me-3" width="60" height="60">
              <div>
                <h4 class="mb-1">Gonzalo Felipez</h4>
                <div class="testimonial-rating">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                </div>
              </div>
            </div>
            <p class="mb-0">"Como prestador, aumenté clientes gracias a PROSERVI."</p>
          </div>
        </div>

        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
          <div class="testimonial-card h-100">
            <div class="d-flex align-items-center mb-4">
              <img src="{{ asset('images/testimonio2.jpg') }}" alt="Saith Trujillo" class="testimonial-avatar me-3" width="60" height="60">
              <div>
                <h4 class="mb-1">Saith Trujillo</h4>
                <div class="testimonial-rating">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
              </div>
            </div>
            <p class="mb-0">"Por fin una plataforma seria para encontrar servicios."</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= CTA ================= -->
  <section id="contacto" class="section">
    <div class="container">
      <div class="cta-section text-center p-5" data-aos="fade-up">
        <h2 class="section-title mb-2">¿Eres un profesional?</h2>
        <p class="section-subtitle mb-4">Únete y llega a más clientes en Sacaba.</p>
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
          <i class="fas fa-user-plus me-2"></i> Regístrate como prestador
        </a>
      </div>
    </div>
  </section>
@endsection