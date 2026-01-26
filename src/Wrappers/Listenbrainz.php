<?php

namespace App\Wrappers;

use App\Cache\ICache;
use App\Helpers\Env;

class Listenbrainz extends Base {
    private const COVER_BASE_URL = 'https://coverartarchive.org/release/%s/front';
    private string $username;

    public function __construct(string $username, ?ICache $engine = null) {
        $headers = [];
        $token = Env::listenbrainz_token();
        if ($token !== null) {
            $headers['Authorization'] = "Token $token";
        }

        parent::__construct('https://api.listenbrainz.org', [], $headers, $engine);
        $this->username = $username;
    }

    public static function poster_url(string $id): string
    {
        return sprintf(self::COVER_BASE_URL, $id);
    }

    public function listened(): ?array {
        $cache = $this->getCache('listenbrainz', 'listened', $this->username);
        $listened = $cache->exists ? $cache->data : $this->_fetchListened();

        return $listened;
    }

    private function _fetchListened(): ?array {
        $res = $this->request("/1/user/{$this->username}/listens");
        if ($res->success && $res->data) {
            $listens = $res->data->payload->listens;
            $this->setCache('listenbrainz', 'listened', $this->username, json_encode($listens));
            return $listens;
        }
        return null;
    }
}
