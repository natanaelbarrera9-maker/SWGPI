<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Panel de Administrador - SWGPI</title>

  <link href="{{ asset('assets/img/ITSSMT/ITSSMT.png') }}" rel="icon">
  <link href="{{ asset('assets/img/ITSSMT/ITSSMT.png') }}" rel="apple-touch-icon">

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
          <li class="dropdown"><a href="#" class="active"><span>Usuarios</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#" data-bs-toggle="modal" data-bs-target="#registerUserModal">Registrar Usuario</a></li>
              <li><a href="#">Editar Usuario</a></li>
              <li><a href="#">Eliminar Usuario</a></li>
            </ul>
          </li>
          <li class="dropdown"><a href="#"><span>Proyectos</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#">Registrar Proyecto</a></li>
              <li><a href="#">Editar Proyecto</a></li>
              <li><a href="#">Eliminar Proyecto</a></li>
            </ul>
          </li>
          <li><a href="#">Herramientas</a></li>
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
              <h1 class="heading-title">Panel de Administrador</h1>
              <p class="mb-0">Gestión centralizada de usuarios, proyectos y herramientas del sistema.</p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="{{ url('/') }}">Inicio</a></li>
            <li class="current">Admin</li>
          </ol>
        </div>
      </nav>
    </div>

    <section id="admin-panel" class="admin-panel section">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2>Gestionar Usuarios</h2>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerUserModal">
            <i class="bi bi-plus-circle"></i> Registrar Nuevo Usuario
          </button>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead class="table-dark">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Correo Electrónico</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Fecha de Registro</th>
                    <th scope="col" class="text-center">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">1</th>
                    <td>Juan Pérez</td>
                    <td>juan.perez@example.com</td>
                    <td><span class="badge bg-primary">Administrador</span></td>
                    <td>2024-01-15</td>
                    <td class="text-center">
                      <button class="btn btn-info btn-sm" title="Ver" data-bs-toggle="modal" data-bs-target="#viewUserModal"><i class="bi bi-eye"></i></button>
                      <button class="btn btn-warning btn-sm" title="Editar" data-bs-toggle="modal" data-bs-target="#editUserModal"><i class="bi bi-pencil"></i></button>
                      <button class="btn btn-danger btn-sm" title="Eliminar" data-bs-toggle="modal" data-bs-target="#deleteUserModal"><i class="bi bi-trash"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main>

  <!-- Modales (registrar, ver, editar, eliminar) se pueden implementar en Blade parcial más adelante -->

  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>

</body>
</html>
