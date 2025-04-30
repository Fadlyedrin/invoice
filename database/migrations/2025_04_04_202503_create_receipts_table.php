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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->nullable()->unique();
            $table->foreignId('invoice_id')->unique()->constrained();
            $table->decimal('amount_paid', 30, 2);
            $table->enum('payment_method', ['Cash', 'Credit Card', 'Bank Transfer']);
            $table->enum('status', ['Draft', 'Menunggu Approval', 'Disetujui', 'Ditolak']);
            $table->date('payment_date');
            $table->json('draft_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
