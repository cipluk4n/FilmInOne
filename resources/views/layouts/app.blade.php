<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmInOne - Komunitas Multimedia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { bg-color: #121212; color: #e0e0e0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .bg-dark-cinema { background-color: #1a1a1a; }
        .text-gold { color: #ffcc00; }
        .btn-gold { background-color: #ffcc00; color: #111; font-weight: bold; border: none; }
        .btn-gold:hover { background-color: #e6b800; color: #111; }
        .card-cinema { background-color: #1a1a1a; border: 1px solid #333; color: #fff; }
    </style>
</head>
<body class="bg-dark">

    @auth
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark-cinema border-bottom border-secondary mb-4 shadow">
        <div class="container">
            <a class="navbar-brand fw-bold text-gold" href="{{ url('/dashboard') }}">🎬 FilmInOne</a>
            <button class="navbar-dark navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-3 text-muted small">🍿 Sineas: <strong class="text-white">{{ auth()->user()->name }}</strong></li>
                    <li class="nav-item">
                        <form action="{{ url('/logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm fw-bold">Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endauth

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show bg-success text-white border-0" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>