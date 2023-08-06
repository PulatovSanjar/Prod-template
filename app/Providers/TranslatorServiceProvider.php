<?php

namespace App\Providers;

use App\Models\Translator;
use App\Services\TranslatorService;
use Illuminate\Support\ServiceProvider;

class TranslatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (isDatabaseAvailable('translators')) {
            $this->replaceTranslations();
        }
    }

    private function replaceTranslations()
    {
        /** @var TranslatorService $service */
        $service = resolve(TranslatorService::class);
        $hardTranslations = $service->getAllTranslations(app()->getLocale());

        $translations = Translator::query()->with('translations')->get();

        foreach ($translations as $translation) {

            if (isset($hardTranslations[$translation->key])) {

                app('translator')->addLines([
                    $translation->key => $translation->translate(app()->getLocale())?->value
                ], app()->getLocale());

            }
        }
    }
}
