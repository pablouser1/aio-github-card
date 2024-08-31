<?php
namespace App\Wrappers;

class Base {
  private string $base_url = '';
  private array $params = [];
  private array $headers = [];

  protected bool $spoof_ua = false;

  private const SPOOF_UA = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36";

  function __construct(string $base_url, array $params = [], array $headers = []) {
    $this->base_url = $base_url;
    $this->params = $params;

    if ($this->spoof_ua) {
      $ua = self::SPOOF_UA;
      $headers[] = "User-Agent: $ua";
    }

    $this->headers = $headers;
  }

  protected function request(string $endpoint, array $params = [], bool $isJson = true): object {
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
}
