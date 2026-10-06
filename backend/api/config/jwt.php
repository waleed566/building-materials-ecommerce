<?php

declare(strict_types=1);

class Jwt {
    private static string $secret = 'construction_store_secret_key';

    public static function encode(array $payload): string {
        $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payloadEncoded = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', "$header.$payloadEncoded", self::$secret, true);
        $signatureEncoded = base64_encode($signature);

        return "$header.$payloadEncoded.$signatureEncoded";
    }

    public static function decode(string $token): ?array {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$header, $payload, $signature] = $parts;
        $expected = hash_hmac('sha256', "$header.$payload", self::$secret, true);
        $expectedEncoded = base64_encode($expected);

        if (!hash_equals($expectedEncoded, $signature)) {
            return null;
        }

        $decoded = json_decode(base64_decode($payload), true);
        return is_array($decoded) ? $decoded : null;
    }
}
