<?php
namespace App\Helpers;

class Errors {
  public static function show(string $message, int $code = 400) {
    http_response_code($code);
    echo $message;
  }
}
