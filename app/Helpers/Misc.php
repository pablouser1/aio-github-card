<?php
namespace App\Helpers;

class Misc {
    static public function env(string $key, $default_value) {
        return isset($_ENV[$key]) && !empty($_ENV[$key]) ? $_ENV[$key] : $default_value;
    }

    static public function getTemplate(string $template): string {
        return __DIR__ . "/../../templates/{$template}.latte";
    }

    static public function latte(): \Latte\Engine {
        $latte = new \Latte\Engine;
        $latte->addFunction('local', function (string $path) {
            $local = __DIR__ . '/../..' . $path;
            return $local;
        });
        $latte->addFunction('toHours', function (int $minutes): int {
            return round($minutes / 60, 2);
        });
        $latte->addFunction('isDarkTheme', function (string $theme_name): bool {
            return Themes::all[$theme_name]['isDark'];
        });
        $latte->addFunction('getThemeCSS', function (string $theme_name): string {
            $theme = Themes::getCSS($theme_name);
            return $theme;
        });
        return $latte;
    }
}
