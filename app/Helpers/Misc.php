<?php
namespace App\Helpers;

class Misc {
    static public function env(string $key, $default_value) {
        return isset($_ENV[$key]) && !empty($_ENV[$key]) ? $_ENV[$key] : $default_value;
    }

    /**
     * Render template with Plates
     */
    static public function plates(string $view, array $data = []): void {
        $engine = new \League\Plates\Engine(__DIR__ . '/../../templates');
        $engine->registerFunction('toHours', function (int $minutes): int {
            return round($minutes / 60, 2);
        });
        $engine->registerFunction('show_url', function (string $showId, string $seasonId = "", string $episodeId = ""): string {
            $url = 'https://trakt.tv/shows/' . $showId;
            if ($seasonId) {
                $url .= '/seasons/' . $seasonId;
                if ($episodeId) {
                    $url .= '/episodes/' . $episodeId;
                }
            }
            return $url;
        });
        $engine->registerFunction('movie_url', function (string $movieId): string {
            return 'https://trakt.tv/movies/' . $movieId;
        });
        $engine->registerFunction('getThemeCSS', function (string $theme_name): string {
            return Themes::getCSS($theme_name);
        });
        $engine->registerFunction('getLogo', function (string $themeName): string {
            return base64_encode(Themes::getLogo($themeName));
        });
        $template = $engine->make($view);
        echo $template->render($data);
    }
}
