<?php

$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uriPath = is_string($uriPath) ? $uriPath : '/';
$normalizedPath = rtrim($uriPath, '/');
$normalizedPath = $normalizedPath === '' ? '/' : $normalizedPath;

$projectRoot = __DIR__;
$publicRoot = $projectRoot . '/public';

$publicFile = realpath($publicRoot . $uriPath);
if ($uriPath !== '/' && $publicFile !== false && is_file($publicFile)) {
    return false;
}

$rootFile = realpath($projectRoot . $uriPath);
if ($uriPath !== '/' && $rootFile !== false && is_file($rootFile)) {
    return false;
}

if (str_starts_with($uriPath, '/assets/')) {
    $assetsRoot = realpath($projectRoot . '/assets');
    $assetPath = realpath($projectRoot . $uriPath);

    if ($assetsRoot === false || $assetPath === false || !str_starts_with($assetPath, $assetsRoot) || !is_file($assetPath)) {
        http_response_code(404);
        echo 'Asset not found';
        return true;
    }

    $extension = strtolower(pathinfo($assetPath, PATHINFO_EXTENSION));
    $mimeTypes = [
        'css' => 'text/css; charset=utf-8',
        'js' => 'application/javascript; charset=utf-8',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
    ];

    header('Content-Type: ' . ($mimeTypes[$extension] ?? 'application/octet-stream'));
    readfile($assetPath);
    return true;
}

$routes = [
    '/' => ['Home', 'index'],
    '/projects' => ['Home', 'projects'],
    '/contact' => ['Home', 'contact'],
    '/login' => ['User', 'login'],
    '/register' => ['User', 'register'],
    '/logout' => ['User', 'logout'],
    '/school' => ['School', 'index'],
];

if (isset($routes[$normalizedPath])) {
    $_GET['controller'] = $routes[$normalizedPath][0];
    $_GET['method'] = $routes[$normalizedPath][1];
}

if ($normalizedPath === '/public' || $normalizedPath === '/public/index.php') {
    require $publicRoot . '/index.php';
    return true;
}

require $publicRoot . '/index.php';
return true;
