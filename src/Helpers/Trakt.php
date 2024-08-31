<?php
namespace App\Helpers;

class Trakt {
  public static function show_url(string $showId, string $seasonId = "", string $episodeId = ""): string {
    $url = 'https://trakt.tv/shows/' . $showId;
    if ($seasonId) {
      $url .= '/seasons/' . $seasonId;
      if ($episodeId) {
        $url .= '/episodes/' . $episodeId;
      }
    }
    return $url;
  }

  public static function movie_url(string $movieId): string {
    return 'https://trakt.tv/movies/' . $movieId;
  }
}
