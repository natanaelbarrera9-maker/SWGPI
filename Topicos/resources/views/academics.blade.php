<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Repositorio por Generación - SWGPI</title>
  <meta name="description" content="Repositorio de proyectos integradores filtrado por generación.">

  <!-- Favicons -->
  <link href="{{ asset('assets/img/ITSSMT/ITSSMT.png') }}" rel="icon">
  <link href="{{ asset('assets/img/ITSSMT/ITSSMT.png') }}" rel="apple-touch-icon">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
</head>
<body class="academics-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="{{ url('/') }}" class="logo d-flex align-items-center">
        <i class="bi bi-buildings"></i>
        <h1 class="sitename">Gestion de Proyectos Integradores ITSSMT</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ url('/') }}">Inicio</a></li>
          <li class="dropdown"><a href="#" class="active"><span>Repositorio</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="{{ url('projects') }}">Repositorio general</a></li>
              <li><a href="{{ url('admissions') }}">Por Carrera</a></li>
              <li><a href="{{ url('academics') }}">Por Generacion</a></li>
              <li><a href="{{ url('faculty-staff') }}">Temas</a></li>
            </ul>
          </li>
          <li><a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Iniciar Sesion</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8">
              <h1 class="heading-title">Repositorio por Generación</h1>
              <p class="mb-0">Encuentra los proyectos integradores de cada generación de egresados.</p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="{{ url('/') }}">Inicio</a></li>
            <li><a href="#">Repositorio</a></li>
            <li class="current">Por Generación</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <section id="academics" class="academics section">
      <div class="container">
        <p>Aquí se mostrará el contenido del repositorio filtrado por generación.</p>
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
