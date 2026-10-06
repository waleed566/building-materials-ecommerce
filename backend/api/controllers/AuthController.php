<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../config/jwt.php';

class AuthController {
    public function register(): void {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['full_name']) || empty($input['email']) || empty($input['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        $pdo = Database::getConnection();
        $email = trim($input['email']);

        $exists = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
        $exists->execute(['email' => $email]);

        if ($exists->fetch()) {
            http_response_code(409);
            echo json_encode(['error' => 'Email already registered']);
            return;
        }

        $stmt = $pdo->prepare('INSERT INTO users (role_id, full_name, email, phone, password_hash, city, address) VALUES (1, :full_name, :email, :phone, :password_hash, :city, :address)');
        $stmt->execute([
            'full_name' => $input['full_name'],
            'email' => $email,
            'phone' => $input['phone'] ?? null,
            'password_hash' => password_hash($input['password'], PASSWORD_BCRYPT),
            'city' => $input['city'] ?? null,
            'address' => $input['address'] ?? null,
        ]);

        $userId = (int)$pdo->lastInsertId();
        $token = Jwt::encode([
            'user_id' => $userId,
            'email' => $email,
            'role' => 'customer',
            'exp' => time() + 86400,
        ]);

        echo json_encode([
            'message' => 'User registered successfully',
            'token' => $token,
        ]);
    }

    public function login(): void {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['email']) || empty($input['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Email and password are required']);
            return;
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT u.id, u.full_name, u.email, u.password_hash, r.name AS role_name FROM users u INNER JOIN roles r ON r.id = u.role_id WHERE u.email = :email LIMIT 1');
        $stmt->execute(['email' => trim($input['email'])]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($input['password'], $user['password_hash'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid email or password']);
            return;
        }

        $token = Jwt::encode([
            'user_id' => (int)$user['id'],
            'email' => $user['email'],
            'role' => $user['role_name'],
            'exp' => time() + 86400,
        ]);

        echo json_encode([
            'token' => $token,
            'user' => [
                'id' => (int)$user['id'],
                'full_name' => $user['full_name'],
                'email' => $user['email'],
                'role' => $user['role_name'],
            ],
        ]);
    }
}
