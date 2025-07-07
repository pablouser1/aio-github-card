<?php
namespace App\Wrappers;
use App\Cache\ICache;
use App\Models\CacheData;

class Base {
  private const BROWSER_UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36';
  private string $base_url = '';
  private array $params = [];
  private array $headers = [];
  private ?ICache $cacheEngine = null;
  protected bool $spoof_ua = false;

  function __construct(string $base_url, array $params = [], array $headers = [], ?ICache $engine = null) {
    $this->base_url = $base_url;
    $this->params = $params;

    if ($this->spoof_ua) {
      $ua = self::BROWSER_UA;
      $headers[] = "User-Agent: $ua";
    }

    $this->headers = $headers;
    $this->cacheEngine = $engine;
  }

  protected function request(string $endpoint, array $params = [], string $cookies = '', bool $isJson = true): object {
    $ch = curl_init();
    $finalParams = array_merge($this->params, $params);
    $url = $this->base_url . $endpoint;

    if (count($finalParams) > 0) {
      $url .= "?" . http_build_query($finalParams);
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);

    // Additional cookies
    if ($cookies !== '') {
      curl_setopt($ch, CURLOPT_COOKIE, $cookies);
    }

    $response = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($code >= 200 && $code < 300) {
      $data = null;

      if (!empty($response)) {
        $data = $isJson ? json_decode($response) : $response;
      }
      return (object) ['success' => true, 'data' => $data];
    }
    return (object) ['success' => false, 'data' => null];
  }

  protected function getCache(string $service, string $endpoint, string $payload): CacheData {
    if ($this->cacheEngine !== null) {
      return $this->cacheEngine->get("$service-$endpoint-$payload");
    }

    return new CacheData(false, []);
  }

  protected function setCache(string $service, string $endpoint, string $payload, mixed $data): void {
    if ($this->cacheEngine !== null) {
      $this->cacheEngine->set("$service-$endpoint-$payload", $data);
    }
  }
}
