There is a critical path resolution bug in **Matrix 1** of your router code.

### ## The API Path Bug

In your current code:

```php
if (strpos($route, '/api/') === 0) {
    $fileName = basename($route); // e.g., "search_products.php"
    $targetApiScript = APP_ROOT . '/' . $fileName; // ❌ Resolves to /search_products.php at project root!

```

When a fetch request is made to `/api/search_products.php` or `/api/get_products.php`, `basename($route)` extracts `search_products.php`. Combining `APP_ROOT . '/' . $fileName` makes PHP look for the file directly in your root directory rather than inside the `/api/` subfolder, causing API requests to fail or fall through to 404s.

---

### ## Corrected Front Controller Router (`index.php`)

Here is the clean, fixed version. It preserves the original path mapping while ensuring API calls point accurately to `APP_ROOT . '/api/' . $fileName` (or `APP_ROOT . $route`). Clean URL aliases for your pages are also included:

```php
<?php
/**
 * Mambo Hardware — Monolithic Front Controller & API Gateway
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Intercept request path and strip query strings/trailing slashes
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = rtrim($requestUri, '/');

// Project Root Directory
define('APP_ROOT', __DIR__);

// ── MATRIX 1: API UTILITY ROUTER ──
if (strpos($route, '/api/') === 0) {
    // Preserve full path to search inside /api/ folder
    $targetApiScript = APP_ROOT . $route;

    if (file_exists($targetApiScript) && basename($route) !== 'index.php') {
        require_once APP_ROOT . '/db_connection.php';
        require_once $targetApiScript;
        exit;
    }
}

// ── MATRIX 2: PRESENTATIONAL LAYOUT ROUTER ──
switch ($route) {
    // ── DASHBOARD / HOME ──
    case '':
    case '/index.php':
        header("Location: /dashboard/dashboard.php");
        exit;

    case '/dashboard':
    case '/dashboard/dashboard.php':
        require_once APP_ROOT . '/db_connection.php';
        require_once APP_ROOT . '/dashboard/dashboard.php';
        break;

    // ── CART & PRODUCTS ──
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

    // ── ABOUT PAGE ──
    case '/about':
    case '/about.php':
    case '/about/about.php':
        require_once APP_ROOT . '/db_connection.php';

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

```