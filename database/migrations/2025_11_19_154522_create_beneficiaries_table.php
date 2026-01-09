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
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Внешний ID в IFX
            $table->string('ifx_beneficiary_id', 64)
                ->nullable()
                ->unique();

            // Тип бенефициара в IFX: individual / corporate
            $table->string('beneficiary_type', 32);
            $table->string('provider', 32);

            // IFX individual
            $table->string('first_names', 150)->nullable();
            $table->string('last_name', 150)->nullable();

            // IFX corporate (и общее отображаемое имя)
            $table->string('name', 150);

            // Контактные данные / идемпотентность IFX
            $table->string('unique_reference', 128)->nullable()->unique();
            // Статус синхронизации с внешней системой (IFX)
            $table->string('status', 64)->default('local_only');

            $table->timestamps();

            $table->index('status', 'idx_beneficiaries_status');
            $table->index('provider', 'idx_beneficiaries_provider');

            $table->index(
                ['beneficiary_type', 'first_names', 'last_name'],
                'idx_beneficiaries_name_search'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
