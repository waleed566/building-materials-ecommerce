<?php

declare(strict_types=1);

require __DIR__ . '/../config/jwt.php';

class AuthMiddleware {
    public static function requireAuth(): array {
        $headers = getallheaders();
        $authorization = $headers['Authorization'] ?? '';

        if (!str_starts_with($authorization, 'Bearer ')) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $token = trim(str_replace('Bearer ', '', $authorization));
        $payload = Jwt::decode($token);

        if (!$payload) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid or expired token']);
            exit;
        }

        return $payload;
    }
}
