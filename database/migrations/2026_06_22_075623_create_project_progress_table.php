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
        Schema::create('project_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang upload progress
            $table->string('title'); // Nama progress (misal: "Revisi Audio Scene 1")
            $table->text('description')->nullable();
            $table->string('file_path')->nullable(); // Lokasi file berkas (PDF, XML, PNG, dll)
            $table->string('file_type')->nullable(); // Ekstensi file (.mp3, .xml, dll)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_progress');
    }
};
