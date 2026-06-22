<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DapurKita - Resep Masakan Komunitas')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #E05C2A; --secondary: #2C5F2E; --cream: #FDF6EC; }
        body { font-family: 'Inter', sans-serif; background-color: var(--cream); }
        h1,h2,h3 { font-family: 'Playfair Display', serif; }
        .navbar { background:#fff; border-bottom:3px solid var(--primary); box-shadow:0 2px 10px rgba(0,0,0,0.06); }
        .navbar-brand { font-family:'Playfair Display',serif; font-size:1.6rem; color:var(--primary) !important; }
        .nav-link:hover { color:var(--primary) !important; }
        .btn-primary { background-color:var(--primary); border-color:var(--primary); }
        .btn-primary:hover { background-color:#c44e22; border-color:#c44e22; }
        .btn-outline-primary { color:var(--primary); border-color:var(--primary); }
        .btn-outline-primary:hover { background-color:var(--primary); }
        .recipe-card { border:none; border-radius:16px; overflow:hidden; transition:transform .2s,box-shadow .2s; background:#fff; box-shadow:0 2px 12px rgba(0,0,0,0.07); }
        .recipe-card:hover { transform:translateY(-5px); box-shadow:0 8px 24px rgba(0,0,0,0.13); }
        .recipe-card img { height:200px; object-fit:cover; }
        .card-img-placeholder { height:200px; background:linear-gradient(135deg,#f5c6a0,#e8956d); display:flex; align-items:center; justify-content:center; font-size:4rem; }
        .badge-category { background-color:var(--secondary); color:#fff; border-radius:20px; font-size:0.75rem; padding:4px 10px; }
        .star-rating .bi-star-fill { color:#f5a623; }
        .star-rating .bi-star { color:#ccc; }
        .hero { background:linear-gradient(135deg,var(--primary) 0%,#c44e22 60%,var(--secondary) 100%); color:white; padding:80px 0 60px; }
        footer { background:#1A1A1A; color:#ccc; }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">🍳 DapurKita</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('recipes.index') }}"><i class="bi bi-book"></i> Semua Resep</a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('recipes.create') }}"><i class="bi bi-plus-circle"></i> Tambah Resep</a>
                </li>
                @endauth
            </ul>
            <ul class="navbar-nav">
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger" type="submit">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                <li class="nav-item"><a class="btn btn-primary btn-sm ms-2" href="{{ route('register') }}">Daftar</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

@if(session('success'))
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@yield('content')

<footer class="py-4 mt-5">
    <div class="container text-center">
        <p class="mb-0">🍳 <strong>DapurKita</strong> &copy; {{ date('Y') }} — Berbagi Resep, Berbagi Cinta</p>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>