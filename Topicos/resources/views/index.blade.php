<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Página de inicio - SWGPI</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{ asset('assets/img/ITSSMT/ITSSMT.png') }}" rel="icon">
  <link href="{{ asset('assets/img/ITSSMT/ITSSMT.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">

</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="{{ url('/') }}" class="logo d-flex align-items-center">
        <i class="bi bi-buildings"></i>
        <h1 class="sitename">Gestion de Proyectos Integradores ITSSMT</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ url('/') }}" class="active">Inicio</a></li>
          <li class="dropdown"><a href="#"><span>Repositorio</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
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

    <!-- Hero Section -->
    <section id="hero" class="hero section" style="background-image: url('{{ asset('assets/img/ITSSMT/fondo.jpg') }}');">

      <div class="hero-container">
        <div class="hero-content">
          <h1>Bienvenido</h1>
          <p>Bienvenido al Repositorio digital del ITSSMT, aca podras visualizar cada trabajo de titulacion que el Tecnologico pueda ofrecer.Si eres Docente o Estudiante Inicia sesion.</p>
          <div class="cta-buttons">
            <a href="#" class="btn-apply" data-bs-toggle="modal" data-bs-target="#loginModal">Iniciar sesion</a>
            <a href="#" class="btn-tour">Repositorio</a>
          </div>
          <div class="announcement">
            <div class="announcement-badge">New</div>
            <p>Sistema diseñado por estudiantes del ITSSMT</p>
          </div>
        </div>
      </div>
    </section><!-- /Hero Section -->

  </main>

  <footer id="footer" class="footer-16 footer position-relative dark-background">
    <!-- footer content left intact, using asset() for images -->
    <div class="container">
      <div class="footer-main">
        <div class="row align-items-start">
          <div class="col-lg-5">
            <div class="brand-section">
              <a href="{{ url('/') }}" class="logo d-flex align-items-center mb-4">
                <span class="sitename">SWGPI ITSSMT</span>
              </a>
              <p class="brand-description">Repositorio digital de proyectos integradores del Instituto Tecnológico Superior de San Martín Texmelucan.</p>

              <div class="contact-info mt-5">
                <div class="contact-item">
                  <i class="bi bi-geo-alt"></i>
                  <span>Carretera Federal México-Puebla Km. 79.5, San Martín Texmelucan, Puebla.</span>
                </div>
                <div class="contact-item">
                  <i class="bi bi-telephone"></i>
                  <span>+52 (248) 484-1800</span>
                </div>
                <div class="contact-item">
                  <i class="bi bi-envelope"></i>
                  <span>contacto@itssmt.edu.mx</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-7">
            <div class="footer-nav-wrapper">
              <div class="row">
                <div class="col-6 col-lg-6">
                  <div class="nav-column">
                    <h6>Navegación</h6>
                    <nav class="footer-nav">
                      <a href="{{ url('/') }}">Inicio</a>
                      <a href="{{ url('projects') }}">Repositorio General</a>
                      <a href="{{ url('admissions') }}">Por Carrera</a>
                      <a href="{{ url('academics') }}">Por Generación</a>
                    </nav>
                  </div>
                </div>

                <div class="col-6 col-lg-6">
                  <div class="nav-column">
                    <h6>Soporte</h6>
                    <nav class="footer-nav">
                      <a href="#">Contacto</a>
                      <a href="#">Aviso de Privacidad</a>
                      <a href="#">Términos de Servicio</a>
                    </nav>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="footer-social">
        <div class="row align-items-center">
          <div class="col-lg-12">
            <div class="social-section">
              <div class="social-links">
                <a href="https://www.facebook.com/ITSSMT" aria-label="Facebook" class="social-link" target="_blank">
                  <i class="bi bi-facebook"></i>
                  <span>Facebook</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="container">
        <div class="bottom-content">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <div class="copyright">
                <p>© <span class="sitename">SWGPI ITSSMT</span>. Todos los derechos reservados.</p>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="legal-links">
                <a href="#">Aviso de Privacidad</a>
                <a href="#">Términos de Servicio</a>
                <div class="credits">
                  Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>

  <!-- Main JS File -->
  <script src="{{ asset('assets/js/main.js') }}"></script>

  <!-- Login Modal -->
  <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content login-modal p-3">
        <div class="modal-body">
          <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="login-brand text-center mb-3">
            <img src="{{ asset('assets/img/ITSSMT/ISC.png') }}" alt="logo" style="height:48px;"/>
            <h4 class="mt-2">Bienvenido</h4>
            <p class="muted">Ingresa tus credenciales para continuar</p>
          </div>

          <div id="login-message-container" class="mb-3"></div>

          <form id="loginForm" action="{{ url('login') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="mb-3 form-floating">
              <input type="text" class="form-control" id="userId" name="userId" placeholder="Matrícula o No. de Empleado" required>
              <label for="userId">ID de Usuario (Matrícula/Nómina)</label>
              <div class="invalid-feedback">Por favor ingresa tu ID de usuario.</div>
            </div>

            <div class="mb-3 form-floating">
              <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
              <label for="password">Contraseña</label>
              <div class="invalid-feedback">Por favor ingresa tu contraseña.</div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="rememberCheck">
                <label class="form-check-label muted" for="rememberCheck">Recuérdame</label>
              </div>
              <a href="#" class="muted small">¿Olvidaste tu contraseña?</a>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary btn-login">Iniciar sesión</button>
            </div>
          </form>
          <div class="login-footer text-center mt-3">
            <small class="muted">¿No tienes cuenta? <a href="#">Regístrate</a></small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      const error = urlParams.get('error');
      const messageContainer = document.getElementById('login-message-container');

      if (error) {
        let message = '';
        switch (error) {
          case 'invalidcredentials':
            message = 'Datos incorrectos. Por favor, verifica tu ID y contraseña.';
            break;
          case 'emptyfields':
            message = 'Por favor, completa todos los campos.';
            break;
          default:
            message = 'Ha ocurrido un error inesperado.';
            break;
        }
        messageContainer.innerHTML = `<div class="alert alert-danger" role="alert">${message}</div>`;
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
      }
    });
  </script>

</body>

</html>
