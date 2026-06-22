@extends('layouts.app')
@section('title', $recipe->title . ' - DapurKita')
@section('content')
<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-8">
            @if($recipe->image)
                <img src="{{ Storage::url($recipe->image) }}" class="img-fluid rounded-4 mb-4 w-100"
                     style="max-height:400px;object-fit:cover;" alt="{{ $recipe->title }}">
            @else
                <div class="rounded-4 mb-4 text-center py-5"
                     style="background:linear-gradient(135deg,#f5c6a0,#e8956d);font-size:6rem;">🍲</div>
            @endif

            <span class="badge-category mb-2 d-inline-block">{{ $recipe->category->name }}</span>
            <h1 class="fw-bold mb-1">{{ $recipe->title }}</h1>
            <div class="d-flex gap-3 text-muted small mb-3 flex-wrap">
                <span><i class="bi bi-person"></i> {{ $recipe->user->name }}</span>
                <span><i class="bi bi-clock"></i> {{ $recipe->cook_time }} menit</span>
                <span><i class="bi bi-people"></i> {{ $recipe->servings }} porsi</span>
                <span><i class="bi bi-star-fill text-warning"></i> {{ number_format($recipe->averageRating(), 1) }}
                    ({{ $recipe->ratings->count() }} ulasan)</span>
            </div>
            <p class="lead text-muted">{{ $recipe->description }}</p>
            <hr>

            <h3 class="fw-bold mb-3">🥕 Bahan-bahan</h3>
            <ul class="list-group list-group-flush mb-4">
                @foreach($recipe->ingredients as $ing)
                <li class="list-group-item bg-transparent d-flex justify-content-between">
                    <span>{{ $ing->name }}</span>
                    <span class="text-muted">{{ $ing->quantity }}</span>
                </li>
                @endforeach
            </ul>

            <h3 class="fw-bold mb-3">👨‍🍳 Cara Memasak</h3>
            <div class="bg-white rounded-4 p-4 shadow-sm mb-4" style="white-space:pre-line;line-height:1.8;">
                {{ $recipe->instructions }}
            </div>

            @auth
                @if(Auth::id() === $recipe->user_id)
                <div class="d-flex gap-2 mb-4">
                    <a href="{{ route('recipes.edit', $recipe) }}" class="btn btn-outline-primary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('recipes.destroy', $recipe) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
                    </form>
                </div>
                @endif
            @endauth

            <h3 class="fw-bold mb-3">💬 Komentar ({{ $recipe->comments->count() }})</h3>
            @auth
            <form action="{{ route('recipes.comment', $recipe) }}" method="POST" class="mb-4">
                @csrf
                <textarea name="body" class="form-control mb-2" rows="3"
                          placeholder="Bagikan pengalaman memasakmu..."></textarea>
                <button class="btn btn-primary btn-sm"><i class="bi bi-send"></i> Kirim</button>
            </form>
            @else
            <div class="alert alert-light border mb-4">
                <a href="{{ route('login') }}">Login</a> untuk berkomentar.
            </div>
            @endauth

            @forelse($recipe->comments as $comment)
            <div class="d-flex gap-3 mb-3">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                     style="width:40px;height:40px;">
                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                </div>
                <div class="bg-white rounded-3 p-3 shadow-sm flex-grow-1">
                    <div class="fw-semibold">{{ $comment->user->name }}
                        <span class="text-muted fw-normal small ms-2">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="mb-0 mt-1">{{ $comment->body }}</p>
                </div>
            </div>
            @empty
            <p class="text-muted">Belum ada komentar.</p>
            @endforelse
        </div>

        <div class="col-lg-4">
            <div class="bg-white rounded-4 p-4 shadow-sm sticky-top" style="top:80px;">
                <h5 class="fw-bold mb-3">⭐ Beri Rating</h5>
                @auth
                <form action="{{ route('recipes.rate', $recipe) }}" method="POST">
                    @csrf
                    <div class="d-flex gap-2 mb-3 justify-content-center" id="starContainer">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="fs-3" style="cursor:pointer;">
                            <input type="radio" name="score" value="{{ $i }}" class="d-none star-input"
                                   {{ $userRating && $userRating->score == $i ? 'checked' : '' }}>
                            <i class="bi {{ $userRating && $userRating->score >= $i ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"
                               data-val="{{ $i }}"></i>
                        </label>
                        @endfor
                    </div>
                    <button class="btn btn-primary w-100">
                        {{ $userRating ? 'Perbarui Rating' : 'Simpan Rating' }}
                    </button>
                </form>
                @else
                <p class="text-muted text-center"><a href="{{ route('login') }}">Login</a> untuk memberi rating.</p>
                @endauth
                <hr>
                <div class="text-center">
                    <div class="display-4 fw-bold" style="color:var(--primary);">
                        {{ number_format($recipe->averageRating(), 1) }}
                    </div>
                    <div class="star-rating fs-5 mb-1">
                        @php $avg = round($recipe->averageRating()); @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= $avg ? 'bi-star-fill' : 'bi-star' }}"></i>
                        @endfor
                    </div>
                    <div class="text-muted small">dari {{ $recipe->ratings->count() }} pengguna</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const icons = document.querySelectorAll('[data-val]');
icons.forEach((icon, idx) => {
    icon.parentElement.addEventListener('mouseenter', () => {
        icons.forEach((ic, i) => {
            ic.className = i <= idx ? 'bi bi-star-fill text-warning' : 'bi bi-star text-muted';
        });
    });
    icon.parentElement.addEventListener('click', () => {
        document.querySelectorAll('.star-input')[idx].checked = true;
    });
});
document.getElementById('starContainer')?.addEventListener('mouseleave', () => {
    const checked = document.querySelector('.star-input:checked');
    const val = checked ? parseInt(checked.value) : 0;
    icons.forEach((ic, i) => {
        ic.className = i < val ? 'bi bi-star-fill text-warning' : 'bi bi-star text-muted';
    });
});
</script>
@endpush