<?php

namespace App\Classes;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

Class FileService
{
    public const OPTIONS_PUBLIC = 'public';

    /**
     * @param string $path
     * @param string $filename
     * @param string $extension
     * @param string $content
     * @return string
     */
    public static function createFile(string $path, string $filename, string $extension, string $content = ''): string
    {
        self::createDirectory($path);

        $handler = fopen(self::getFullPath($path) . $filename . '.' . $extension, 'a');
        if ($content) {
            fputs($handler, $content);
        }
        fclose($handler);

        return $path . $filename . '.' . $extension;
    }

    /**
     * @param string $path
     * @return bool
     */
    public static function createDirectory(string $path): bool
    {
        return Storage::disk('public')->makeDirectory($path);
    }

    /**
     * @param UploadedFile $file
     * @param string $path
     * @param string $options
     * @return string
     */
    public static function saveFile(UploadedFile $file, string $path, string $options = self::OPTIONS_PUBLIC): string
    {
        return $file->store($path, $options);
    }

    /**
     * @param string $path
     * @return bool
     */
    public static function deleteFile(string $path): bool
    {
        return Storage::disk('public')->delete($path);
    }

    /**
     * @param string $path
     * @return string
     */
    public static function getFullPath(string $path): string
    {
        return Storage::disk('public')->path($path);
    }
}
