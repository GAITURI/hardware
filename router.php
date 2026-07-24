<?php
/**
 * Mambo Hardware — Canonical Front Controller Router (Root Standalone)
 */
session_start();

// Intercept the request path and strip query strings/trailing slashes for matching
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = rtrim($requestUri, '/');

// Since this file physically lives in the root directory, __DIR__ resolves to the project root
define('APP_ROOT', __DIR__);

// Automated API pass-through handler
// Dynamically catches background requests to /api/cart_add.php, etc.
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
    // ── HOME / DASHBOARD ROUTING ──
    case '':
    case '/index.php':
        header("Location: /dashboard/dashboard.php");
        exit;

    case '/dashboard':
    case '/dashboard/dashboard.php':
        require_once APP_ROOT . '/db_connection.php';
        require_once APP_ROOT . '/dashboard/dashboard.php';
        break;

    // ── CART & PRODUCT ROUTING ──
    case '/cart/product':
    case '/cart/product.php':
        require_once APP_ROOT . '/db_connection.php';
        require_once APP_ROOT . '/cart/product.php';
        break;

    case '/cart':
    case '/cart/cart.php':
        require_once APP_ROOT . '/db_connection.php';
        require_once APP_ROOT . '/cart/cart.php';
        break;

    // ── ABOUT PAGE ROUTING (Handles all URL variations) ──
    case '/about':
    case '/about.php':
    case '/about/about.php':
        require_once APP_ROOT . '/db_connection.php'; // Fixed missing slash

        // Check if about.php lives in /about/about.php or root /about.php
        if (file_exists(APP_ROOT . '/about/about.php')) {
            require_once APP_ROOT . '/about/about.php';
        } else if (file_exists(APP_ROOT . '/about.php')) {
            require_once APP_ROOT . '/about.php';
        } else {
            http_response_code(404);
            echo "<h3>404 Error: About page template file missing.</h3>";
        }
        break;

    // ── FALLBACK 404 CATCHER ──
    default:
        http_response_code(404);
        echo "<h3>404 Error: Router could not resolve path asset: " . htmlspecialchars($route) . "</h3>";
        break;
}