@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <span class="badge bg-warning text-dark fw-bold mb-2">📅 Kalender Kolaboratif</span>
        <h2 class="fw-bold text-white m-0">Sinkronisasi Jadwal Syuting</h2>
        <p class="text-muted small m-0">Proyek Film: <strong class="text-white">{{ $project->title }}</strong></p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ url('/project/' . $project->id) }}" class="btn btn-outline-light btn-sm fw-bold">← Kembali ke Ruang Kerja</a>
    </div>
</div>

<hr class="border-secondary mb-4">

<!-- NAVIGASI TAB: MEMISAHKAN JADWAL SAYA DAN JADWAL BERSAMA -->
<ul class="nav nav-tabs border-secondary mb-4" id="scheduleTab" role="tablist">
    <li class="nav-item">
        <button class="nav-link active fw-bold text-gold bg-transparent border-0" id="my-schedule-tab" data-bs-toggle="tab" data-bs-target="#my-schedule-panel" type="button">👤 Jadwal Saya</button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold text-gold bg-transparent border-0" id="team-schedule-tab" data-bs-toggle="tab" data-bs-target="#team-schedule-panel" type="button">👥 Kalender Bersama Tim</button>
    </li>
</ul>

<div class="tab-content">
    <!-- PANEL 1: JADWAL SAYA (TEMPAT INPUT & LOG MANDIRI) -->
    <div class="tab-pane fade show active" id="my-schedule-panel">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-cinema p-3 rounded-3 border-secondary">
                    <h5 class="fw-bold text-gold mb-3"><i class="bi bi-calendar-plus"></i> Input Jam Kosong Anda</h5>
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
                        <button type="submit" class="btn btn-gold w-100 fw-bold">💾 Simpan Jadwal Saya</button>
                    </form>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-cinema p-3 rounded-3 border-secondary">
                    <h5 class="fw-bold text-white mb-3">📋 Riwayat Waktu Luang Anda di Proyek Ini</h5>
                    <table class="table table-dark table-striped table-hover small border-secondary">
                        <thead>
                            <tr>
                                <th>Waktu Mulai</th>
                                <th>Waktu Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules->where('user_id', auth()->id()) as $mySch)
                                <tr>
                                    <td>🟢 {{ \Carbon\Carbon::parse($mySch->start_time)->format('d M Y (H:i)') }}</td>
                                    <td>🔴 {{ \Carbon\Carbon::parse($mySch->end_time)->format('d M Y (H:i)') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-muted text-center py-3">Anda belum mengisi jadwal luang Anda.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- PANEL 2: KALENDER BERSAMA TIM (FULL CALENDAR) -->
    <div class="tab-pane fade" id="team-schedule-panel">
        <div class="card card-cinema p-3 rounded-3 border-secondary shadow shadow-lg">
            <div id="calendar" style="max-height: 650px; color: white;"></div>
        </div>
    </div>
</div>

<!-- MODAL POP-UP: MELIHAT SIAPA YANG HADIR / LUANG SAAT TANGGAL DIKLIK -->
<div class="modal fade" id="attendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-cinema text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold text-gold" id="modal-date-title">📌 Kru Tersedia</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6 class="small text-muted mb-3">Daftar Sineas yang terjadwal kosong pada hari ini:</h6>
                <ul class="list-group list-group-flush" id="attendance-list">
                    <!-- Data disuntikkan via JavaScript -->
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        
        // Data Jadwal Lengkap dengan Inject Informasi Tambahan (Role)
        var eventsData = [
            @foreach($schedules as $sch)
            {
                title: '🟢 {{ $sch->user->name }}',
                start: '{{ \Carbon\Carbon::parse($sch->start_time)->toIso8601String() }}',
                end: '{{ \Carbon\Carbon::parse($sch->end_time)->toIso8601String() }}',
                extendedProps: {
                    username: '{{ $sch->user->name }}',
                    role: '{{ $project->creator_id == $sch->user_id ? "Produser/Ketua" : ($sch->user->projects()->where("project_id", $project->id)->first()->pivot->role ?? "Anggota Tim") }}',
                    hours: '{{ \Carbon\Carbon::parse($sch->start_time)->format("H:i") }} - {{ \Carbon\Carbon::parse($sch->end_time)->format("H:i") }}'
                },
                backgroundColor: '#2c3e50',
                borderColor: '#ffcc00',
                allDay: false
            },
            @endforeach
        ];

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            timeZone: 'local', // SOLUSI SOAL NOMOR 5: Menghilangkan pergeseran zona waktu otomatis
            headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek' },
            locale: 'id',
            events: eventsData,
            
            // LOGIKA NO 4: JIKA HARI/KOTAK DIKLIK, LIHAT SIAPA SAJA YANG HADIR
            // LOGIKA NO 4: JIKA HARI/KOTAK DIKLIK, LIHAT SIAPA SAJA YANG HADIR
            dateClick: function(info) {
                var clickedDate = info.dateStr;
                var listHtml = '';
                var timeRangeText = ''; // Penampung teks jam syuting
                
                // Cari data krunya yang beririsan dengan tanggal yang diklik
                eventsData.forEach(function(ev) {
                    if(ev.start.startsWith(clickedDate)) {
                        listHtml += `<li class="list-group-item bg-transparent text-white border-secondary d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-person-video2 text-gold me-2"></i><strong>${ev.extendedProps.username}</strong>
                                <div class="text-muted small">${ev.extendedProps.hours}</div>
                            </div>
                            <span class="badge bg-secondary">${ev.extendedProps.role}</span>
                        </li>`;
                        
                        // Ambil jam dari kru pertama yang ditemukan sebagai acuan jam syuting
                        if (timeRangeText === '') {
                            timeRangeText = ' (' + ev.extendedProps.hours + ')';
                        }
                    }
                });

                if(listHtml === '') {
                    listHtml = '<li class="list-group-item bg-transparent text-muted text-center border-0 py-3">Tidak ada jadwal luang kru di tanggal ini.</li>';
                    timeRangeText = ' (Belum Ada Jam Syuting)';
                }

                // FORMAT BARU: Menggabungkan Tanggal + Jam Syuting di Judul Atas Pop-up
                var dateObj = new Date(clickedDate);
                var formattedDate = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                
                // Set teks ke Header Modal
                document.getElementById('modal-date-title').innerHTML = `<i class="bi bi-clock-history text-gold me-2"></i> ${formattedDate}${timeRangeText}`;
                document.getElementById('attendance-list').innerHTML = listHtml;

                // Tampilkan Modal
                var attnModal = new bootstrap.Modal(document.getElementById('attendanceModal'));
                attnModal.show();
            }
        });

        // Solusi render ulang saat tab Kalender diklik agar tidak gepeng/patah
        document.getElementById('team-schedule-tab').addEventListener('shown.bs.tab', function () {
            calendar.render();
        });
    });
</script>

<style>
    .fc { background: #1a1a1a; padding: 15px; border-radius: 8px; }
    .fc-theme-standard td, .fc-theme-standard th { border: 1px solid #333 !important; }
    .fc .fc-toolbar-title { color: #ffcc00; font-weight: bold; font-size: 1.2rem; }
    .fc .fc-button-primary { background-color: #333; border-color: #444; }
    .fc .fc-button-primary:hover { background-color: #ffcc00; color: #000; }
    .fc .fc-button-active { background-color: #ffcc00 !important; color: #000 !important; }
    .nav-tabs .nav-link.active { border-bottom: 3px solid #ffcc00 !important; color: #ffcc00 !important; }
    .nav-tabs .nav-link:hover { color: #fff; }
</style>
@endsection