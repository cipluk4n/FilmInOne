<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\ProjectProgress;
use App\Notifications\ProgressUploadedNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\RevisionUrgentMail;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // 1. Ambil semua proyek yang ada di database
        $projects = \App\Models\Project::all();

        // 2. 🌟 PERBAIKAN: Ambil proyek yang dibuatnya sendiri ATAU yang dia terlibat sebagai anggota
        $my_projects = \App\Models\Project::where('creator_id', $userId)
            ->orWhereHas('members', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        // 3. Kirim kembali ke view
        return view('dashboard', compact('projects', 'my_projects'));

        // // 1. Ambil SEMUA proyek yang ada di database (untuk bagian @foreach($projects))
        // $projects = \App\Models\Project::all();

        // // 2. Ambil proyek yang di mana user saat ini merupakan pembuat atau anggotanya (untuk bagian $my_projects)
        // // Karena tadi Anda memilih Cara 2 (user_id di projects boleh kosong), kita cari berdasarkan 'creator_id'
        // $my_projects = \App\Models\Project::where('creator_id', auth()->id())->get();

        // // 3. Kirim KEDUA variabel ini sekaligus ke view dashboard
        // return view('dashboard', compact('projects', 'my_projects'));
    }
        // LOGIKA 1: Membuat Proyek Baru (Oleh Ketua)
    public function storeProject(Request $request)
    {
        // Validasi input dari form website
        $request->validate([
            'title' => 'required|string|max:255|unique:projects,title',
            'description' => 'nullable|string',
            'script' => 'nullable|file|mimes:pdf,docx|max:10000', // max 10MB
            'storyboard' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:20000', // max 20MB
        ], [
            'title.unique' => 'Gagal membuat proyek! Judul film tersebut sudah digunakan oleh proyek lain. Silakan cari judul yang berbeda.'
        ]);

        // Proses simpan file Naskah & Storyboard ke dalam folder storage aplikasi
        $scriptPath = $request->file('script') ? $request->file('script')->store('scripts', 'public') : null;
        $storyboardPath = $request->file('storyboard') ? $request->file('storyboard')->store('storyboards', 'public') : null;

        // Simpan data proyek ke database MySQL
        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'script_path' => $scriptPath,
            'storyboard_path' => $storyboardPath,
            'creator_id' => auth()->id(), // ID user yang sedang login otomatis jadi Ketua
        ]);

        // Otomatis masukkan si pembuat proyek ke tabel project_members sebagai 'Ketua'
        ProjectMember::create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'role' => 'Ketua',
            'permissions' => ['all'] // Punya semua hak akses
        ]);

        // return redirect()->back()->with('success', 'Proyek FilmInOne Berhasil Dibuat!');
        return redirect()->route('dashboard')->with('success', 'Proyek film berhasil dibuat!');
    }

    public function destroy($id)
    {
        // 1. Cari proyeknya, jika tidak ada langsung memicu error 404
        $project = \App\Models\Project::findOrFail($id);

        // 2. Keamanan tambahan: Pastikan hanya pembuat proyek (Produser) yang bisa menghapus
        if ($project->creator_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk menghapus proyek ini!');
        }

        // 3. ERROR HANDLING STATUS: Cek apakah status proyek sudah selesai
        // Catatan: Sesuaikan kata 'Selesai' di bawah dengan string status yang Anda simpan di database Anda (misal: 'Completed' atau 'Finished')
        if (strtolower($project->status) !== 'selesai' && strtolower($project->status) !== 'completed') {
            return redirect()->back()->with('error', 'Gagal Menghapus! Proyek film ini masih berjalan. Anda hanya bisa menghapus proyek yang statusnya sudah "Selesai". ⚠️');
        }

        // 4. Jika lolos pengecekan di atas, proyek baru boleh dihapus
        $project->delete();

        return redirect()->route('dashboard')->with('success', 'Proyek film lama berhasil dihapus dari sistem! 🗑️');
}

    // LOGIKA 2: Menambahkan Anggota ke Proyek (Oleh Ketua)
    public function addMember(Request $request, $id)
    {
        $project = \App\Models\Project::findOrFail($id);

        // 1. Validasi input email harus terdaftar di tabel users
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|string|max:50',
        ]);

        // 2. Cari data user berdasarkan email
        $user = \App\Models\User::where('email', $request->email)->first();

        // 3. DETEKSI RELEVANSI: Cek apakah dia adalah pembuat proyek
        if ($project->creator_id == $user->id) {
            return redirect()->back()->with('error', 'Gagal: Email ini adalah milik Produser/Pembuat proyek ini sendiri! 👑');
        }

        // 4. DETEKSI RELEVANSI: Cek apakah dia sudah jadi anggota
        if ($project->members->contains($user->id)) {
            return redirect()->back()->with('error', 'Gagal: Mahasiswa ini sudah bergabung di dalam tim proyek ini! 👥');
        }

        // 5. Tambahkan ke pivot table jika lolos cek di atas
        $project->members()->attach($user->id, ['role' => $request->role]);

        // 6. Jalankan pemicu notifikasi & email
        $details = [
            'message' => 'Anda telah diundang oleh ' . auth()->user()->name . ' untuk bergabung sebagai ' . $request->role . ' di proyek film "' . $project->title . '".',
            'project_id' => $project->id
        ];
        
        try {
        // Kita paksa kirim email langsung tanpa perantara antrean
        // $user->notify(new \App\Notifications\ProgressUploadedNotification($details));
        $user->notify(new ProgressUploadedNotification([
            'message' => $request->progress_title, // 🌟 Kirim judul progresnya di sini!
            'project_id' => $project->id,
            'time' => date('H:i')
        ]));
        } catch (\Exception $e) {
            // JIKA GOOGLE MENOLAK SAMBUNGAN, KODE INI AKAN MEMAKSA WEB ANDA MENAMPILKAN PESAN ERRORNYA DI LAYAR
            return redirect()->back()->with('error', 'Kru berhasil join, TAPI EMAIL GAGAL. Alasan: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Anggota baru berhasil diundang & Email telah dikirim!');
}

    // LOGIKA 3: Mengunggah Berkas Progress Proyek / Timeline Editing (Oleh Semua Anggota)
    public function uploadProgress(Request $request, $id)
    {
        $project = \App\Models\Project::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string',
            'progress_file' => 'required|file|max:20480', // Max 20MB
        ]);

        $file = $request->file('progress_file');
        $path = $file->store('progress_files', 'public');

        // PERBAIKAN DI SINI: Ganti Progress menjadi ProjectProgress
        $progress = \App\Models\ProjectProgress::create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
        ]);

        // Logika notifikasi di bawahnya tetap sama...
        if ($request->has('send_notification')) {
            $recipients = [$project->creator_id];
            $memberIds = $project->members()->pluck('users.id')->toArray();
            $recipients = array_merge($recipients, $memberIds);
            $recipients = array_diff(array_unique($recipients), [auth()->id()]);

            $usersToNotify = \App\Models\User::whereIn('id', $recipients)->get();
            
            $details = [
                'message' => auth()->user()->name . " mengunggah progress baru '" . $request->title . "' di proyek '" . $project->title . "'.",
                'time' => now()->diffForHumans(),
                'project_id' => $project->id
            ];

            foreach ($usersToNotify as $user) {
                $user->notify(new \App\Notifications\ProgressUploadedNotification($progress));
            }
        }

        // 2. 🌟 PROSES PENGIRIMAN EMAIL DARURAT
        if ($request->has('notify_email') && $request->notify_email == '1') {
            
            // Ambil semua user_id anggota yang tergabung di proyek ini (kecuali yang sedang login/pengunggah)
            $memberIds = ProjectMember::where('project_id', $project->id)
                                    ->where('user_id', '!=', auth()->id())
                                    ->pluck('user_id');

            // Ambil data user beserta emailnya berdasarkan ID tersebut
            $members = \App\Models\User::whereIn('id', $memberIds)->get();

            // Lakukan perulangan kirim email ke setiap anggota
            foreach ($members as $member) {
                if ($member->email) {
                    // Mail::to($member->email)->queue(new RevisionUrgentMail($project, $request->progress_detail));
                    // Mail::to($member->email)->send(new RevisionUrgentMail($project, $request->progress_detail));
                    // Pastikan kodenya seperti ini (menggunakan queue)
                    Mail::to($member->email)->queue(new RevisionUrgentMail($project, $request->progress_detail));
                }
            }
        }

        return redirect()->back()->with('success', 'Progress berhasil diunggah' . ($request->notify_email ? ' dan email darurat telah dikirim!' : '!'));
        // return redirect('/project/' . $id)->with('success', 'Progress berhasil diunggah!');
    }
    
    // LOGIKA 4: Menyimpan Jadwal Luang Anggota
    public function addSchedule(Request $request, $projectId)
    {
        $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        \App\Models\AvailableSchedule::create([
            'user_id' => auth()->id(), // Otomatis mencatat siapa yang nginput (Budi si Editor)
            'project_id' => $projectId,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->back()->with('success', 'Jadwal luang Anda berhasil disimpan ke sistem!');
    }

    // LOGIKA 5: Algoritma Pencocokan Jadwal Otomatis
    public function matchSchedule($projectId)
    {
        // Ambil semua jadwal kosong yang diinput anggota di proyek ini
        $schedules = \App\Models\AvailableSchedule::where('project_id', $projectId)
            ->orderBy('start_time')
            ->get();

        // Jika belum ada yang input jadwal sama sekali
        if ($schedules->isEmpty()) {
            return redirect()->back()->with('success', 'Belum ada anggota yang memasukkan jadwal luang.');
        }

        // Mengelompokkan jadwal berdasarkan Tanggal agar mudah dianalisis
        $groupedByDate = $schedules->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->start_time)->format('Y-m-d');
        });

        $rekomendasi = [];

        // Looping untuk mencari jam yang bertabrakan / tumpang tindih (Overlap)
        foreach ($groupedByDate as $tanggal => $jadwalHariIni) {
            if ($jadwalHariIni->count() > 1) { // Hanya proses jika ada lebih dari 1 orang yang kosong di hari itu
                $maxStart = $jadwalHariIni->max('start_time');
                $minEnd = $jadwalHariIni->min('end_time');

                // Jika Jam Mulai Terakhir masih LEBIH KECIL dari Jam Selesai Tercepat, berarti ada irisan waktu!
                if ($maxStart < $minEnd) {
                    $rekomendasi[] = [
                        'tanggal' => \Carbon\Carbon::parse($tanggal)->format('d M Y'),
                        'jam_mulai' => \Carbon\Carbon::parse($maxStart)->format('H:i'),
                        'jam_selesai' => \Carbon\Carbon::parse($minEnd)->format('H:i'),
                        'jumlah_orang' => $jadwalHariIni->count()
                    ];
                }
            }
        }

        // Jika ada kecocokan, kirimkan hasilnya lewat pop-up notifikasi (Session)
        if (!empty($rekomendasi)) {
            $pesan = "🔥 REKOMENDASI WAKTU SYUTING: ";
            foreach ($rekomendasi as $r) {
                $pesan .= "Pada tanggal " . $r['tanggal'] . " jam " . $r['jam_mulai'] . " s/dB " . $r['jam_selesai'] . " (" . $r['jumlah_orang'] . " anggota bersedia). ";
            }
            return redirect()->back()->with('success', $pesan);
        }

        return redirect()->back()->with('success', 'Waduh, belum ditemukan jam kosong yang pas dan tumpang tindih di antara anggota minggu ini. Silakan atur ulang jadwal!');
    }

    // LOGIKA UNTUK DASHBOARD: Menampilkan semua proyek
    public function dashboard()
    {
        // Mengambil proyek yang dibuat sendiri ATAU proyek yang dia ikuti sebagai anggota
        $my_projects = \App\Models\Project::where('creator_id', auth()->id())
            ->orWhereHas('members', function($query) {
                $query->where('users.id', auth()->id());
            })->latest()->get();

        return view('dashboard', compact('my_projects'));
    }

    // LOGIKA UNTUK MEMBUAT PROYEK BARU
    public function createProject(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        \App\Models\Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'creator_id' => auth()->id(), // Otomatis mencatat ID user yang sedang login
        ]);

        return redirect('/dashboard')->with('success', 'Proyek film baru berhasil dibuat! Silakan buka proyek untuk mengelola.');
    }

    public function completeProject($id)
    {
        $project = \App\Models\Project::findOrFail($id);

        // Keamanan: Pastikan hanya pembuat proyek yang bisa menyelesaikan
        if ($project->creator_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk mengubah status proyek ini.');
        }

        // Ubah status menjadi Selesai
        $project->status = 'Selesai';
        $project->save();

        return redirect()->route('dashboard')->with('success', 'Selamat! Proyek film "' . $project->title . '" telah SELESAI!');
    }

    // LOGIKA HALAMAN 3: Menampilkan Detail Proyek & Lini Masa Progress (Aman dari Penyusup)
    public function showProject($id)
    {
        // 1. Ambil data proyek beserta progres, anggota, dan penciptanya
        $project = \App\Models\Project::with(['progresses.user', 'members', 'creator'])->findOrFail($id);
        
        // 2. KEAMANAN CRITICAL: Cek apakah user yang login adalah Pembuat ATAU Anggota Resmi
        $isCreator = $project->creator_id === auth()->id();
        $isMember = $project->members->contains(auth()->id());

        if (!$isCreator && !$isMember) {
            // Jika akun lain mencoba menerobos via URL, usir ke dashboard
            return redirect('/dashboard')->with('error', 'Akses ditolak! Anda bukan anggota resmi dari proyek film ini. 🚫');
        }

        // 3. Jika lolos pengecekan, ambil daftar user lain untuk dropdown tambah anggota
        $all_users = \App\Models\User::where('id', '!=', auth()->id())->get();

        return view('project-detail', compact('project', 'all_users'));
    }

    // LOGIKA HALAMAN 4: Menampilkan Halaman Kalender & Form Jadwal
    public function showSchedule($id)
    {
        $project = \App\Models\Project::with(['members', 'creator'])->findOrFail($id);
        
        // KEAMANAN: Usir jika bukan ketua dan bukan anggota
        if ($project->creator_id !== auth()->id() && !$project->members->contains(auth()->id())) {
            return redirect('/dashboard')->with('error', 'Akses jadwal ditolak! Anda bukan bagian dari tim ini. 🚫');
        }

        $schedules = \App\Models\AvailableSchedule::where('project_id', $id)->with('user')->get();
        $shooting_schedules = \App\Models\ShootingSchedule::where('project_id', $id)->get();

        $workload = [];
        foreach ($project->members as $member) {
            $count = \App\Models\ShootingSchedule::where('project_id', $id)
                ->whereJsonContains('assigned_users', $member->id)
                ->count();
            $workload[$member->id] = $count;
        }
        $workload[$project->creator_id] = \App\Models\ShootingSchedule::where('project_id', $id)
            ->whereJsonContains('assigned_users', $project->creator_id)
            ->count();

        return view('project-schedule', compact('project', 'schedules', 'shooting_schedules', 'workload'));
    }

    // Menyimpan Jadwal Panggilan Syuting Resmi (Call Sheet) oleh Ketua
    public function addShootingSchedule(Request $request, $id)
    {
        $project = \App\Models\Project::findOrFail($id);

        // Keamanan: Pastikan hanya Ketua Proyek yang bisa mengunci jadwal syuting
        if ($project->creator_id != auth()->id()) {
            return redirect()->back()->with('error', 'Hanya Produser/Ketua yang dapat membuat jadwal syuting.');
        }

        $request->validate([
            'title' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'assigned_users' => 'required|array'
        ]);

        \App\Models\ShootingSchedule::create([
            'project_id' => $id,
            'title' => $request->title,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'assigned_users' => $request->assigned_users
        ]);

        return redirect()->back()->with('success', 'Jadwal Panggilan Syuting berhasil diterbitkan & dikunci!');
    }
}