<?php
declare(strict_types=1);

namespace App\Providers;

use App\Models\Translator;
use App\Services\TranslatorService;
use Illuminate\Support\ServiceProvider;

class TranslatorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (isDatabaseAvailable('translators')) {
            $this->replaceTranslations();
        }
    }

    private function replaceTranslations(): void
    {
        /** @var TranslatorService $service */
        $service = app(TranslatorService::class);

        $locale = app()->getLocale();
        $hardTranslations = $service->getAllTranslations($locale);

        /** @var \Illuminate\Database\Eloquent\Collection<int, Translator> $translations */
        $translations = Translator::query()
            ->with('translations')
            ->get();

        foreach ($translations as $translation) {
            if (!isset($hardTranslations[$translation->key])) {
                continue;
            }

            $line = $translation->translate($locale)?->getAttribute('value');

            if ($line !== null) {
                app('translator')->addLines([
                    $translation->key => $line,
                ], $locale);
            }
        }
    }
}
