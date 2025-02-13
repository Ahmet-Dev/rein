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
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Vergi, Kesinti veya Komisyon adı
            $table->decimal('rate', 5, 2); // Yüzde oranı (%5 gibi)
            $table->enum('type', ['tax', 'deduction', 'commission']); // Türü: vergi, kesinti veya komisyon
            $table->boolean('is_active')->default(true); // Aktif/Pasif durumu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
