<?php
// PHP built-in web server router for CodeIgniter projects.
// Run with: php -S localhost:8080 server.php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Support running the app from a local prefix such as /portal_askara
$basePath = '/portal_askara';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
    if ($uri === '') {
        $uri = '/';
    }
}

$filename = __DIR__ . $uri;

if ($uri !== '/' && file_exists($filename) && is_file($filename)) {
    return false;
}

$_SERVER['SCRIPT_NAME'] = '/index.php';
require_once __DIR__ . '/index.php';
