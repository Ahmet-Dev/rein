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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->enum('transaction_type', ['credit', 'debit']);
            $table->string('description')->nullable();
            $table->decimal('final_amount', 15, 2)->after('amount'); // Nihai tutar
            $table->decimal('tax_amount', 15, 2)->default(0)->after('final_amount'); // Vergi
            $table->decimal('deduction_amount', 15, 2)->default(0)->after('tax_amount'); // Kesinti
            $table->decimal('commission_amount', 15, 2)->default(0)->after('deduction_amount'); // Komisyon
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['final_amount', 'tax_amount', 'deduction_amount', 'commission_amount']);
        });
    }
};
