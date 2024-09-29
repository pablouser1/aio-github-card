<?php
namespace App\Helpers;
use App\Cache\ApcuCache;
use App\Cache\ICache;
use App\Cache\JSONCache;
use App\Cache\RedisCache;

class Cache {
  public static function getEngine(): ?ICache {
    $engine = null;

    $key = Env::request_cache();

    switch ($key) {
      case 'apcu':
        $engine = new ApcuCache();
        break;
      case 'json':
        $engine = new JSONCache();
        break;
      case 'redis':
        $config = Env::redis();
        $engine = new RedisCache($config['host'], $config['port'], $config['password']);
    }

    return $engine;
  }
}
