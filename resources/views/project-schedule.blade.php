@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <span class="badge bg-warning text-dark fw-bold mb-2">Kalender Kolaboratif</span>
        <h2 class="fw-bold text-white m-0">Sinkronisasi Jadwal Proyek</h2>
        <p class="text-white-60 small m-0">Proyek: <strong class="text-white">{{ $project->title }}</strong></p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ url('/project/' . $project->id) }}" class="btn btn-outline-light btn-sm fw-bold">← Ruang Kerja</a>
    </div>
</div>

<hr class="border-secondary mb-4">

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card card-cinema p-3 rounded-3 border-secondary shadow shadow-lg">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-white-60 small"><i class="bi bi-info-circle text-info"></i> Klik tanggal untuk membuat/melihat plot jadwal.</span>
            </div>
            <div id="calendar" style="max-height: 600px; color: white;"></div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-cinema p-3 rounded-3 border-secondary mb-3">
            <h6 class="fw-bold text-gold mb-2"><i class="bi bi-person-plus"></i> Set Waktu Anda:</h6>
            <form action="{{ url('project/' . $project->id . '/add-schedule') }}" method="POST" class="row g-2">
                @csrf
                <div class="col-6">
                    <input type="datetime-local" name="start_time" class="form-control form-control-sm bg-dark border-secondary text-white" required>
                </div>
                <div class="col-6">
                    <input type="datetime-local" name="end_time" class="form-control form-control-sm bg-dark border-secondary text-white" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-outline-warning btn-xs w-100 fw-bold btn-sm py-1">Simpan Waktu Saya</button>
                </div>
            </form>
        </div>

        <div class="card card-cinema p-3 rounded-3 border-secondary bg-dark bg-opacity-20" id="detail-sidebar" style="min-height: 400px;">
            <div id="sidebar-default-msg" class="text-center text-muted py-5">
                <i class="bi bi-calendar2-check" style="font-size: 2.5rem;"></i>
                <h6 class="mt-2 small text-white-50">Detail Harian</h6>
                <p class="small text-muted px-3">Silakan klik tanggal mana saja di kalender untuk memantau panggilan syuting atau melihat kru yang menganggur.</p>
            </div>

            <div id="sidebar-content" class="d-none">
                <h5 class="fw-bold text-warning mb-1" id="side-date-title">30 Juni 2026</h5>
                <div id="side-shooting-info" class="mb-3"></div>
                
                <hr class="border-secondary my-2">
                
                <h6 class="fw-bold text-white small mb-2"><i class="bi bi-people"></i> Anggota yang Senggang/Kosong:</h6>
                <ul class="list-group list-group-flush small mb-3" id="side-available-list"></ul>

                @if($project->creator_id == auth()->id())
                <div class="border-top border-secondary pt-3 mt-2" id="chairman-form-area">
                    <h6 class="fw-bold text-gold small mb-2"><i class="bi bi-megaphone"></i> Terbitkan Panggilan Syuting (Call Sheet):</h6>
                    <form action="{{ url('/project/' . $project->id . '/add-shooting-schedule') }}" method="POST">
                        @csrf
                        <input type="hidden" id="form_start_time" name="start_time">
                        <input type="hidden" id="form_end_time" name="end_time">
                        
                        <div class="mb-2">
                            <input type="text" name="title" class="form-control form-control-sm bg-dark border-secondary text-white" placeholder="Nama Agenda (Misal: Syuting Scene 1 di Lab)" required>
                        </div>
                        
                        <div class="mb-2">
                            <label class="small text-muted fw-bold d-block mb-1">Pilih Kru yang Wajib Hadir:</label>
                            <div style="max-height: 150px; overflow-y: auto;" class="bg-dark p-2 rounded border border-secondary">
                                <div class="form-check small mb-1">
                                    <input class="form-check-input" type="checkbox" name="assigned_users[]" value="{{ $project->creator_id }}" id="user-{{ $project->creator_id }}">
                                    <label class="form-check-label text-white" for="user-{{ $project->creator_id }}">
                                        👑 {{ $project->creator->name }} (Produser) <span class="badge bg-secondary text-xs">{{ $workload[$project->creator_id] }}x tugas</span>
                                    </label>
                                </div>
                                @foreach($project->members as $member)
                                <div class="form-check small mb-1">
                                    <input class="form-check-input" type="checkbox" name="assigned_users[]" value="{{ $member->id }}" id="user-{{ $member->id }}">
                                    <label class="form-check-label text-white-50" for="user-{{ $member->id }}">
                                        {{ $member->name }} ({{ $member->pivot->role }}) <span class="badge bg-secondary text-xs">{{ $workload[$member->id] }}x tugas</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="submit" class="btn btn-gold btn-sm w-100 fw-bold shadow">Kunci & Kabari Tim</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        // Penampung Data Mentah dari database
        var rawCrewSchedules = [
            @foreach($schedules as $sch)
            {
                username: '{{ $sch->user->name }}',
                role: '{{ $project->creator_id == $sch->user_id ? "Produser" : ($sch->user->projects()->where("project_id", $project->id)->first()->pivot->role ?? "Kru") }}',
                date: '{{ \Carbon\Carbon::parse($sch->start_time)->format("Y-m-d") }}',
                hours: '{{ \Carbon\Carbon::parse($sch->start_time)->format("H:i") }} - {{ \Carbon\Carbon::parse($sch->end_time)->format("H:i") }}',
                workloadCount: '{{ $workload[$sch->user_id] ?? 0 }}x tugas'
            },
            @endforeach
        ];

        // 1. STRATEGI AGREGASI: Kelompokkan jumlah kru yang kosong berdasarkan tanggal
        var aggregateMap = {};
        rawCrewSchedules.forEach(function(item) {
            if(!aggregateMap[item.date]) { aggregateMap[item.date] = 0; }
            aggregateMap[item.date]++;
        });

        // 2. Format Data untuk FullCalendar (Events)
        var calendarEvents = [];

        // Masukkan data agregat jam kosong (Berwarna Hijau Lembut)
        for (var dateStr in aggregateMap) {
            calendarEvents.push({
                title: '🟢 ' + aggregateMap[dateStr] + ' Kru Senggang',
                start: dateStr,
                display: 'block',
                backgroundColor: '#198754',
                borderColor: '#198754',
                textColor: '#fff',
                extendedProps: { type: 'crew_count', dateKey: dateStr }
            });
        }

        // Masukkan data Jadwal Syuting Resmi (Berwarna Kuning Emas/Merah Sinema)
        @foreach($shooting_schedules as $ss)
        calendarEvents.push({
            title: '🎬 SYUTING: {{ $ss->title }}',
            start: '{{ $ss->start_time->toIso8601String() }}',
            end: '{{ $ss->end_time->toIso8601String() }}',
            backgroundColor: '#ffcc00',
            borderColor: '#ffcc00',
            textColor: '#000',
            extendedProps: { 
                type: 'official_shooting',
                titleText: '{{ $ss->title }}',
                hours: '{{ $ss->start_time->format("H:i") }} - {{ $ss->end_time->format("H:i") }}',
                // Ambil daftar nama yang ditugaskan
                crewNames: [
                    @foreach($project->members as $m)
                        @if(in_array($m->id, $ss->assigned_users)) '🎬 {{ $m->name }} ({{ $m->pivot->role }})', @endif
                    @endforeach
                    @if(in_array($project->creator_id, $ss->assigned_users)) '👑 {{ $project->creator->name }} (Produser)', @endif
                ]
            }
        });
        @endforeach

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            timeZone: 'local',
            locale: 'id',
            headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek' },
            events: calendarEvents,
            
            // LOGIKA INTERAKTIF KLIK TANGGAL: TAMPILKAN PANEL KANAN YANG INFORMATIF
            dateClick: function(info) {
                var clickedDate = info.dateStr;
                
                // Sembunyikan pesan default, tampilkan panel data
                document.getElementById('sidebar-default-msg').classList.add('d-none');
                document.getElementById('sidebar-content').classList.remove('d-none');
                
                // Pasang Judul Tanggal
                var dObj = new Date(clickedDate);
                document.getElementById('side-date-title').innerText = dObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

                // Set value default untuk form ketua (Jam default 09:00 - 17:00)
                if(document.getElementById('form_start_time')) {
                    document.getElementById('form_start_time').value = clickedDate + 'T09:00';
                    document.getElementById('form_end_time').value = clickedDate + 'T17:00';
                }

                // A. ISI DATA JADWAL SYUTING RESMI JIKA ADA
                var shootingHtml = '';
                calendarEvents.forEach(function(ev) {
                    if(ev.extendedProps.type === 'official_shooting' && ev.start.startsWith(clickedDate)) {
                        shootingHtml += `
                            <div class="alert alert-warning bg-warning bg-opacity-10 border-warning text-white p-2 rounded mb-2 small">
                                <strong class="text-warning">⚡ JADWAL RESMI: ${ev.extendedProps.titleText}</strong><br>
                                <span class="text-muted small"><i class="bi bi-clock"></i> Jam: ${ev.extendedProps.hours}</span><br>
                                <div class="mt-1 fw-bold text-white-50">Kru Wajib Hadir:</div>
                                <ul class="ps-3 mb-0 text-muted" style="font-size:0.8rem;">
                                    ${ev.extendedProps.crewNames.map(name => `<li>${name}</li>`).join('')}
                                </ul>
                            </div>`;
                    }
                });
                document.getElementById('side-shooting-info').innerHTML = shootingHtml || '<div class="text-white-70 small py-1"><i class="bi bi-slash-circle"></i> Belum ada agenda syuting resmi dikunci.</div>';

                // B. ISI DAFTAR NAMA KRU YANG SEDANG LUANG
                var crewListHtml = '';
                rawCrewSchedules.forEach(function(crew) {
                    if(crew.date === clickedDate) {
                        crewListHtml += `
                            <li class="list-group-item bg-transparent text-white border-secondary px-0 d-flex justify-content-between align-items-center" style="font-size:0.8rem;">
                                <div>
                                    <strong>👤 ${crew.username}</strong> <span class="text-muted">(${crew.hours})</span>
                                    <div class="text-muted style="font-size:0.75rem;">${crew.role}</div>
                                </div>
                                <span class="badge bg-secondary bg-opacity-50 text-white-50">${crew.workloadCount}</span>
                            </li>`;
                    }
                });
                document.getElementById('side-available-list').innerHTML = crewListHtml || '<li class="list-group-item bg-transparent text-white-70 small px-0 border-0">Tidak ada kru yang menginput jam kosong hari ini.</li>';
            }
        });

        calendar.render();
    });
</script>

<style>
    .fc { background: #1a1a1a; padding: 10px; border-radius: 8px; font-size: 0.85rem; }
    .fc-theme-standard td, .fc-theme-standard th { border: 1px solid #2d2d2d !important; }
    .fc .fc-toolbar-title { color: #ffcc00; font-weight: bold; font-size: 1.1rem; }
    .fc .fc-button-primary { background-color: #333; border-color: #444; font-size: 0.8rem; padding: 4px 8px; }
    .fc .fc-button-primary:hover { background-color: #ffcc00; color: #000; }
    .fc .fc-button-active { background-color: #ffcc00 !important; color: #000 !important; }
    .fc-event { cursor: pointer; padding: 2px 4px; font-weight: bold; }
    .text-xs { font-size: 0.7rem; }
    input[type="datetime-local"]::-webkit-calendar-picker-indicator {
        /* display: none; */
        filter: invert(1); 
    }
</style>
@endsection