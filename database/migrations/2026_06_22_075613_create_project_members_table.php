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
        Schema::create('project_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // Terhubung ke tabel projects
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Terhubung ke tabel users (anggota)
            $table->string('role'); // Contoh: 'Ketua', 'Editor', 'Sutradara'
            $table->json('permissions')->nullable(); // Hak akses (fitur apa saja yang boleh diklik)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_members');
    }
};
