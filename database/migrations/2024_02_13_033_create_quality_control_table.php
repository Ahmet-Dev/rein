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
        Schema::create('quality_control', function (Blueprint $table) {
            $table->id();
            $table->string('test_name');
            $table->text('description')->nullable();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('result', ['passed', 'failed', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_control');
    }
};
