<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat Akun Pengguna Kampus (Password semuanya: rahasia123)
        $ketua = User::create([
            'name' => 'Andi (Ketua Multimedia)',
            'email' => 'andi@kampus.ac.id',
            'password' => Hash::make('rahasia123'),
        ]);

        $editor = User::create([
            'name' => 'Budi (Editor Video)',
            'email' => 'budi@kampus.ac.id',
            'password' => Hash::make('rahasia123'),
        ]);

        $cameramen = User::create([
            'name' => 'Citra (Kameramen)',
            'email' => 'citra@kampus.ac.id',
            'password' => Hash::make('rahasia123'),
        ]);

        // Buat 1 user tambahan untuk opsi pilihan di website
        User::create([
            'name' => 'Dedi (Talent/Aktor)',
            'email' => 'dedi@kampus.ac.id',
            'password' => Hash::make('rahasia123'),
        ]);

        // 2. Membuat Proyek Film Pertama (ID Proyek otomatis = 1)
        $project = Project::create([
            'title' => 'Film Pendek: Lentera Kampus',
            'description' => 'Proyek film pendek berdurasi 10 menit untuk festival budaya kampus.',
            'script_path' => null, // Anggap belum upload naskah fisik
            'storyboard_path' => null,
            'creator_id' => $ketua->id,
        ]);

        // 3. Memasukkan Andi, Budi, dan Citra ke dalam Proyek Film ini
        ProjectMember::create([
            'project_id' => $project->id,
            'user_id' => $ketua->id,
            'role' => 'Ketua Proyek',
            'permissions' => ['all']
        ]);

        ProjectMember::create([
            'project_id' => $project->id,
            'user_id' => $editor->id,
            'role' => 'Lead Editor',
            'permissions' => ['upload_progress']
        ]);

        ProjectMember::create([
            'project_id' => $project->id,
            'user_id' => $cameramen->id,
            'role' => 'Director of Photography',
            'permissions' => ['upload_progress']
        ]);
    }
}