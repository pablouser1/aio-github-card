<?php
namespace App\Cache;
use App\Models\CacheData;

class JSONCache implements ICache {
  private string $cache_path = __DIR__ . '/../../cache/requests';

  function __construct() {
    if (isset($_ENV['REQUEST_CACHE_JSON']) && !empty($_ENV['REQUEST_CACHE_JSON'])) {
      $this->cache_path = $_ENV['REQUST_CACHE_JSON'];
    }
  }

  public function get(string $cache_key): CacheData {
    $filename = $this->cache_path . '/' . $cache_key . '.json';
    if (is_file($filename)) {
      $json_string = file_get_contents($filename);
      $element = json_decode($json_string);
      return new CacheData(true, $element);
    }

    return new CacheData(false, []);
  }

  public function exists(string $cache_key): bool {
    $filename = $this->cache_path . '/' . $cache_key . '.json';
    return is_file($filename);
  }

  public function set(string $cache_key, string $data, int $timeout = 3600): void {
    file_put_contents($this->cache_path . '/' . $cache_key . '.json', $data);
  }
}
