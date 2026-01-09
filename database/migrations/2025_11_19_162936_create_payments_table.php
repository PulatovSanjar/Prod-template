<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('magma_payment_request_id');
            $table->unsignedBigInteger('beneficiary_account_id')->nullable();

            $table->string('status', 64)->default('new');
            $table->string('bank_status', 64)->nullable();

            $table->unsignedBigInteger('approved_by_id')->nullable();
            $table->timestamp('approved_at')->nullable();

            // Внешние идентификаторы
            $table->string('external_payment_id', 64)
                ->nullable()
                ->unique(); // payment id

            $table->bigInteger('amount');
            $table->string('currency', 32);
            $table->string('type', 32)->index();

            $table->string('full_reference', 255)->nullable();
            $table->timestamps();

            // Индексы
            $table->index('beneficiary_account_id', 'idx_payments_beneficiary_account_id');
            $table->index('status', 'idx_payments_status');
            $table->index('bank_status', 'idx_payments_bank_status');
            $table->index('created_at', 'idx_payments_created_at');
            $table->index('approved_by_id', 'idx_payments_approved_by_id');

            // Внешние ключи
            $table->foreign('approved_by_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table
                ->foreign('magma_payment_request_id')
                ->references('id')
                ->on('magma_payment_requests');

            $table
                ->foreign('beneficiary_account_id')
                ->references('id')
                ->on('beneficiary_accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
