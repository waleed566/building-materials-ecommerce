<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';

class ProductController {
    public function index(): void {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT p.*, c.name AS category_name FROM products p INNER JOIN categories c ON c.id = p.category_id WHERE p.is_active = 1 ORDER BY p.created_at DESC');
        $products = $stmt->fetchAll();
        echo json_encode(['products' => $products]);
    }

    public function show(): void {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = array_values(array_filter(explode('/', $path), fn($segment) => $segment !== ''));
        $id = end($segments);

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT p.*, c.name AS category_name FROM products p INNER JOIN categories c ON c.id = p.category_id WHERE p.id = :id AND p.is_active = 1 LIMIT 1');
        $stmt->execute(['id' => (int)$id]);
        $product = $stmt->fetch();

        if (!$product) {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
            return;
        }

        echo json_encode(['product' => $product]);
    }

    public function store(): void {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['name']) || empty($input['price'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Name and price are required']);
            return;
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO products (category_id, seller_id, name, slug, short_description, description, price, stock, image_url, is_active) VALUES (:category_id, :seller_id, :name, :slug, :short_description, :description, :price, :stock, :image_url, 1)');
        $stmt->execute([
            'category_id' => $input['category_id'] ?? 1,
            'seller_id' => $input['seller_id'] ?? 1,
            'name' => $input['name'],
            'slug' => strtolower(str_replace(' ', '-', $input['name'])),
            'short_description' => $input['short_description'] ?? '',
            'description' => $input['description'] ?? '',
            'price' => (float)$input['price'],
            'stock' => $input['stock'] ?? 0,
            'image_url' => $input['image_url'] ?? '',
        ]);

        echo json_encode(['message' => 'Product created successfully']);
    }
}
