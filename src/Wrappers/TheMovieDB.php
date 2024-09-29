<?php
namespace App\Wrappers;
use App\Cache\ICache;
use App\Helpers\Env;

class TheMovieDB extends Base {
  const IMAGE_URL = 'https://image.tmdb.org/t/p';
  private int $id;
  public string $type;

  function __construct(int $id, string $type, ?ICache $engine = null) {
    $token = Env::tmdb_token();
    if ($token === '') {
      throw new \Exception('You need to set your The Movie DB token!');
    }

    parent::__construct('https://api.themoviedb.org/3', [
      'api_key' => $token
    ], ["Content-Type: application/json; charset=utf-8"], $engine);
    $this->id = $id;
    $this->type = $type;
  }

  public function poster(): string {
    $cache = $this->getCache('themoviedb', 'poster', "{$this->type}-{$this->id}");
    $data = $cache->exists ? $cache->data : $this->_fetchPoster();
    if ($data !== null) {
      return self::IMAGE_URL . '/w154' . $data->poster_path;
    }

    return '';
  }

  private function _fetchPoster(): ?object {
    $req = $this->request("/{$this->type}/{$this->id}");
    if ($req->success) {
      $this->setCache('themoviedb', 'poster', "{$this->type}-{$this->id}", json_encode($req->data));
      return $req->data;
    }
    return null;
  }
}
