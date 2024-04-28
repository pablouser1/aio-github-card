<?php
namespace App\Models;
use App\Constants;

class Params {
  public string $username;
  public string $mode;
  public string $theme;
  public int $width;

  private string $error = "";

  public function __construct(string $mode, int $defaultWidth) {
    $this->mode = $mode;
    $this->width = $defaultWidth;
  }

  public function parse(): bool {
    if (!isset($_GET["username"])) {
      $this->error = "username";
      return false;
    }

    $username = $_GET["username"];

    $theme = Constants::DEFAULT_THEME;
    if (isset($_GET["theme"])) {
      $theme = $_GET["theme"];
    }

    if (isset($_GET["width"]) && is_numeric($_GET["width"])) {
      $this->width = intval($_GET["width"]);
    }

    $this->username = $username;
    $this->theme = $theme;
    return true;
  }

  public function getError(): string {
    return $this->error;
  }
}
