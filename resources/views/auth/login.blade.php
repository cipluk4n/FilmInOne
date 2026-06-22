@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="col-md-5">
        <div class="card card-cinema shadow-lg p-4 rounded-3">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-gold m-0">🎬 FilmInOne</h2>
                <p class="text-muted small">Ruang Kerja Kolaborasi Komunitas Multimedia</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger bg-danger text-white border-0 small py-2">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form action="{{ url('/login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Email Kampus</label>
                    <input type="email" name="email" class="form-control bg-dark border-secondary text-white" placeholder="nama@kampus.ac.id" value="{{ old('email') }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control bg-dark border-secondary text-white" placeholder="••••••" required>
                </div>

                <button type="submit" class="btn btn-gold w-100 py-2 mb-3 shadow">Masuk Sineas 🚀</button>
            </form>

            <div class="text-center mt-2">
                <p class="small text-muted mb-0">Belum punya akun? <a href="{{ url('/register') }}" class="text-gold text-decoration-none fw-bold">Daftar Sineas Baru</a></p>
            </div>
        </div>
    </div>
</div>
@endsection