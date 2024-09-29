<?php
use App\Constants;
use App\Controllers\BackloggdController;
use App\Controllers\TraktController;
use App\Helpers\Env;
use App\Helpers\Render;
require_once __DIR__ . '/../vendor/autoload.php';

$router = new \Bramus\Router\Router();

Env::parse(__DIR__ . '/../.env');

$router->get('/', function () {
  Render::page('home', [
    'services' => Constants::SERVICES
  ]);
});

// Trakt (https://trakt.tv)
$router->mount('/trakt', function () use ($router) {
  $router->get('/', [TraktController::class, 'index']);
  $router->get('/stats', [TraktController::class, 'stats']);
  $router->get('/watch', [TraktController::class, 'watch']);
});

// Backloggd (https://backloggd.com)
$router->mount('/backloggd', function () use ($router) {
  $router->get('/', [BackloggdController::class, 'index']);
  $router->get('/played', [BackloggdController::class, 'played']);
});

$router->setNamespace('\App\Controllers');
$router->run();
