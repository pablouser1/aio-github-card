<?php
namespace App\Helpers;

class Env {
  public static function parse(string $path): void {
    $arr = parse_ini_file($path);

    if ($arr === false) {
      return;
    }

    foreach ($arr as $key => $val) {
      putenv("$key=$val");
      $_ENV[$key] = $val;
    }
  }

  public static function trakt_client_id(): string {
    return $_ENV["TRAKT_CLIENT_ID"] ?? '';
  }

  public static function tmdb_token(): string {
    return $_ENV["TMDB_TOKEN"] ?? '';
  }

  public static function wrapper_cache(): string {
    return $_ENV['WRAPPER_CACHE'] ?? '';
  }

  public static function redis(): array {
    $host = $_ENV['REDIS_HOST'] ?? 'localhost';
    $port = isset($_ENV['REDIS_PORT']) ? intval($_ENV['REDIS_PORT']) : 6379;
    $password = $_ENV['REDIS_PASSWORD'] ?? null;

    return [
      "host" => $host,
      "port" => $port,
      "password" => $password
    ];
  }
}
