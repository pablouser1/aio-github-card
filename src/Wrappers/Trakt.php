<?php
namespace App\Wrappers;
use App\Helpers\Env;

class Trakt extends Base {
  private string $username = '';

  function __construct(string $username) {
    $client_id = Env::trakt_client_id();
    if ($client_id === '') {
      throw new \Exception('You need to set your Trakt client id!');
    }

    parent::__construct('https://api.trakt.tv', [], [
      "content-type: application/json",
      "trakt-api-version: 2",
      "trakt-api-key: $client_id"
    ]);
    $this->username = $username;
  }

  public function stats(): ?object {
    $res = $this->request("/users/{$this->username}/stats");
    if ($res->success && $res->data) {
      return $res->data;
    }
    return null;
  }

  public function watching(): ?object {
    $res = $this->request("/users/{$this->username}/watching");
    if ($res->success && $res->data) {
      return $res->data;
    }
    return null;
  }

  public function watched(): ?array {
    $res = $this->request("/users/{$this->username}/history");
    if ($res->success && $res->data) {
      return $res->data;
    }
    return null;
  }
}
