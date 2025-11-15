<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Proyectos Destacados - SWGPI</title>

  <link href="{{ asset('assets/img/ITSSMT/ITSSMT.png') }}" rel="icon">
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
  <style>
    .project-card { transition: transform 0.3s ease, box-shadow 0.3s ease; height:100%; display:flex; flex-direction:column; }
    .project-card:hover { transform: translateY(-10px); box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
    .project-card .card-body { flex-grow:1; display:flex; flex-direction:column; }
    .project-card .card-text { flex-grow:1; }
  </style>
</head>
<body class="news-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="{{ url('/') }}" class="logo d-flex align-items-center">
        <i class="bi bi-buildings"></i>
        <h1 class="sitename">Gestion de Proyectos Integradores ITSSMT</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ url('/') }}">Inicio</a></li>
          <li><a href="{{ url('academics') }}">Académico</a></li>
          <li><a href="{{ url('faculty-staff') }}">Facultad</a></li>
          <li class="dropdown"><a href="{{ url('projects-showcase') }}"><span>Proyectos</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="{{ url('projects-showcase') }}">Ver Proyectos</a></li>
              <li><a href="{{ url('projects-view') }}">Panel Administrativo</a></li>
            </ul>
          </li>
          <li><a href="#">Contacto</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">
    <div class="page-title">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8">
              <h1 class="heading-title">Proyectos Integradores</h1>
              <p class="mb-0">Descubre los proyectos desarrollados por nuestros estudiantes</p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="{{ url('/') }}">Inicio</a></li>
            <li class="current">Proyectos</li>
          </ol>
        </div>
      </nav>
    </div>

    <section id="projects-showcase" class="section">
      <div class="container">
        <div class="section-title" data-aos="fade-up">
          <h2>Proyectos Destacados</h2>
          <p>Una selección de los mejores proyectos integradores realizados en ITSSMT</p>
        </div>

        <!-- Aquí va la grilla de proyectos (se copió desde la maqueta original). -->
        <div class="row g-4">
          <p>Importa o crea proyectos dinámicos desde la base de datos en controladores de Laravel.</p>
        </div>

        <div class="text-center mt-5">
          <a href="{{ url('projects-view') }}" class="btn btn-primary btn-lg"><i class="bi bi-arrow-right"></i> Ver Panel Administrativo</a>
        </div>
      </div>
    </section>
  </main>

  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>

</body>
</html>
