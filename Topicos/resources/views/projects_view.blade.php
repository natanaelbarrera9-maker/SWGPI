<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Proyectos - Panel Admin</title>

  <link href="{{ asset('assets/img/ITSSMT/ITSSMT.png') }}" rel="icon">
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
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
          <li class="dropdown"><a href="{{ url('admin') }}"><span>Usuarios</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#" data-bs-toggle="modal" data-bs-target="#registerUserModal">Registrar Usuario</a></li>
              <li><a href="{{ url('admin') }}">Ver Usuarios</a></li>
            </ul>
          </li>
          <li class="dropdown"><a href="{{ url('projects-view') }}"><span>Proyectos</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="{{ url('projects-view') }}">Ver Proyectos</a></li>
              <li><a href="{{ url('projects-view?action=register') }}">Registrar Proyecto</a></li>
            </ul>
          </li>
          <li><a href="#">Cerrar Sesión</a></li>
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
              <h1 class="heading-title">Gestión de Proyectos</h1>
              <p class="mb-0">Administra los proyectos integradores del repositorio.</p>
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

    <section id="projects-panel" class="admin-panel section">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2>Proyectos</h2>
          <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerProjectModal"><i class="bi bi-plus-circle"></i> Registrar Proyecto</button>
          </div>
        </div>

        <div id="status-messages"></div>

        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead class="table-dark">
                  <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Autores</th>
                    <th>Año</th>
                    <th>Descripción</th>
                    <th class="text-center">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>Sistema de Gestión de Biblioteca</td>
                    <td>Juan García, María López</td>
                    <td>2023</td>
                    <td>Desarrollo de un sistema web para la gestión completa de bibliotecas digitales y físicas.</td>
                    <td class="text-center">
                      <button class="btn btn-info btn-sm" title="Ver" data-bs-toggle="modal" data-bs-target="#viewProjectModal"><i class="bi bi-eye"></i></button>
                      <button class="btn btn-warning btn-sm" title="Editar" data-bs-toggle="modal" data-bs-target="#editProjectModal"><i class="bi bi-pencil"></i></button>
                      <button class="btn btn-danger btn-sm" title="Eliminar" data-bs-toggle="modal" data-bs-target="#deleteProjectModal"><i class="bi bi-trash"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="mt-3">
          <a href="{{ url('admin') }}" class="btn btn-secondary">Volver a Admin</a>
        </div>
      </div>
    </section>

  </main>

  <!-- Aquí se pueden agregar los modales de ver/editar/eliminar/registrar proyectos como en la maqueta -->

  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>

</body>
</html>
