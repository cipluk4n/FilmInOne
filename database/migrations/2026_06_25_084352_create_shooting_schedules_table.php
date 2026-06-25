<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('shooting_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('title'); // Contoh: "Syuting Scene 1 - Koridor"
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->json('assigned_users'); // Menyimpan ID user yang wajib hadir dalam bentuk [1, 3, 5]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shooting_schedules');
    }
};
