<?php
namespace App\Wrappers;

class Base {
    private $base_url = '';
    private $headers = [];
    private $params = [];

    function __construct(string $base_url, array $headers = [], array $params = []) {
        $this->base_url = $base_url;
        $this->headers = $headers;
        $this->params = $params;
    }

    protected function request (string $endpoint) {
        $ch = curl_init();
        $url = $this->base_url . $endpoint . '?' . http_build_query($this->params);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);

        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($code >= 200 && $code < 300) {
          return (object) ['success' => true, 'data' => !empty($response) ? json_decode($response) : null];
        }
        return (object) ['success' => false, 'data' => null];
    }
}
