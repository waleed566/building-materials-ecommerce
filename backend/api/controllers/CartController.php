<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../middleware/AuthMiddleware.php';

class CartController
{
    public function index(): void
    {
        $user = AuthMiddleware::requireAuth();
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare('SELECT ci.*, p.name, p.image_url FROM cart_items ci INNER JOIN products p ON p.id = ci.product_id INNER JOIN carts c ON c.id = ci.cart_id WHERE c.user_id = :user_id');
        $stmt->execute(['user_id' => $user['user_id']]);

        echo json_encode(['cart' => $stmt->fetchAll()]);
    }

    public function add(): void
    {
        $user = AuthMiddleware::requireAuth();
        $input = json_decode(file_get_contents('php://input'), true);

        $pdo = Database::getConnection();

        $cartStmt = $pdo->prepare('SELECT id FROM carts WHERE user_id = :user_id LIMIT 1');
        $cartStmt->execute(['user_id' => $user['user_id']]);
        $cart = $cartStmt->fetch();

        if (!$cart) {
            $insertCart = $pdo->prepare('INSERT INTO carts (user_id) VALUES (:user_id)');
            $insertCart->execute(['user_id' => $user['user_id']]);
            $cartId = (int)$pdo->lastInsertId();
        } else {
            $cartId = (int)$cart['id'];
        }

        $productId = (int)($input['product_id'] ?? 0);
        $quantity = (int)($input['quantity'] ?? 1);
        $unitPrice = (float)($input['unit_price'] ?? 0);

        if ($productId <= 0 || $quantity <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid product or quantity']);
            return;
        }

        $existingStmt = $pdo->prepare('SELECT id FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id LIMIT 1');
        $existingStmt->execute(['cart_id' => $cartId, 'product_id' => $productId]);
        $existing = $existingStmt->fetch();

        if ($existing) {
            $updateStmt = $pdo->prepare('UPDATE cart_items SET quantity = quantity + :qty WHERE cart_id = :cart_id AND product_id = :product_id');
            $updateStmt->execute(['qty' => $quantity, 'cart_id' => $cartId, 'product_id' => $productId]);
        } else {
            $insertStmt = $pdo->prepare('INSERT INTO cart_items (cart_id, product_id, quantity, unit_price) VALUES (:cart_id, :product_id, :quantity, :unit_price)');
            $insertStmt->execute([
                'cart_id' => $cartId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
            ]);
        }

        echo json_encode(['message' => 'Product added to cart']);
    }
}
