<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Proyectos Académicos')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Favicon -->
    <link href="{{ asset('img/ITSSMT/ITSSMT.png') }}" rel="icon">
    <link href="{{ asset('img/ITSSMT/ITSSMT.png') }}" rel="apple-touch-icon">
    
    <style>
        :root {
            --primary-blue: #1B396A;
            --secondary-blue: #2D5A96;
            --light-bg: #ffffff;
            --text-dark: #333645;
            --text-muted: #7a7f88;
        }
        
        body {
            font-family: 'Roboto', system-ui, -apple-system, 'Segoe UI', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
        }
        
        h1, h2, h3, h4, h5, h6 {
            color: var(--primary-blue);
            font-family: 'Raleway', sans-serif;
            font-weight: 600;
        }
        
        /* Navbar styling */
        .navbar {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .navbar-brand {
            font-size: 1.1rem;
            font-weight: 700;
            color: white !important;
            display: flex !important;
            align-items: center;
            gap: 12px;
        }
        
        .navbar-brand img {
            height: 45px;
            width: auto;
        }
        
        .navbar-brand-text {
            line-height: 1.1;
        }
        
        .navbar-brand-text span:first-child {
            display: block;
            font-size: 0.85rem;
        }
        
        .navbar-brand-text span:last-child {
            display: block;
            font-size: 1.05rem;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: all 0.3s;
            margin: 0 5px;
        }
        
        .nav-link:hover {
            color: white !important;
        }
        
        /* Buttons */
        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-blue);
            border-color: var(--secondary-blue);
        }
        
        .btn-outline-primary {
            color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        
        /* Cards */
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 8px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: white;
            border: none;
            font-weight: 600;
        }
        
        /* Stats cards */
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Section styling */
        .section {
            padding: 60px 0;
        }
        
        .section-title {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--primary-blue);
        }
        
        .section-subtitle {
            color: var(--text-muted);
            margin-bottom: 40px;
            font-size: 1.1rem;
        }
        
        /* Breadcrumbs */
        .breadcrumb {
            background: transparent;
            padding: 0;
        }
        
        .breadcrumb-item {
            color: var(--text-muted);
        }
        
        .breadcrumb-item.active {
            color: var(--primary-blue);
            font-weight: 600;
        }
        
        /* Footer */
        .footer {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: white;
            padding: 40px 0 20px;
            margin-top: 60px;
        }
        
        .footer a {
            color: rgba(255,255,255,0.8);
        }
        
        .footer a:hover {
            color: white;
        }
        
        /* Alert styling */
        .alert-primary {
            background-color: rgba(27, 57, 106, 0.1);
            border-color: var(--primary-blue);
            color: var(--primary-blue);
        }
        
        /* Forms */
        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(27, 57, 106, 0.25);
        }
        
        .form-label {
            font-weight: 600;
            color: inherit;
        }

        /* Prefer a utility class for hero/banner sections that should use the primary
           gradient. Use `.hero-gradient` on a wrapper when needed. This keeps the
           general page background white and limits blue usage to headers/cards. */
        .hero-gradient {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: white;
            padding: 40px 0;
        }

        .hero-gradient h1, .hero-gradient h2, .hero-gradient h3, .hero-gradient h4, .hero-gradient h5 {
            color: white;
        }

        /* Make headings and primary hero text white when a container uses a background image
           This targets the inline background-image pattern used in several views. */
        .container-fluid[style*="background: url"] h1,
        .container-fluid[style*="background: url"] h2,
        .container-fluid[style*="background: url"] .display-3,
        .container-fluid[style*="background: url"] .fw-bold {
            color: white !important;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.35);
        }

        .container-fluid[style*="background: url"] p,
        .container-fluid[style*="background: url"] .lead,
        .container-fluid[style*="background: url"] .btn-light {
            color: rgba(255,255,255,0.95) !important;
        }
        
        /* Table styling */
        .table thead {
            background-color: var(--primary-blue);
        }

        .table thead th {
            color: white;
            font-weight: 600;
            border-bottom: 2px solid rgba(255,255,255,0.06);
        }
        
        .table tbody tr:hover {
            background-color: var(--light-bg);
        }
        
        /* Badge styling */
        .badge {
            font-size: 0.85rem;
            padding: 6px 12px;
        }
        
        /* Dropdown menu */
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 8px;
        }
        
        .dropdown-item:hover {
            background-color: var(--light-bg);
            color: var(--primary-blue);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-xl">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('img/ITSSMT/ITSSMT.png') }}" alt="ITSSMT">
                <div class="navbar-brand-text">
                    <span>Gestión de Proyectos</span>
                    <span>Integradores ITSSMT</span>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="bi bi-house"></i> Inicio
                        </a>
                    </li>
                    
                    @auth
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.users.index') }}">
                                    <i class="bi bi-people"></i> Usuarios
                                </a>
                            </li>
                        @elseif(auth()->user()->isTeacher())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('teacher.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('student.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                        @endif
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->nombre }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-house"></i> Inicio</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-vh-100">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container-xl">
            <div class="row">
                <div class="col-lg-8">
                    <p class="mb-2"><strong>© 2025 SGPI ITSSMT</strong></p>
                    <p class="small mb-0">Sistema de Gestión de Proyectos Integradores del Instituto Tecnológico Superior de San Martín Texmelucan</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <p class="small mb-0">Versión Laravel 11 | Bootstrap 5.3</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
