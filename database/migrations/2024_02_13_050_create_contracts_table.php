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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // İlgili proje
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Sözleşme sahibi kullanıcı
            $table->text('terms'); // Sözleşme şartları
            $table->decimal('amount', 10, 2)->default(0); // Sözleşme tutarı
            $table->dateTime('start_date'); // Başlangıç tarihi
            $table->dateTime('end_date')->nullable(); // Bitiş tarihi
            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->default('pending'); // Sözleşme durumu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
