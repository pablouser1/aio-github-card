<?php
namespace App\Controllers;

use App\Constants;
use App\Helpers\Errors;
use App\Helpers\Misc;
use App\Helpers\Render;
use App\Models\Params;
use App\Wrappers\TheMovieDB;
use App\Wrappers\Trakt;

class TraktController {
  private const WIDTH_STATS = 300;
  private const WIDTH_WATCH = 380;

  private const MODES = ["stats", "watch"];

  public static function index() {
    Render::page("service", [
      "themes" => Constants::THEMES,
      "modes" => self::MODES
    ]);
  }

  public static function stats() {
    $params = new Params("stats", self::WIDTH_WATCH);
    $ok = $params->parse();

    if (!$ok) {
      Errors::show("Could not parse GET data! '{$params->getError()}' is invalid");
      return;
    }

    Misc::setupHeaders();

    $trakt = new Trakt($params->username);
    $stats = $trakt->stats();

    Render::card("trakt/stats", $params, $stats);
  }

  public static function watch() {
    $params = new Params("watch", self::WIDTH_WATCH);
    $ok = $params->parse();

    if (!$ok) {
      Errors::show("Could not parse GET data! '{$params->getError()}' is invalid");
      return;
    }

    Misc::setupHeaders();

    $trakt = new Trakt($params->username);

    $data = new \stdClass;

    $watching = $trakt->watching();
    if (!$watching) {
      // If user is not watching, get latest stuff he saw
      $watched = $trakt->watched();
      if ($watched) {
        $num_elements = count($watched);
        $chosen = rand(0, $num_elements - 1);
        $element = $watched[$chosen];
        $data = $element;
        $data->isWatching = false;
      }
    } else {
      $data = $watching;
      $data->isWatching = true;
    }
    $type = $data->type;
    $id = $data->{$type}->ids->tmdb;
    if ($type === 'episode') {
      $id = $data->show->ids->tmdb;
      $type = 'tv';
    }
    $moviedb = new TheMovieDB($id, $type);
    $image = $moviedb->poster();
    $data->poster = $image;

    Render::card("trakt/watch", $params, $data);
  }
}
