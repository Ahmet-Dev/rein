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
        Schema::create('risk_management', function (Blueprint $table) {
            $table->id();
            $table->string('risk_type');
            $table->text('description');
            $table->decimal('impact', 5, 2);
            $table->enum('status', ['identified', 'mitigated', 'resolved'])->default('identified');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_management');
    }
};
