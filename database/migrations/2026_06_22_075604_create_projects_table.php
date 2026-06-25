<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id(); // Membuat kolom ID otomatis
            // $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title'); // Kolom Judul Proyek Film
            $table->text('description')->nullable(); // Deskripsi proyek (boleh kosong)
            $table->string('script_path')->nullable(); // Tempat menyimpan file naskah
            $table->string('storyboard_path')->nullable(); // Tempat menyimpan file storyboard
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade'); // ID Ketua yang buat proyek
            $table->timestamps(); // Otomatis membuat kolom tanggal dibuat & diedit
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
