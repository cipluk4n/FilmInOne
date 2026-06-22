@extends('layouts.app')

@section    ('content')
<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5">
        <div class="card card-cinema shadow-lg p-4 rounded-3">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-gold m-0">🎬 Daftar FilmInOne</h2>
                <p class="text-muted small">Gabung ke dalam ekosistem produksi film kampus</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger bg-danger text-white border-0 small py-2">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control bg-dark border-secondary text-white" placeholder="Contoh: Andi Wijaya" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Email Kampus</label>
                    <input type="email" name="email" class="form-control bg-dark border-secondary text-white" placeholder="nama@kampus.ac.id" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Password (Minimal 6 Karakter)</label>
                    <input type="password" name="password" class="form-control bg-dark border-secondary text-white" placeholder="••••••" required>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control bg-dark border-secondary text-white" placeholder="••••••" required>
                </div>

                <button type="submit" class="btn btn-gold w-100 py-2 mb-3 shadow">Mulai Berkarya 🎥</button>
            </form>

            <div class="text-center mt-2">
                <p class="small text-muted mb-0">Sudah punya akun? <a href="{{ url('/login') }}" class="text-gold text-decoration-none fw-bold">Masuk Lewat Sini</a></p>
            </div>
        </div>
    </div>
</div>
@endsection