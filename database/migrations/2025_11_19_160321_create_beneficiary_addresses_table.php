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
        Schema::create('beneficiary_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('beneficiary_id');

            $table->string('address_line1', 255)->nullable();
            $table->string('address_line2', 255)->nullable();
            $table->string('city', 128)->nullable();
            $table->string('postcode', 64)->nullable();
            $table->string('country', 10)->nullable(); // IFX: country (GB, etc.), но оставим чуть шире

            $table->timestamps();

            $table->index('beneficiary_id', 'idx_beneficiary_addresses_beneficiary_id');

            $table
                ->foreign('beneficiary_id')
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
        Schema::dropIfExists('beneficiary_addresses');
    }
};
