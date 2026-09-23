<?php
// api/ping.php
header('Content-Type: application/json');
echo json_encode([
    'status' => 'ok',
    'php_version' => phpversion(),
    'cwd' => getcwd()
]);