<?php

namespace App;

abstract class Constants
{
    public const SERVICES = ["trakt", "backloggd", "listenbrainz"];
    public const THEMES = ["default", "dark"];
    public const DEFAULT_THEME = self::THEMES[0];
    public const DEFAULT_WIDTH = 300;
}
