<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public const OPTIONS_PUBLIC = 'public';

    public static function createFile(string $path, string $filename, string $extension, string $content = ''): string
    {
        self::createDirectory($path);

        $fullPath = self::getFullPath($path) . $filename . '.' . $extension;
        $handler = fopen($fullPath, 'ab'); // append + бинарный режим надёжнее

        if ($handler === false) {
            throw new \RuntimeException("Cannot open file for writing: {$fullPath}");
        }

        if ($content !== '') {
            $bytes = fwrite($handler, $content);
            if ($bytes === false) {
                fclose($handler);
                throw new \RuntimeException("Cannot write to file: {$fullPath}");
            }
        }

        fclose($handler);

        // Возвращаем относительный путь в сторадже
        return $path . $filename . '.' . $extension;
    }

    public static function createDirectory(string $path): bool
    {
        return Storage::disk('public')->makeDirectory($path);
    }

    public static function saveFile(UploadedFile $file, string $path, string $options = self::OPTIONS_PUBLIC): string
    {
        $stored = $file->store($path, $options); // тип PHPStan: string|false
        if ($stored === false) {
            throw new \RuntimeException("Failed to store uploaded file to '{$path}' on disk '{$options}'");
        }

        return $stored; // строго string
    }

    public static function deleteFile(string $path): bool
    {
        return Storage::disk('public')->delete($path);
    }

    public static function getFullPath(string $path): string
    {
        return Storage::disk('public')->path($path);
    }
}
