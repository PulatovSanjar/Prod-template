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
        Schema::create('beneficiary_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('beneficiary_id');

            // Внешний ID счёта в IFX
            $table->string('ifx_account_id', 64)
                ->nullable()
                ->unique();

            // Обязательные для IFX поля
            $table->string('currency', 32);
            $table->string('account_holder', 255)->nullable();
            $table->string('nickname', 255)->nullable();

            // Базовые реквизиты счёта
            $table->string('account_number', 128)->nullable();
            $table->string('iban', 128)->nullable();
            $table->string('sort_code', 64)->nullable();
            $table->string('swift_bic', 64)->nullable();

            $table->string('default_reference', 255)->nullable();

            // Банковские данные верхнего уровня (если нужно)
            $table->string('bank_name', 255)->nullable();
            $table->string('bank_country', 32)->nullable();

            // Статус синхронизации с IFX
            $table->string('status', 64)->default('local_only');

            $table->timestamps();

            // Индексы
            $table->index('beneficiary_id', 'idx_beneficiary_accounts_beneficiary_id');
            $table->index(['beneficiary_id', 'iban', 'currency'], 'idx_beneficiary_accounts_identity');
            $table->index('status', 'idx_beneficiary_accounts_status');
            $table->index(['beneficiary_id', 'account_number', 'currency'], 'idx_beneficiary_accounts_account_number');

            $table->foreign('beneficiary_id')
                ->references('id')
                ->on('beneficiaries')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiary_accounts');
    }
};
