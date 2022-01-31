<?php
namespace App\Helpers;

class Misc {
    static private function env(string $key, mixed $default_value): string {
        return isset($_ENV[$key]) && !empty($_ENV[$key]) ? $_ENV[$key] : $default_value;
    }

    static public function getURL(): string {
        return self::env('APP_URL', '/');
    }

    static public function getTemplate(string $template): string {
        return __DIR__ . "/../../templates/{$template}.latte";
    }

    static public function latte(): \Latte\Engine {
        $url = self::getURL();
        $latte = new \Latte\Engine;
        $latte->addFunction('assets', function (string $name, string $type) use ($url) {
            $path = "{$url}/{$type}/{$name}";
            return $path;
        });
        $latte->addFunction('path', function (string $name) use ($url) {
            $path = "{$url}/{$name}";
            return $path;
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
