@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h2 class="fw-bold text-gold m-0">Proyek Produksi Film</h2>
        <p class="text-white-70 small m-0">Kelola dan pantau semua linimasa produksi multimedia Anda di sini.</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <button type="button" class="btn btn-gold shadow" data-bs-toggle="modal" data-bs-target="#createProjectModal">
            Mulai Proyek Film Baru
        </button>
    </div>
</div>

<hr class="border-secondary mb-4">

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show bg-danger bg-opacity-10 text-white border-danger mb-4 mx-1" role="alert">
        <div class="d-flex align-items-center">
            <span class="fs-4 me-2">⚠️</span>
            <div>
                <strong>Peringatan Keamanan:</strong> {{ session('error') }}
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    @forelse($my_projects as $project)

        <div class="col-md-4 mb-4">
            <div class="card card-cinema h-100 shadow-sm rounded-3">
                <div class="card-body d-flex flex-column">
                    <span class="badge bg-warning text-dark fw-bold align-self-start mb-2">Status: {{ $project->status }}</span>
                    <h4 class="card-title fw-bold text-light mb-2">{{ $project->title }}</h4>
                    <p class="card-text small flex-grow-1 text-white-70 small">
                        {{ Str::limit($project->description, 120, '...') }}
                    </p>
                    <div class="border-top border-secondary pt-2 mt-3 text-white-70 small">
                       Pemilik: <strong class="text-white">{{ $project->creator->name }}</strong>
                    </div>
                    <br></br>
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <form action="{{ url('/project/' . $project->id) }}" method="POST" onsubmit="return confirm('Hapus permanen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                HAPUS
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-secondary p-3">
                    <a href="{{ url('/project/' . $project->id) }}" class="btn btn-outline-light btn-sm w-100 fw-bold">
                        Buka Ruang Kerja →
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            {{-- <div class="text-muted mb-3" style="font-size: 3rem;">📭</div> --}}
            <h5 class="text-muted">Belum ada proyek film yang dibuat.</h5>
            <p class="text-muted small">Klik tombol "Mulai Proyek Film Baru" di atas untuk membuat proyek pertama Anda!</p>
        </div>
        @if(isset($my_projects) && $my_projects->isNotEmpty())
            <div class="alert alert-warning p-1 small">
                @foreach($my_projects as $project)
                    <div class="project-item mb-3 p-2 border-bottom border-secondary">
                        <h5>
                            <a href="{{ url('/project/' . $project->id) }}" class="text-decoration-none text-warning fw-bold">
                                {{ $project->title }}
                            </a>
                        </h5>
                        <p class="mb-1 text-muted">Status: <span class="text-dark fw-bold">{{ $project->status }}</span></p>
                        @if($project->creator_id == auth()->id())
                            @if(Str::lower($project->status) == 'selesai' || Str::lower($project->status) == 'completed')
                                <form action="{{ url('/project/' . $project->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek film ini secara permanen?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger shadow-sm py-0 px-1" style="font-size: 11px;">
                                        <i class="bi bi-trash-fill"></i> Hapus Proyek
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-secondary text-wrap style="font-size: 10px;">🔒 Proyek Belum Selesai (Hapus Terkunci)</span>
                            @endif
                        @endif
                    </div>
                @endforeach
                <div class="mt-2 pt-1 border-top border-light text-muted" style="font-size: 11px;">
                    ID Pembuat Utama: {{ $my_projects->first()->creator_id }} | ID Anda: {{ auth()->id() }}
                </div>
            </div> @else
            <div class="text-center my-4 p-4 text-white-50 bg-dark rounded border border-secondary small">
                <i class="bi bi-folder-x fs-3 d-block mb-2 text-secondary"></i>
                Belum ada proyek film yang Anda ikuti atau buat saat ini. <br>
                <span class="text-warning">Silakan klik "Mulai Proyek Film Baru" untuk memulai!</span>
            </div>
        @endif

    @endforelse
</div>

<div class="modal fade" id="createProjectModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-cinema text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold text-gold" id="exampleModalLabel">Buat Proyek Film Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('/project/create') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-white-70 small fw-bold">Judul Proyek / Judul Film</label>
                        <input type="text" name="title" class="form-control bg-dark border-secondary text-white" placeholder="Contoh: Film Pendek - Lentera Kampus" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white-70 small fw-bold">Deskripsi / Sinopsis Singkat</label>
                        <textarea name="description" class="form-control bg-dark border-secondary text-white" rows="4" placeholder="Jelaskan secara singkat tentang proyek film ini..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gold btn-sm px-3">Simpan & Mulai</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection