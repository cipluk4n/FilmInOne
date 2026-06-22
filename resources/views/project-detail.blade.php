<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmInOne - Detail Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container my-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card p-3 mb-4 bg-warning bg-opacity-10 border-warning shadow-sm">
            <h5 class="fw-bold text-warning-emphasis">🔔 Pemberitahuan Tim (Revisi Terbaru):</h5>
            <ul class="list-group list-group-flush small">
                @forelse(auth()->user()->unreadNotifications as $notification)
                    <li class="list-group-item bg-transparent text-dark px-0 py-1 d-flex justify-content-between align-items-center">
                        <span>⚠️ {{ $notification->data['message'] }}</span>
                        <span class="badge bg-secondary">{{ $notification->data['time'] }}</span>
                    </li>
                @empty
                    <li class="list-group-item bg-transparent text-muted px-0 py-1">Belum ada pemberitahuan atau revisi baru.</li>
                @endforelse
            </ul>
        </div>

        <div class="card p-4 mb-4 shadow-sm">
            <h1 class="display-5 text-primary fw-bold">{{ $project->title }}</h1>
            <p class="lead text-muted">{{ $project->description }}</p>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <h5>Naskah (Script):</h5>
                    @if($project->script_path)
                        <a href="{{ asset('storage/' . $project->script_path) }}" class="btn btn-sm btn-outline-secondary" target="_blank">📄 Lihat Naskah</a>
                    @else
                        <span class="text-danger small">Belum diunggah oleh Ketua</span>
                    @endif
                </div>
                <div class="col-md-6">
                    <h5>Storyboard:</h5>
                    @if($project->storyboard_path)
                        <a href="{{ asset('storage/' . $project->storyboard_path) }}" class="btn btn-sm btn-outline-secondary" target="_blank">🎨 Lihat Storyboard</a>
                    @else
                        <span class="text-danger small">Belum diunggah oleh Ketua</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card p-4 mb-4 shadow-sm">
                    <!-- Fitur Rekomendasi Jadwal Syuting -->
                    <div class="alert alert-info border shadow-sm mb-4">
                        <h5 class="fw-bold text-dark">🎬 Cari Waktu Syuting Terbaik</h5>
                        <p class="small text-muted mb-2">Sistem akan menganalisis jam kosong yang tumpang tindih (overlap) di antara semua anggota tim.</p>
                        <a href="{{ url('project/' . $project->id . '/match-schedule') }}" class="btn btn-dark btn-sm fw-bold">⚡ Cek Jadwal yang Cocok</a>
                    </div>
                    <h3 class="mb-3 text-secondary">Log Progres & File Sharing</h3>
                    
                    <form action="{{ url('project/' . $project->id . '/upload-progress') }}" method="POST" enctype="multipart/form-data" class="bg-white p-3 border rounded mb-4">
                        @csrf
                        <h5>Unggah Progres / File Timeline Baru</h5>
                        <div class="mb-2">
                            <input type="text" name="title" class="form-control form-control-sm" placeholder="Judul progress (Contoh: Timeline XML Premieree v1)" required>
                        </div>
                        <div class="mb-2">
                            <textarea name="description" class="form-control form-control-sm" placeholder="Keterangan atau catatan revisi (opsional)"></textarea>
                        </div>
                        <div class="mb-2">
                            <input type="file" name="progress_file" class="form-control form-control-sm" required>
                            <small class="text-muted">Mendukung PDF, XML, PNG, JPG, WAV, ZIP, dll.</small>
                        </div>
                        <div class="form-check mb-3 mt-2">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="send_notification" id="sendNotification" value="1" checked>
                            <label class="form-check-label text-danger fw-bold" for="sendNotification">
                                🔔 Kirim notifikasi revisi ke semua anggota tim
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">🚀 Unggah & Beritahu Tim</button>
                    </form>

                    <div class="list-group">
                        @forelse($project->progresses as $progress)
                            <div class="list-group-item list-group-item-action p-3">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1 text-dark fw-bold">{{ $progress->title }}</h5>
                                    <small class="text-muted">{{ $progress->created_at->format('d M Y, H:i') }} WIB</small>
                                </div>
                                <p class="mb-2 text-muted small">{{ $progress->description }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-info text-dark">Format: .{{ $progress->file_type }}</span>
                                    <a href="{{ asset('storage/' . $progress->file_path) }}" class="btn btn-xs btn-success btn-sm" download>📥 Download File</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-muted py-3">Belum ada progress yang diunggah.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-4 shadow-sm mb-4">
                    <h4 class="text-secondary mb-3">Anggota Tim</h4>
                    
                    <form action="{{ url('project/' . $project->id . '/add-member') }}" method="POST" class="mb-3 pb-3 border-bottom">
                        @csrf
                        <div class="mb-2">
                            <label class="small fw-bold">Pilih Anggota:</label>
                            <select name="user_id" class="form-select form-select-sm" required>
                                <option value="">-- Pilih User --</option>
                                @foreach($all_users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="small fw-bold">Role / Peran:</label>
                            <input type="text" name="role" class="form-control form-control-sm" placeholder="Contoh: Editor, Cameramen" required>
                        </div>
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100">+ Tambah Anggota</button>
                    </form>

                    <ul class="list-group list-group-flush">
                        @foreach($project->members as $member)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <strong class="d-block">{{ $member->name }}</strong>
                                    <small class="text-muted">Role: {{ $member->pivot->role }}</small>
                                </div>
                            </li>
                        @endforeach
                        <!-- Form Input Jadwal Luang -->
                        <div class="card p-3 mt-3 bg-light border">
                            <h5 class="text-secondary small fw-bold">📅 Input Jadwal Kosong Anda:</h5>
                            <form action="{{ url('project/' . $project->id . '/add-schedule') }}" method="POST">
                                @csrf
                                <div class="mb-2">
                                    <label class="xs-text small text-muted">Jam Mulai:</label>
                                    <input type="datetime-local" name="start_time" class="form-control form-control-sm" required>
                                </div>
                                <div class="mb-2">
                                    <label class="xs-text small text-muted">Jam Selesai:</label>
                                    <input type="datetime-local" name="end_time" class="form-control form-control-sm" required>
                                </div>
                                <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold">💾 Simpan Jadwal Luang</button>
                            </form>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>