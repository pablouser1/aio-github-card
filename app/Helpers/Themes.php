<?php
namespace App\Helpers;

class Themes {
    const THEMES_DIR = __DIR__ . '/../../themes';
    const LOGOS_DIR = __DIR__ . '/../../svg';

    const all = [
        'default' => [
            'isDark' => false,
            'logo' => 'trakt-wide-red-black.svg'
        ],
        'dark' => [
            'isDark' => true,
            'logo' => 'trakt-wide-red-white.svg'
        ]
    ];

    static private function getFile(string $filename, string $basepath): string {
        return file_get_contents($basepath . '/' . basename($filename));
    }

    static public function getCSS(string $theme): string {
        $keys = array_keys(self::all);
        if (in_array($theme, $keys)) {
            $theme = self::getFile($theme . '.css', self::THEMES_DIR);
            $common = self::getFile('common.css', self::THEMES_DIR);
            return $theme . $common;
        }
        return '';
    }

    static public function getLogo(string $theme): string {
        $keys = array_keys(self::all);
        if (in_array($theme, $keys)) {
            $svg = self::all[$theme]['isDark'] ? 'trakt-wide-red-white.svg' : 'trakt-wide-red-black.svg';
            return self::getFile($svg, self::LOGOS_DIR);
        }
        return '';
    }
}
