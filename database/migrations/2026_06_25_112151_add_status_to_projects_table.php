<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Kita tambahkan kolom status, dengan nilai bawaan (default) 'Planning'
            $table->string('status')->default('Planning')->after('title'); 
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Untuk jaga-jaga jika migration di-rollback
            $table->dropColumn('status');
        });
    }
};