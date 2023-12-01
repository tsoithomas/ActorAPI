<?php
require_once('vendor/autoload.php');

class Router {
    private $routes = [];

    public function addRoute($path, $handler) {
        $this->routes[$path] = $handler;
    }

    public function handleRequest($path) {
        if (array_key_exists($path, $this->routes)) {
            $handler = $this->routes[$path];
            $handler();
        } else {
            $this->notFound();
        }
    }

    public function notFound() {
        header("HTTP/1.0 404 Not Found");
        echo '404 Not Found';
    }
}

$router = new Router();


$router->addRoute('/test/actorcount/', function () {
    include_once("./controllers/actorcount.php");
});


$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->handleRequest($path);
