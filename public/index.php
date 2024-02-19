<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\DependencyInjection\ContainerManager;
use App\Exception\RouteNotFoundException;
use App\Routing\Router;

if (php_sapi_name() !== 'cli' && preg_match('/\.(ico|png|jpg|jpeg|css|js|gif)$/', $_SERVER['REQUEST_URI'])) {
    return false;
}

try {
    $containerManager = new ContainerManager();
    $router = new Router($containerManager->buildContainer());
} catch (Exception $e) {
    var_dump($e);
    exit();
}

//  ---------------------------------------------------------------------

if (php_sapi_name() === 'cli') {
    return;
}

[
    'REQUEST_URI' => $uri,
    'REQUEST_METHOD' => $httpMethod
] = $_SERVER;

try {
    echo $router->execute($uri, $httpMethod);
} catch (RouteNotFoundException) {
    http_response_code(404);
    echo "Page non trouvée";
} catch (Exception $e) {
    http_response_code(500);
    var_dump($e);
    echo "Erreur interne, veuillez contacter l'administrateur";
}