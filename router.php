<?php
/**
 * Mambo Hardware — Canonical Front Controller Router
 */
session_start();

// Intercept the request path and strip query strings for matching
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = rtrim($requestUri, '/');

// Establish a rock-solid, absolute root file path reference
define('APP_ROOT', dirname(__DIR__));

// Automated API pass-through handler
// This dynamically catches requests to /api/cart_add.php, /api/get_products.php, etc.
if (strpos($route, '/api/') === 0) {
    $apiFile = APP_ROOT . $route;
    if (file_exists($apiFile)) {
        require_once APP_ROOT . '/db_connection.php';
        require_once $apiFile;
        exit;
    }
}

// Presentational Layout Router Matrix
switch ($route) {
    // 1. Root Entry Point
    case '':
    case '/index.php':
        // Explicitly trigger your initial redirect down to the dashboard folder
        header("Location: /dashboard/dashboard.php");
        exit;

    // 2. Dashboard View
    case '/dashboard/dashboard.php':
        require_once APP_ROOT . '/dashboard/dashboard.php';
        break;

    // 3. Product Details Page (e.g., clicked from dashboard)
    case '/cart/product.php':
        require_once APP_ROOT . '/db_connection.php';
        require_once APP_ROOT . '/cart/product.php';
        break;

    // 4. Final Shopping Cart View
    case '/cart/cart.php':
        require_once APP_ROOT . '/db_connection.php';
        require_once APP_ROOT . '/cart/cart.php';
        break;

    default:
        http_response_code(404);
        echo "<h3>404 Error: Router could not resolve path asset: " . htmlspecialchars($route) . "</h3>";
        break;
}