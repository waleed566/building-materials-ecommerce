<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require __DIR__ . '/config/database.php';

$routes = [
    'POST /api/auth/register' => 'AuthController@register',
    'POST /api/auth/login' => 'AuthController@login',
    'GET /api/products' => 'ProductController@index',
    'GET /api/products/{id}' => 'ProductController@show',
    'POST /api/products' => 'ProductController@store',
    'GET /api/cart' => 'CartController@index',
    'POST /api/cart/add' => 'CartController@add',
    'POST /api/orders' => 'OrderController@store',
    'GET /api/admin/dashboard' => 'AdminController@dashboard',
];

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$matched = false;

foreach ($routes as $route => $handler) {
    [$httpMethod, $routePath] = explode(' ', $route, 2);

    if ($httpMethod !== $method) {
        continue;
    }

    $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
    $pattern = str_replace('/', '\/', $pattern);

    if (preg_match("/^{$pattern}$/", $path)) {
        $matched = true;
        [$controllerName, $action] = explode('@', $handler);
        require __DIR__ . '/controllers/' . $controllerName . '.php';

        $controller = new $controllerName();
        $controller->$action();
        break;
    }
}

if (!$matched) {
    http_response_code(404);
    echo json_encode(['error' => 'Route not found']);
}
