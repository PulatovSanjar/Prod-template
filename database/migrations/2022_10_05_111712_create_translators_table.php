<?php
declare(strict_types=1);

use App\Models\Translator;
use App\Utilities\PermissionHelper;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translators', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->timestamps();
        });

        Schema::create('translator_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale');
            $table->foreignIdFor(Translator::class)->constrained()
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('value')->nullable();
        });

        PermissionHelper::apply('translators');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translator_translations');
        Schema::dropIfExists('translators');
    }
};
