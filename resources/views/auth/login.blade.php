@extends('layouts.app')
@section('title', 'Login - DapurKita')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="bg-white rounded-4 shadow-sm p-4">
                <h3 class="fw-bold text-center mb-4">👋 Masuk ke DapurKita</h3>
                <form action="/login" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="nama@email.com">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <p class="text-center mt-3 mb-0 text-muted small">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection