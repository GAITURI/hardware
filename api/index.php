
<?php
// TEMPORARY DEBUGGING: Force PHP to output hidden fatal errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * Mambo Hardware — Monolithic Front Controller & API Gateway
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Intercept request path and strip query strings/trailing slashes
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = rtrim($requestUri, '/');



define('PROJECT_ROOT', dirname(__DIR__));
// Project Root Directory
define('APP_ROOT', __DIR__);

// ── MATRIX 1: API UTILITY ROUTER ──
if (strpos($route, '/api/') === 0) {
    $relativeScriptPath = str_replace('/api', '', $route);
    $targetApiScript = APP_ROOT . $relativeScriptPath;
    if (file_exists($targetApiScript) && basename($route) !=='index.php'){
        require_once APP_ROOT . '/db_connection.php';
        require_once $targetApiScript;
        exit;
    }
}
function renderPage($path) {
    $dbPath = APP_ROOT . '/db_connection.php';
    if (file_exists($dbPath)) {
        require_once $dbPath;
    }

    $fullPath = APP_ROOT . $path;
    if (file_exists($fullPath)) {
        require_once $fullPath;
    } else {
        http_response_code(404);
        echo "<h3>404 Error: Could not locate page asset at: " . htmlspecialchars($path) . "</h3>";
    }
}
// ── MATRIX 2: PRESENTATIONAL LAYOUT ROUTER ──
switch ($route) {
    // ── DASHBOARD / HOME ──
    case '':
    case '/index.php':
    case '/dashboard':
    case '/dashboard/dashboard.php':
        renderPage('/dashboard/dashboard.php');
        break;

    // ── CART & PRODUCTS ──
    case '/cart/product':
    case '/cart/product.php':
        renderPage('/cart/product.php');
        break;

    case '/cart':
    case '/cart/cart.php':
        renderPage('/cart/cart.php');
        break;

    // ── ABOUT PAGE ──
    case '/about':
    case '/about.php':
    case '/about/about.php':
        renderPage ('/about/about.php');
        break;

    // ── FALLBACK 404 CATCHER ──
    default:
        http_response_code(404);
        echo "<h3>404 Error: Router could not resolve path asset: " . htmlspecialchars($route) . "</h3>";
        break;
}

