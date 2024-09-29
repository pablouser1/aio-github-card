<?php
namespace App\Cache;
use App\Models\CacheData;

class RedisCache implements ICache {
  private \Redis $client;
  function __construct(string $host, int $port, ?string $password) {
    $this->client = new \Redis();
    if (!$this->client->connect($host, $port)) {
      throw new \Exception('REDIS: Could not connnect to server');
    }

    if ($password) {
      if (!$this->client->auth($password)) {
          throw new \Exception('REDIS: Could not authenticate');
      }
    }
  }

  function __destruct() {
    $this->client->close();
  }

  public function get(string $cache_key): CacheData {
    $fetch = $this->client->get($cache_key);
    $data = $fetch !== false ? json_decode($fetch) : [];
    return new CacheData($fetch !== false, $data);
  }

  public function exists(string $cache_key): bool {
    return $this->client->exists($cache_key);
  }

  public function set(string $cache_key, string $data, int $timeout = 3600): void {
    $this->client->set($cache_key, $data, $timeout);
  }
}
