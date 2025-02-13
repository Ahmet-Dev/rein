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
            $table->id();
            $table->string('name'); // Proje adı
            $table->text('description')->nullable(); // Açıklama
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Projeyi oluşturan kullanıcı
            $table->dateTime('start_date'); // Başlangıç tarihi
            $table->dateTime('end_date')->nullable(); // Bitiş tarihi
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending'); // Durum
            $table->timestamps();
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
