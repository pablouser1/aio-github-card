<?php
namespace App\Controllers;
use App\Constants;
use App\Helpers\Errors;
use App\Helpers\Misc;
use App\Helpers\Render;
use App\Models\Params;
use App\Wrappers\Backloggd;

class BackloggdController {
  private const WIDTH_WATCH = 380;

  private const MODES = ["played"];

  public static function index() {
    Render::page("service", [
      "themes" => Constants::THEMES,
      "modes" => self::MODES
    ]);
  }

  public static function played() {
    $params = new Params("played", self::WIDTH_WATCH);
    $ok = $params->parse();

    if (!$ok) {
      Errors::show("Could not parse GET data! '{$params->getError()}' is invalid");
      return;
    }

    Misc::setupHeaders();

    $bkl = new Backloggd($params->username);

    $data = $bkl->played();

    if ($data === null) {
      Errors::show("Could not get data from Backloggd!");
      return;
    }

    Render::card("backloggd/played", $params, $data);
  }
}
