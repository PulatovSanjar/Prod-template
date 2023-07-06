<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class TranslatorService
{
    /**
     * Get all locales in /lang directory
     *
     * @return array
     */
    public function getAllLocales(): array
    {
        $languages = [];

        foreach (scandir(lang_path()) as $element) {
            if (in_array($element, config('translatable.locales'))) {
                $languages[] = $element;
            }
        }

        return $languages;
    }

    /**
     * Get all name of files without the extension
     *
     * @param string $locale
     * @return array
     */
    public function getAllFiles(string $locale): array
    {
        $files = scandir(lang_path($locale));
        unset($files[0], $files[1]);

        return array_values(Arr::map($files, fn($file) => Str::remove('.php', $file)));
    }

    /**
     * Get all translations in /lang/{$locale} directory
     *
     * @param string $locale
     * @return array
     */
    public function getAllTranslations(string $locale): array
    {
        $files = $this->getAllFiles($locale);

        $translations = [];

        foreach ($files as $file) {
            $translations[$file] = Arr::dot(__($file));
        }

        return Arr::dot($translations);
    }
}
