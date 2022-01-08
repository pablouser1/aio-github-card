<?php
namespace Helpers;
class Misc {
    static public function getSubDir(): string {
        return isset($_ENV['APP_SUBDIR']) && !empty($_ENV['APP_SUBDIR']) ? $_ENV['APP_SUBDIR'] : '/';
    }

    static public function getTemplate(string $template): string {
        return __DIR__ . "/../templates/{$template}.latte";
    }

    static public function latte(): \Latte\Engine {
        // Workaround to avoid weird path issues
        $subdir = Misc::getSubDir();
        if ($subdir === '/') {
            $subdir = '';
        }
        $latte = new \Latte\Engine;
        $latte->setTempDirectory(__DIR__ . '/../cache/templates');
        $latte->addFunction('assets', function (string $name, string $type) use ($subdir) {
            $path = "{$subdir}/{$type}/{$name}";
            return $path;
        });
        $latte->addFunction('path', function (string $name) use ($subdir) {
            $path = "{$subdir}/{$name}";
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
