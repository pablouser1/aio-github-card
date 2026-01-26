<?php

namespace App\Helpers;

use App\Models\Params;
use App\Wrappers\Backloggd;
use App\Wrappers\Listenbrainz;
use App\Wrappers\Trakt;

class Render
{
    /**
     * Render template with Plates
     */
    public static function card(string $view, Params $params, object $data): void
    {
        $engine = new \League\Plates\Engine(__DIR__ . '/../../templates');
        $engine->registerFunction('toHours', function (int $minutes): float {
            return round($minutes / 60, 2);
        });
        $engine->registerFunction('getThemeCSS', [Themes::class, 'getCSS']);
        $engine->registerFunction('getLogo', function (string $service, string $theme): string {
            return base64_encode(Themes::getLogo($service, $theme));
        });

        // TRAKT
        $engine->registerFunction('trakt_show_url', [Trakt::class, 'show_url']);
        $engine->registerFunction('trakt_movie_url', [Trakt::class, 'movie_url']);
        // BACKLOGGD
        $engine->registerFunction('backloggd_url', function (string $path): string {
            return Backloggd::BASE_URL . $path;
        });
        // Listenbrainz
        $engine->registerFunction('listenbrainz_poster_url', [Listenbrainz::class, 'poster_url']);
        $template = $engine->make($view);
        echo $template->render([
            "params" => $params,
            "data" => $data
        ]);
    }

    public static function page(string $view, array $data = []): void
    {
        $engine = new \League\Plates\Engine(__DIR__ . '/../../templates');
        $template = $engine->make($view);
        echo $template->render($data);
    }
}
