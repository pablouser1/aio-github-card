<?php
namespace App\Wrappers;

use App\Cache\ICache;
use App\Models\Game;

class Backloggd extends Base {
  public const BASE_URL = 'https://backloggd.com';

  protected bool $spoof_ua = true;
  private string $username;

  public function __construct(string $username, ?ICache $engine = null) {
    parent::__construct(self::BASE_URL, [], [], $engine);
    $this->username = $username;
  }

  public function played(): ?array {
    $games = [];
    $cache = $this->getCache('backloggd', 'played', $this->username);
    $games = $cache->exists ? $cache->data : $this->_fetchPlayed();
    if ($games !== null && count($games) > 0) {
      return $games;
    }

    // IT JUST HAS NO GAMES
    return null;
  }

  private function _fetchPlayed(): ?array {
    $res = $this->request("/u/{$this->username}/games/added:desc/", [], false);
    if (!$res->success) {
      return null;
    }

    $dom = new \DOMDocument();
    @$dom->loadHTML($res->data);
    $xp = new \DOMXPath($dom);

    $list = $dom->getElementById('game-lists');

    if ($list === null) {
      return null;
    }

    $els = $xp->query('.//div[@game_id and contains(@class, "card")]', $list);
    $games = [];
    foreach ($els as $el) {
      $imgs = $xp->query('.//img[@alt and contains(@class, "card-img")]', $el);
      $anchors = $xp->query('.//a[contains(@class, "cover-link")]', $el);
      if ($imgs->count() > 0 && $anchors->count() > 0) {

        // Get name and image from <img>
        $img = $imgs->item(0);
        $name = $img->getAttribute('alt');
        $image = $img->getAttribute('src');

        // Get url from <a>
        $a = $anchors->item(0);
        $path = $a->getAttribute('href');

        // Optionally get user review
        $rating = -1;
        $ratingStr = $el->getAttribute('data-rating');
        if ($ratingStr !== '') {
          $rating = intval($ratingStr);
        }

        $games[] = new Game($name, $image, $path, $rating);
      }
    }

    $this->setCache('backloggd', 'played', $this->username, json_encode($games));

    return $games;
  }
}
