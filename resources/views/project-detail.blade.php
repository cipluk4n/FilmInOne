@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <span class="badge bg-warning text-dark fw-bold mb-2">🎬 Project Workspace</span>
        <h2 class="fw-bold text-white m-0">{{ $project->title }}</h2>
        <p class="text-muted small m-0">{{ $project->description }}</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ url('/project/' . $project->id . '/schedule') }}" class="btn btn-gold shadow">
            📅 Manajemen Jadwal & Kalender Luang →
        </a>
    </div>
</div>

<hr class="border-secondary mb-4">

<div class="card card-cinema border-warning border-opacity-50 p-3 mb-4 shadow-sm bg-warning bg-opacity-10">
    <h6 class="fw-bold text-warning mb-2">🔔 Pemberitahuan Tim (Revisi Terbaru):</h6>
    <ul class="list-group list-group-flush small">
        @forelse(auth()->user()->unreadNotifications as $notification)
            <li class="list-group-item bg-transparent text-white-50 px-0 py-1 d-flex justify-content-between align-items-center border-0">
                <span>⚠️ {{ $notification->data['message'] }}</span>
                <span class="badge bg-secondary small">{{ $notification->data['time'] }}</span>
            </li>
        @empty
            <li class="list-group-item bg-transparent text-muted px-0 py-1 border-0">Belum ada pemberitahuan atau revisi baru dari tim.</li>
        @endforelse
    </ul>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card card-cinema p-3 mb-4 rounded-3 shadow-sm">
            <h5 class="fw-bold text-gold mb-3">🚀 Unggah Progress / Berkas Editing</h5>
            <form action="{{ url('/project/' . $project->id . '/upload-progress') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <input type="text" name="title" class="form-control form-control-sm bg-dark border-secondary text-white" placeholder="Judul Progress (Contoh: Potongan Kasar Video Scene 1, Naskah Final, dll)" required>
                </div>
                <div class="mb-3">
                    <textarea name="description" class="form-control form-control-sm bg-dark border-secondary text-white" rows="2" placeholder="Catatan tambahan revisi atau detail file..."></textarea>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-7 mb-2 mb-md-0">
                        <input type="file" name="progress_file" class="form-control form-control-sm bg-dark border-secondary text-white" required>
                    </div>
                    <div class="col-md-5">
                        <button type="submit" class="btn btn-gold btn-sm w-100 fw-bold">Upload & Kabari Tim 🚀</button>
                    </div>
                </div>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="send_notification" id="sendNotif" value="1" checked>
                    <label class="form-check-label text-danger small fw-bold" for="sendNotif">
                        🔔 Centang untuk kirim notifikasi/reminder revisi otomatis ke anggota tim
                    </label>
                </div>
            </form>
        </div>

        <h5 class="fw-bold text-white mb-3">📌 Lini Masa Progress Proyek</h5>
        <div class="position-relative ps-3 border-start border-secondary">
            @forelse($project->progresses->sortByDesc('created_at') as $progress)
                <div class="mb-4 position-relative">
                    <div class="position-absolute bg-warning rounded-circle" style="width: 12px; height: 12px; left: -20px; top: 6px;"></div>
                    
                    <div class="card card-cinema p-3 shadow-sm rounded-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="fw-bold text-gold m-0">📁 {{ $progress->title }}</h6>
                            <span class="badge bg-dark border border-secondary text-muted small">{{ $progress->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <p class="text-white-50 small mb-2">{{ $progress->description ?? 'Tidak ada catatan tambahan.' }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center border-top border-secondary pt-2 mt-2">
                            <span class="small text-muted">🎬 Diunggah oleh: <strong class="text-white">{{ $progress->user->name }}</strong></span>
                            <a href="{{ asset('storage/' . $progress->file_path) }}" target="_blank" class="btn btn-outline-info btn-xs fw-bold px-3 py-1 btn-sm">
                                📥 Download Berkas ({{ strtoupper($progress->file_type) }})
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted small ps-2">Belum ada linimasa progress terunggah. Silakan upload file perdana proyek ini di atas!</p>
            @endforelse
        </div>
    </div>

    <div class="col-md-4">
        @if($project->creator_id == auth()->id())
        <div class="card card-cinema p-3 mb-3 rounded-3 shadow-sm border-secondary">
            <h6 class="fw-bold text-gold mb-2">➕ Undang Sineas/Anggota Tim</h6>
            <form action="{{ url('/project/' . $project->id . '/add-member') }}" method="POST">
                @csrf
                <div class="mb-2">
                    <select name="user_id" class="form-select form-select-sm bg-dark border-secondary text-white" required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($all_users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <select name="role" class="form-select form-select-sm bg-dark border-secondary text-white" required>
                        <option value="Editor Video">Editor Video</option>
                        <option value="Kameramen">Kameramen</option>
                        <option value="Scriptwriter">Scriptwriter</option>
                        <option value="Sound Designer">Sound Designer</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-outline-warning btn-sm w-100 fw-bold">💾 Masukkan ke Tim</button>
            </form>
        </div>
        @endif

        <div class="card card-cinema p-3 rounded-3 shadow-sm border-secondary">
            <h6 class="fw-bold text-white mb-3">🎬 Daftar Kru Produksi:</h6>
            <ul class="list-group list-group-flush small">
                <li class="list-group-item bg-transparent text-white px-0 d-flex justify-content-between align-items-center border-secondary">
                    <span>👑 {{ $project->creator->name }}</span>
                    <span class="badge bg-gold text-dark fw-bold">Produser / Ketua</span>
                </li>
                @foreach($project->members as $member)
                    <li class="list-group-item bg-transparent text-white-50 px-0 d-flex justify-content-between align-items-center border-secondary">
                        <span>👥 {{ $member->name }}</span>
                        <span class="badge bg-secondary text-white">{{ $member->pivot->role }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection