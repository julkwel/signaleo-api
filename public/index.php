<?php

use App\Kernel;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/config/bootstrap.php';

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

//// Allow from any origin
//if (isset($_SERVER['HTTP_ORIGIN'])) {
//    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
//    // you want to allow, and if so:
//    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
//    header('Access-Control-Allow-Credentials: true');
//    header('Access-Control-Max-Age: 86400');    // cache for 1 day
//}
//
//// Access-Control headers are received during OPTIONS requests
//if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
//
//    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
//        // may also be using PUT, PATCH, HEAD etc
//        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
//
//    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
//        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
//
//    exit(0);
//}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts([$trustedHosts]);
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
