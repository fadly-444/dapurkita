@extends('layouts.app')
@section('title', 'Semua Resep - DapurKita')
@section('content')

@if(!request('search') && !request('category'))
<section class="hero text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Temukan Resep Favoritmu 🍽️</h1>
        <p class="lead mb-4 opacity-75">Ribuan resep dari komunitas dapur Indonesia</p>
        <form action="{{ route('recipes.index') }}" method="GET" class="d-flex justify-content-center gap-2">
            <input type="text" name="search" class="form-control form-control-lg w-50"
                   placeholder="Cari resep..." value="{{ request('search') }}">
            <button class="btn btn-light btn-lg fw-semibold px-4"><i class="bi bi-search"></i> Cari</button>
        </form>
    </div>
</section>
@endif

<div class="container py-5">
    <div class="d-flex flex-wrap gap-2 mb-4 align-items-center">
        <span class="fw-semibold me-2">Kategori:</span>
        <a href="{{ route('recipes.index') }}"
           class="btn btn-sm {{ !request('category') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill">Semua</a>
        @foreach($categories as $cat)
        <a href="{{ route('recipes.index', ['category' => $cat->slug]) }}"
           class="btn btn-sm {{ request('category') === $cat->slug ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill">
            {{ $cat->name }}
        </a>
        @endforeach
    </div>

    @if($recipes->count())
    <div class="row g-4">
        @foreach($recipes as $recipe)
        <div class="col-md-6 col-lg-4">
            <div class="recipe-card card h-100">
                @if($recipe->image)
                    <img src="{{ Storage::url($recipe->image) }}" class="card-img-top" alt="{{ $recipe->title }}">
                @else
                    <div class="card-img-placeholder">🍲</div>
                @endif
                <div class="card-body d-flex flex-column">
                    <span class="badge-category mb-2 d-inline-block">{{ $recipe->category->name }}</span>
                    <h5 class="card-title fw-bold">{{ $recipe->title }}</h5>
                    <p class="card-text text-muted small flex-grow-1">{{ Str::limit($recipe->description, 90) }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="small text-muted">
                            <i class="bi bi-clock"></i> {{ $recipe->cook_time }} menit &nbsp;
                            <i class="bi bi-people"></i> {{ $recipe->servings }} porsi
                        </div>
                        <div class="star-rating small">
                            @php $avg = round($recipe->averageRating()); @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $avg ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <a href="{{ route('recipes.show', $recipe) }}" class="btn btn-primary btn-sm mt-3 w-100">
                        Lihat Resep <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-footer bg-transparent small text-muted border-0 pb-3">
                    <i class="bi bi-person"></i> {{ $recipe->user->name }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-5">{{ $recipes->withQueryString()->links() }}</div>
    @else
    <div class="text-center py-5">
        <div style="font-size:4rem;">🥗</div>
        <h4 class="mt-3">Belum ada resep</h4>
        @auth
        <a href="{{ route('recipes.create') }}" class="btn btn-primary mt-2"><i class="bi bi-plus"></i> Tambah Resep</a>
        @endauth
    </div>
    @endif
</div>
@endsection