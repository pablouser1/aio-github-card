<?php
namespace App\Wrappers;

use App\Models\Game;

class Backloggd extends Base {
  public const BASE_URL = "https://backloggd.com";
  private string $username;

  private const USER_AGENT = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36";

  public function __construct(string $username) {
    $ua = self::USER_AGENT;
    parent::__construct(self::BASE_URL, [], [
      "User-Agent: $ua"
    ]);
    $this->username = $username;
  }

  public function played(): ?Game {
    $res = $this->request("/u/" . $this->username . "/games/added:desc/", [], false);
    if (!$res->success) {
      return null;
    }

    $dom = new \DOMDocument();
    @$dom->loadHTML($res->data);
    $xp = new \DOMXPath($dom);

    $list = $dom->getElementById("game-lists");

    if ($list === null) {
      return null;
    }

    $els = $xp->query(".//div[@game_id and contains(@class, 'card')]", $list);
    $games = [];
    foreach ($els as $el) {
      $imgs = $xp->query(".//img[@alt and contains(@class, 'card-img')]", $el);
      $anchors = $xp->query(".//a[contains(@class, 'cover-link')]", $el);
      if ($imgs->count() > 0 && $anchors->count() > 0) {
        $game = new Game;

        // Get name and image from <img>
        $img = $imgs->item(0);
        $game->name = $img->getAttribute("alt");
        $game->image = $img->getAttribute("src");

        // Get url from <a>
        $a = $anchors->item(0);
        $game->path = $a->getAttribute("href");

        // Optionally get user review
        $ratingStr = $el->getAttribute("data-rating");
        if ($ratingStr !== "") {
          $game->rating = intval($ratingStr);
        }

        array_push($games, $game);
      }
    }

    if (count($games) === 0) {
      // IT JUST HAS NO GAMES
      return null;
    }

    // Pick random game
    $index = array_rand($games);
    return $games[$index];
  }
}
