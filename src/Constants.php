<?php
namespace App;

abstract class Constants {
  const SERVICES = ["trakt", "backloggd"];
  const THEMES = ["default", "dark"];
  const DEFAULT_THEME = self::THEMES[0];
  const DEFAULT_WIDTH = 300;
}
