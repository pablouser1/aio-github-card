<?php
namespace Helpers;
class Api {
    const BASE_URL = 'https://api.trakt.tv';
    private $client_id = '';
    private $username = '';

    function __construct(string $username) {
        $this->client_id = isset($_ENV['TRAKT_CLIENT_ID']) ? $_ENV['TRAKT_CLIENT_ID'] : '';
        $this->username = $username;
    }

    private function send(string $endpoint): object {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::BASE_URL . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/json",
          "trakt-api-version: 2",
          "trakt-api-key: " . $this->client_id
        ));

        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($code === 204) {
          return (object) ['success' => true, 'data' => null];
        }
        return (object) ['success' => true, 'data' => json_decode($response)];
    }

    public function stats(): object {
        return $this->send("/users/{$this->username}/stats")->data;
    }

    public function watching(): object | false {
        $res = $this->send("/users/{$this->username}/watching");
        if ($res->success && $res->data) {
            return $res->data;
        }
        return false;
    }

    public function watched(): array {
        return $this->send("/users/{$this->username}/history")->data;
    }
}
