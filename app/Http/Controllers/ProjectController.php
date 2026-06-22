<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\ProjectProgress;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    // LOGIKA 1: Membuat Proyek Baru (Oleh Ketua)
    public function storeProject(Request $request)
    {
        // Validasi input dari form website
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'script' => 'nullable|file|mimes:pdf,docx|max:10000', // max 10MB
            'storyboard' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:20000', // max 20MB
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

        return redirect()->back()->with('success', 'Proyek FilmInOne Berhasil Dibuat!');
    }

    // LOGIKA 2: Menambahkan Anggota ke Proyek (Oleh Ketua)
    public function addMember(Request $request, $projectId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string', // misal: Editor, Kameramen, Talent
        ]);

        ProjectMember::create([
            'project_id' => $projectId,
            'user_id' => $request->user_id,
            'role' => $request->role,
            'permissions' => ['upload_progress', 'view_schedule'] // Hak akses standar anggota
        ]);

        return redirect()->back()->with('success', 'Anggota tim berhasil ditambahkan!');
    }

    // LOGIKA 3: Mengunggah Berkas Progress Proyek / Timeline Editing (Oleh Semua Anggota)
    public function uploadProgress(Request $request, $projectId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'progress_file' => 'required|file|mimes:pdf,docx,xml,png,jpg,jpeg,wav,zip|max:50000',
        ]);

        $filePath = $request->file('progress_file')->store('progress_files', 'public');
        $fileType = $request->file('progress_file')->getClientOriginalExtension();

        $progress = \App\Models\ProjectProgress::create([
            'project_id' => $projectId,
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_type' => $fileType,
        ]);

        // KITA PAKAI CARA PALING STANDAR & AMAN DARI LARAVEL
        if ($request->has('send_notification')) {
            $allUsers = \App\Models\User::where('id', '!=', auth()->id())->get();
            
            foreach ($allUsers as $user) {
                $user->notify(new \App\Notifications\ProgressUploadedNotification($progress));
            }
        }

        return redirect()->back()->with('success', 'Progress berhasil diunggah!');
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
}