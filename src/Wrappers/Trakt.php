<?php
namespace App\Wrappers;
use App\Cache\ICache;
use App\Helpers\Env;

class Trakt extends Base {
  private string $username;

  function __construct(string $username, ?ICache $engine = null) {
    $client_id = Env::trakt_client_id();
    if ($client_id === '') {
      throw new \Exception('You need to set your Trakt client id!');
    }

    parent::__construct('https://api.trakt.tv', [], [
      'content-type: application/json',
      'trakt-api-version: 2',
      "trakt-api-key: $client_id"
    ], $engine);
    $this->username = $username;
  }

  public function stats(): ?object {
    $cache = $this->getCache('trakt', 'stats', $this->username);
    return $cache->exists ? $cache->data : $this->_fetchStats();
  }

  public function watching(): ?object {
    return $this->_fetchWatching();
  }

  public function watched(): ?array {
    $cache = $this->getCache('trakt', 'watched', $this->username);
    return $cache->exists ? $cache->data : $this->_fetchWatched();
  }

  private function _fetchStats(): ?object {
    $res = $this->request("/users/{$this->username}/stats");
    if ($res->success && $res->data) {
      $this->setCache('trakt', 'stats', $this->username, json_encode($res->data));
      return $res->data;
    }
    return null;
  }

  private function _fetchWatching(): ?object {
    $res = $this->request("/users/{$this->username}/watching");
    if ($res->success && $res->data) {
      return $res->data;
    }
    return null;
  }

  private function _fetchWatched(): ?array {
    $res = $this->request("/users/{$this->username}/history");
    if ($res->success && $res->data) {
      $this->setCache('trakt', 'watched', $this->username, json_encode($res->data));
      return $res->data;
    }
    return null;
  }
}
