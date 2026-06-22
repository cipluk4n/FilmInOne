@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <span class="badge bg-warning text-dark fw-bold mb-2">📅 Kalender & Manajemen Waktu</span>
        <h2 class="fw-bold text-white m-0">Sinkronisasi Jadwal Syuting</h2>
        <p class="text-muted small m-0">Proyek Film: <strong class="text-white">{{ $project->title }}</strong></p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ url('/project/' . $project->id) }}" class="btn btn-outline-light btn-sm fw-bold">
            ← Kembali ke Ruang Kerja
        </a>
    </div>
</div>

<hr class="border-secondary mb-4">

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card card-cinema p-3 rounded-3 shadow-sm border-secondary mb-3">
            <h5 class="fw-bold text-gold mb-3">📅 Input Jam Kosong Anda:</h5>
            <form action="{{ url('project/' . $project->id . '/add-schedule') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Jam Mulai Luang:</label>
                    <input type="datetime-local" name="start_time" class="form-control bg-dark border-secondary text-white" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Jam Selesai Luang:</label>
                    <input type="datetime-local" name="end_time" class="form-control bg-dark border-secondary text-white" required>
                </div>
                <button type="submit" class="btn btn-gold w-100 fw-bold shadow-sm">💾 Simpan Jadwal Luang</button>
            </form>
        </div>

        <div class="card card-cinema p-3 rounded-3 shadow-sm border-secondary">
            <h6 class="fw-bold text-white mb-2">📋 Log Jadwal Terdaftar:</h6>
            <div style="max-height: 250px; overflow-y: auto;">
                <ul class="list-group list-group-flush small">
                    @forelse($schedules as $sch)
                        <li class="list-group-item bg-transparent text-white-50 px-0 py-2 border-secondary">
                            👤 <strong>{{ $sch->user->name }}</strong><br>
                            <span class="text-muted small">🟢 {{ \Carbon\Carbon::parse($sch->start_time)->format('d M (H:i)') }} s/d {{ \Carbon\Carbon::parse($sch->end_time)->format('H:i') }}</span>
                        </li>
                    @empty
                        <li class="list-group-item bg-transparent text-muted px-0 border-0">Belum ada kru yang memasukkan jadwal luang pekan ini.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-cinema p-4 rounded-3 shadow-sm border-info border-opacity-25 text-center mb-4 bg-info bg-opacity-10">
            <h4 class="fw-bold text-info mb-2">⚡ Rekomendasi Waktu Otomatis</h4>
            <p class="text-muted small mx-auto mb-3" style="max-width: 500px;">
                Sistem akan menyisir seluruh log jadwal luang kru di kolom sebelah kiri, lalu mencarikan irisan waktu (overlap) di mana semua anggota tim sama-sama sedang kosong!
            </p>
            <div>
                <a href="{{ url('project/' . $project->id . '/match-schedule') }}" class="btn btn-info btn-sm fw-bold px-4 text-dark shadow-sm">
                    🔍 Hitung & Cari Waktu Syuting Terbaik
                </a>
            </div>
        </div>

        <h5 class="fw-bold text-white mb-3">📅 Dashboard Kalender Bersama Proyek</h5>
        <div class="card card-cinema p-3 rounded-3 border-secondary">
            <div class="alert alert-secondary bg-dark text-muted border-secondary small mb-0 text-center py-4">
                <div style="font-size: 2rem;" class="mb-2">📆</div>
                <h6 class="text-white-50 mb-1">Simulasi Tampilan Google Calendar Terintegrasi</h6>
                <p class="small text-muted mb-0">Fitur visualisasi render kotak kalender mingguan/bulanan akan tampil di area penampung ini pada pengembangan lanjutan.</p>
            </div>
        </div>
    </div>
</div>
@endsection