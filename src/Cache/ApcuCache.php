<?php
namespace App\Cache;
use App\Models\CacheData;

class ApcuCache implements ICache {
  function __construct() {
    if (!(extension_loaded('apcu') && apcu_enabled())) {
      throw new \Exception('APCu not enabled');
    }
  }

  public function get(string $cache_key): CacheData {
    $ok = false;
    $fetch = apcu_fetch($cache_key, $ok);
    return new CacheData($ok, $ok ? json_decode($fetch) : []);
  }

  public function exists(string $cache_key): bool {
    return apcu_exists($cache_key);
  }

  public function set(string $cache_key, string $data, int $timeout = 86400): void {
    apcu_store($cache_key, $data, $timeout);
  }
}
