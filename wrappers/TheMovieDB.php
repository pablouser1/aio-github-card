<?php
namespace Wrappers;

class TheMovieDB extends Base {
    const IMAGE_URL = 'https://image.tmdb.org/t/p';
    private int $id;
    public string $type;

    function __construct(int $id, string $type) {
        if (!isset($_ENV['TMDB_TOKEN'])) {
            throw new \Exception('You need to set your The Movie DB token!');
        }

        $token = $_ENV['TMDB_TOKEN'];
        parent::__construct('https://api.themoviedb.org/3', ["Content-Type: application/json; charset=utf-8"], [
            'api_key' => $token
        ]);
        $this->id = $id;
        $this->type = $type;
    }

    public function poster(): string {
        $req = $this->request("/{$this->type}/{$this->id}");
        if ($req->success) {
            $url = self::IMAGE_URL . '/w154' . $req->data->poster_path;
            return $url;
        }
        return '';
    }
}
