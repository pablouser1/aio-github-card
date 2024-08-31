<?php
namespace App\Models;

class Game {
  public string $name;
  public string $image;
  public string $path;
  public int $rating = -1;

  public function __construct(string $name, string $image, string $path, int $rating = -1) {
    $this->name = $name;
    $this->image = $image;
    $this->path = $path;
    $this->rating = $rating;
  }
}
