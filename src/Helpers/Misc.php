<?php
namespace App\Helpers;

class Misc {
  static public function setupHeaders(): void {
    header('Content-Type: image/svg+xml');
    header('Cache-Control: s-maxage=1');
  }
}
