<?php
declare(strict_types=1);

namespace App\Services\Alchemy;

final class AlchemySigner
{
    public static function make(string $secret, string $timestamp, string $method, string $requestPath, ?array $body = null): string
    {
        $method = strtoupper($method);
        $content = $timestamp . $method . $requestPath . self::canonicalizeBody($body);

        return base64_encode(hash_hmac('sha256', $content, $secret, true));
    }

    public static function canonicalizeBody(?array $body): string
    {
        if (!$body) return '';
        $filtered = array_filter($body, static fn($v) => $v !== null && $v !== '');
        ksort($filtered);

        return (string) json_encode($filtered, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
