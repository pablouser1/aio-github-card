<?php

namespace App\Controllers;

use App\Constants;
use App\Helpers\Cache;
use App\Helpers\Errors;
use App\Helpers\Misc;
use App\Helpers\Render;
use App\Models\Params;
use App\Wrappers\Listenbrainz;

class ListenbrainzController
{
    private const WIDTH_LISTENED = 380;

    private const MODES = ["listened"];

    public static function index()
    {
        Render::page("service", [
            "themes" => Constants::THEMES,
            "modes" => self::MODES
        ]);
    }

    public static function listened()
    {
        $params = new Params("listened", self::WIDTH_LISTENED);
        $ok = $params->parse();

        if (!$ok) {
            Errors::show("Could not parse GET data! '{$params->getError()}' is invalid");
            return;
        }

        Misc::setupHeaders();

        $engine = Cache::getEngine();

        $brainz = new Listenbrainz($params->username, $engine);

        $data = $brainz->listened();
        if ($data === null) {
            Errors::show("Could not get data from Listenbrainz!");
            return;
        }

        $index = array_rand($data);
        $song = $data[$index];

        Render::card("listenbrainz/listened", $params, $song);
    }
}
