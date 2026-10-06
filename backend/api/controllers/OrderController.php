<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../middleware/AuthMiddleware.php';

class OrderController {
    public function store(): void {
        $user = AuthMiddleware::requireAuth();
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['items']) || empty($input['shipping_address'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Order items and shipping address are required']);
            return;
        }

        $pdo = Database::getConnection();
        $items = $input['items'];
        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += ((float)$item['unit_price'] * (int)$item['quantity']);
        }

        $shippingFee = (float)($input['shipping_fee'] ?? 0);
        $tax = (float)($input['tax'] ?? 0);
        $total = $subtotal + $shippingFee + $tax;

        $orderNumber = 'ORD-' . date('YmdHis') . '-' . rand(1000, 9999);

        $stmt = $pdo->prepare('INSERT INTO orders (user_id, order_number, subtotal, shipping_fee, tax, total, payment_method, payment_status, shipping_address, notes) VALUES (:user_id, :order_number, :subtotal, :shipping_fee, :tax, :total, :payment_method, :payment_status, :shipping_address, :notes)');
        $stmt->execute([
            'user_id' => $user['user_id'],
            'order_number' => $orderNumber,
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => $input['payment_method'] ?? 'cash_on_delivery',
            'payment_status' => 'pending',
            'shipping_address' => $input['shipping_address'],
            'notes' => $input['notes'] ?? null,
        ]);

        $orderId = (int)$pdo->lastInsertId();

        foreach ($items as $item) {
            $insertItem = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price) VALUES (:order_id, :product_id, :quantity, :unit_price, :total_price)');
            $insertItem->execute([
                'order_id' => $orderId,
                'product_id' => (int)$item['product_id'],
                'quantity' => (int)$item['quantity'],
                'unit_price' => (float)$item['unit_price'],
                'total_price' => (float)$item['unit_price'] * (int)$item['quantity'],
            ]);
        }

        echo json_encode([
            'message' => 'Order created successfully',
            'order_number' => $orderNumber,
            'total' => $total,
        ]);
    }
}
