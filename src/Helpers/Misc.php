<?php
namespace App\Helpers;

class Misc {
  static public function env(string $key, $default_value) {
    return isset($_ENV[$key]) && !empty($_ENV[$key]) ? $_ENV[$key] : $default_value;
  }

  static public function setupHeaders(): void {
    header('Content-Type: image/svg+xml');
    header('Cache-Control: s-maxage=1');
  }
}
