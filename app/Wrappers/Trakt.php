<?php
namespace App\Wrappers;

class Trakt extends Base {
    private $username = '';

    function __construct(string $username) {
        if (!isset($_ENV['TRAKT_CLIENT_ID'])) {
            throw new \Exception('You need to set your Trakt client id!');
        }

        $client_id = $_ENV['TRAKT_CLIENT_ID'];
        parent::__construct('https://api.trakt.tv', [
            "Content-Type: application/json",
            "trakt-api-version: 2",
            "trakt-api-key: " . $client_id
        ]);
        $this->username = $username;
    }

    public function stats(): object {
        return $this->request("/users/{$this->username}/stats")->data;
    }

    public function watching(): object | false {
        $res = $this->request("/users/{$this->username}/watching");
        if ($res->success && $res->data) {
            return $res->data;
        }
        return false;
    }

    public function watched(): array {
        return $this->request("/users/{$this->username}/history")->data;
    }
}
