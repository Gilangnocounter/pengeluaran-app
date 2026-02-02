<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Pengeluaran') }}</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f1f4f9;
            font-family: "Inter", sans-serif;
        }

        /* Navbar Premium */
        .navbar-custom {
            background: linear-gradient(90deg, #4e73df, #224abe);
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .nav-link {
            color: #e8e8e8 !important;
            font-weight: 500;
        }

        .nav-link:hover {
            color: #fff !important;
        }

        .content-wrapper {
            padding-top: 30px;
            padding-bottom: 30px;
        }

        /* Card Style */
        .card {
            border: none;
            border-radius: 12px;
        }

        .dropdown-menu {
            border-radius: 8px;
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
        <div class="container-fluid">

            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <i class="bi bi-wallet2 me-2"></i> Pengeluaran App
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="collapse navbar-collapse" id="navbarNav">

                {{-- LEFT NAV --}}
                <ul class="navbar-nav me-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.index') }}">
                                <i class="bi bi-speedometer2 me-1"></i> Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('expenses.index') }}">
                                <i class="bi bi-cash-stack me-1"></i> Pengeluaran
                            </a>
                        </li>
                    @endauth

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about.index') }}">
                            <i class="bi bi-info-circle me-1"></i> About
                        </a>
                    </li>
                </ul>

                {{-- RIGHT NAV (USER) --}}
                <ul class="navbar-nav">

                    @auth
                        {{-- Dropdown User --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                               data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-2 fs-5"></i>
                                <span>{{ Auth::user()->username }}</span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>

                    @else
                        <li class="nav-item">
                            <a class="btn btn-light btn-sm shadow-sm" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </a>
                        </li>
                    @endauth

                </ul>

            </div>
        </div>
    </nav>


    {{-- PAGE CONTENT --}}
    <div class="container content-wrapper">
        @yield('content')
    </div>


    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- PAGE SCRIPTS --}}
    @yield('scripts')

</body>
</html>
