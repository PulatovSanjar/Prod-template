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
        Schema::create('magma_payment_requests', function (Blueprint $table) {
            $table->bigIncrements('id');

            // --- Основная информация о платеже из Magma ---
            $table->string('transaction_id', 64)->unique(); // data.transaction_id
            $table->string('unique_reference', 64); // data.unique_reference
            $table->bigInteger('amount');                   // data.amount (в минимальных единицах)
            $table->string('currency', 32);                 // data.currency

            $table->string('full_reference', 1024)->nullable(); // data.full_reference
            $table->dateTime('created_at_magma')->nullable();   // data.created_at

            // Внешние идентификаторы бенефициара в Magma
            $table->string('external_beneficiary_account_id', 90)->nullable(); // data.beneficiary_account_id
            $table->string('external_beneficiary_id', 90)->nullable();         // data.beneficiary_id

            // --- Денормализованные данные по PAYER ---
            $table->string('payer_iban', 34)->nullable();                 // payer.iban
            $table->string('payer_name', 255);                // payer.name
            $table->string('payer_bank_name', 255)->nullable();   // payer.bank_name
            $table->string('payer_sort_code', 64)->nullable();    // payer.sort_code
            $table->string('payer_wallet_id', 90)->nullable();    // payer.wallet_id
            $table->string('payer_swift_code', 64)->nullable();   // payer.swift_code
            $table->string('payer_bank_country', 10);             // payer.bank_country
            $table->string('payer_profile_type', 64);             // payer.profile_type
            $table->string('payer_business_profile_id', 90);      // payer.business_profile_id
            $table->string('payer_registration_number', 128)->nullable();     // payer.registration_number
            $table->string('payer_country_of_incorporation', 10)->nullable(); // payer.country_of_incorporation

            // payer.address.*
            $table->string('payer_address_city', 128);            // payer.address.city
            $table->string('payer_address_country', 10);          // payer.address.country
            $table->string('payer_address_post_code', 64)->nullable();        // payer.address.post_code
            $table->string('payer_address_street', 33);        // generated column
            $table->string('payer_address_line1', 255)->nullable();           // payer.address.address_line1
            $table->string('payer_address_line2', 255)->nullable(); // payer.address.address_line2

            // --- Денормализованные данные по PAYEE (бенефициар) ---
            $table->string('payee_inn', 32)->nullable();                 // payee.inn
            $table->string('payee_iban', 34)->nullable();    // payee.iban
            $table->string('payee_name', 255);               // payee.name

            // payee.address.*
            $table->string('payee_address_city', 128);       // payee.address.city
            $table->string('payee_address_country', 10);     // payee.address.country
            $table->string('payee_address_post_code', 64);   // payee.address.post_code
            $table->string('payee_address_line1', 255);      // payee.address.address_line1
            $table->string('payee_address_line2', 255)->nullable(); // payee.address.address_line2

            $table->string('payee_code_vo', 64)->nullable();        // payee.code_vo
            $table->string('payee_bank_name', 255)->nullable();                 // payee.bank_name
            $table->string('payee_sort_code', 64)->nullable();      // payee.sort_code
            $table->string('payee_wallet_id', 90)->nullable();      // payee.wallet_id
            $table->string('payee_swift_code', 64);                 // payee.swift_code
            $table->string('payee_second_name', 255)->nullable();   // payee.second_name
            $table->string('payee_bank_country', 10)->nullable();               // payee.bank_country
            $table->string('payee_profile_type', 64);               // payee.profile_type
            $table->string('payee_account_holder', 255);            // payee.account_holder
            $table->string('payee_account_number', 255)->nullable();            // payee.account_number
            $table->string('payee_beneficiary_id', 90);             // payee.beneficiary_id
            $table->string('payee_account_holder_2', 255)->nullable();     // payee.account_holder_2
            $table->string('payee_business_profile_id', 90)->nullable();   // payee.business_profile_id
            $table->string('payee_registration_number', 128)->nullable();  // payee.registration_number
            $table->string('payee_correspondent_bank_name', 255)->nullable();   // payee.correspondent_bank_name
            $table->string('payee_correspondent_bank_swift', 64)->nullable();   // payee.correspondent_bank_swift
            $table->string('payee_country_of_incorporation', 10)->nullable();               // payee.country_of_incorporation
            $table->string('payee_correspondent_bank_account', 255)->nullable(); // payee.correspondent_bank_account

            // --- beneficiary_registry_data (денормализовано) ---
            $table->string('beneficiary_registry_id', 128)->index();                // beneficiary_registry_data._id
            $table->string('beneficiary_registry_type', 64)->nullable();               // beneficiary_registry_data.type
            $table->string('beneficiary_registry_country', 128)->nullable();           // beneficiary_registry_data.country
            $table->string('beneficiary_registry_canonical_name', 255)->nullable();    // beneficiary_registry_data.canonical_name
            $table->string('beneficiary_registry_company_name', 255)->nullable(); // beneficiary_registry_data.company_name
            $table->string('beneficiary_registry_registration_number', 128)->nullable();      // beneficiary_registry_data.registration_number
            $table->string('beneficiary_registry_inn', 32)->nullable();           // beneficiary_registry_data.inn
            $table->string('beneficiary_registry_country_of_incorporation', 10)->nullable();  // beneficiary_registry_data.country_of_incorporation
            $table->dateTime('beneficiary_registry_created_at')->nullable();                 // beneficiary_registry_data.created_at
            $table->dateTime('beneficiary_registry_updated_at')->nullable();                 // beneficiary_registry_data.updated_at

            $table->json('errors')->nullable(); // все ошибки при создании платежа по бенефициарам, аккаунтам и адресам
            $table->string('status', 64)->default('new');

            $table->timestamps();

            // --- Индексы ---
            $table->index('status', 'idx_magma_payment_requests_status');
            $table->index('transaction_id', 'idx_magma_payment_requests_transaction_id');
            $table->index('external_beneficiary_id', 'idx_magma_payment_requests_ext_ben_id');
            $table->index('external_beneficiary_account_id', 'idx_magma_payment_requests_ext_ben_acc_id');
            $table->index('payee_iban', 'idx_magma_payment_requests_payee_iban');
            $table->index('payee_swift_code', 'idx_magma_payment_requests_payee_swift');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('magma_payment_requests');
    }
};
