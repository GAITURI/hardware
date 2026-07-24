<?php
/**
 * Mambo Hardware — Monolithic Front Controller & API Gateway
 */
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = rtrim($requestUri, '/');

// Resolves directly to the bundled 'api' directory in the cloud runtime
define('APP_ROOT', __DIR__);

// Matrix 1: Native API Utility Router
if (strpos($route, '/api/') === 0) {
    $fileName = basename($route);
    $targetApiScript = APP_ROOT . '/' . $fileName;

    if (file_exists($targetApiScript) && $fileName !== 'index.php') {
        require_once APP_ROOT . '/db_connection.php';
        require_once $targetApiScript;
        exit;
    }
}

// Matrix 2: Presentational Layout Router
switch ($route) {
    case '':
    case '/index.php':
        header("Location: /dashboard/dashboard.php");
        exit;

    case '/dashboard/dashboard.php':
        require_once APP_ROOT . '/dashboard/dashboard.php';
        break;

    case '/cart/product.php':
        require_once APP_ROOT . '/db_connection.php';
        require_once APP_ROOT . '/cart/product.php';
        break;

    case '/cart/cart.php':
        require_once APP_ROOT . '/db_connection.php';
        require_once APP_ROOT . '/cart/cart.php';
        break;

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
