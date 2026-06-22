@extends('layouts.app')
@section('title', isset($recipe) ? 'Edit Resep' : 'Tambah Resep Baru')
@section('content')
<div class="container py-5" style="max-width:800px;">
    <h2 class="fw-bold mb-4">{{ isset($recipe) ? '✏️ Edit Resep' : '🍳 Tambah Resep Baru' }}</h2>
    <form action="{{ isset($recipe) ? route('recipes.update', $recipe) : route('recipes.store') }}"
          method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded-4 shadow-sm">
        @csrf
        @if(isset($recipe)) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label fw-semibold">Judul Resep *</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $recipe->title ?? '') }}" placeholder="Contoh: Rendang Padang Spesial">
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Kategori *</label>
            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                    {{ old('category_id', $recipe->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>
            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Deskripsi *</label>
            <textarea name="description" class="form-control" rows="3"
                      placeholder="Ceritakan tentang resep ini...">{{ old('description', $recipe->description ?? '') }}</textarea>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Waktu Memasak (menit) *</label>
                <input type="number" name="cook_time" min="1" class="form-control"
                       value="{{ old('cook_time', $recipe->cook_time ?? '') }}" placeholder="30">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Jumlah Porsi *</label>
                <input type="number" name="servings" min="1" class="form-control"
                       value="{{ old('servings', $recipe->servings ?? '') }}" placeholder="4">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Foto Masakan</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            @if(isset($recipe) && $recipe->image)
                <img src="{{ Storage::url($recipe->image) }}" height="80" class="rounded mt-2">
            @endif
        </div>

        <hr>
        <div class="mb-3">
            <label class="form-label fw-semibold">Bahan-bahan *</label>
            <div id="ingredientList">
                @if(isset($recipe) && $recipe->ingredients->count())
                    @foreach($recipe->ingredients as $i => $ing)
                    <div class="row g-2 mb-2 ingredient-row">
                        <div class="col-6">
                            <input type="text" name="ingredients[{{ $i }}][name]"
                                   class="form-control" placeholder="Nama bahan" value="{{ $ing->name }}">
                        </div>
                        <div class="col-5">
                            <input type="text" name="ingredients[{{ $i }}][quantity]"
                                   class="form-control" placeholder="Jumlah" value="{{ $ing->quantity }}">
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-ing">×</button>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="row g-2 mb-2 ingredient-row">
                        <div class="col-6">
                            <input type="text" name="ingredients[0][name]" class="form-control" placeholder="Nama bahan">
                        </div>
                        <div class="col-5">
                            <input type="text" name="ingredients[0][quantity]" class="form-control" placeholder="Jumlah">
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-ing">×</button>
                        </div>
                    </div>
                @endif
            </div>
            <button type="button" id="addIngredient" class="btn btn-outline-secondary btn-sm mt-1">
                <i class="bi bi-plus"></i> Tambah Bahan
            </button>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Langkah Memasak *</label>
            <textarea name="instructions" class="form-control" rows="8"
                      placeholder="1. Panaskan minyak...&#10;2. Masukkan bawang...">{{ old('instructions', $recipe->instructions ?? '') }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg"></i> {{ isset($recipe) ? 'Simpan Perubahan' : 'Publikasikan Resep' }}
            </button>
            <a href="{{ route('recipes.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
let ingCount = {{ isset($recipe) ? $recipe->ingredients->count() : 1 }};
document.getElementById('addIngredient').addEventListener('click', function() {
    document.getElementById('ingredientList').insertAdjacentHTML('beforeend', `
        <div class="row g-2 mb-2 ingredient-row">
            <div class="col-6">
                <input type="text" name="ingredients[${ingCount}][name]" class="form-control" placeholder="Nama bahan">
            </div>
            <div class="col-5">
                <input type="text" name="ingredients[${ingCount}][quantity]" class="form-control" placeholder="Jumlah">
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-outline-danger btn-sm remove-ing">×</button>
            </div>
        </div>`);
    ingCount++;
});
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-ing')) {
        if (document.querySelectorAll('.ingredient-row').length > 1) {
            e.target.closest('.ingredient-row').remove();
        }
    }
});
</script>
@endpush