<?php
namespace App\Helpers;

use App\Constants;

class Themes {
  const THEMES_DIR = __DIR__ . '/../../themes';
  const LOGOS_DIR = __DIR__ . '/../../logos';

  const ALL = [
    "trakt" => [
      "default" => "trakt-black-text.svg",
      "dark" => "trakt-white-text.svg"
    ],
    "backloggd" => [
      "default" => "backloggd-light.png",
      "dark" => "backloggd-dark.png"
    ]
  ];

  private static function getFile(string $filename, string $basepath): string {
    return file_get_contents($basepath . '/' . basename($filename));
  }

  public static function getCSS(string $theme, string $service): string {
    if (in_array($theme, Constants::THEMES)) {
      $theme = self::getFile("$theme.css", self::THEMES_DIR);
      $common = self::getFile('common.css', self::THEMES_DIR);
      $service = self::getFile("$service.css", self::THEMES_DIR);
      return $theme . $common . $service;
    }
    return '';
  }

  public static function getLogo(string $service, string $theme): string {
    if (in_array($theme, Constants::THEMES)) {
      $logo = self::ALL[$service][$theme];
      return self::getFile($logo, self::LOGOS_DIR);
    }
    return '';
  }
}
