<?php
use App\Constants;
use App\Helpers\Render;
require_once __DIR__ . '/../vendor/autoload.php';

$router = new \Bramus\Router\Router();

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$router->get('/', function () {
  Render::page("home", [
    "services" => Constants::SERVICES
  ]);
});

// Trakt (https://trakt.tv)
$router->mount("/trakt", function () use ($router) {
  $router->get("/", "TraktController@index");
  $router->get("/stats", "TraktController@stats");
  $router->get("/watch", "TraktController@watch");
});

$router->mount("/backloggd", function () use ($router) {
  $router->get("/", "BackloggdController@index");
  $router->get("/played", "BackloggdController@played");
});

$router->setNamespace("\App\Controllers");
$router->run();
