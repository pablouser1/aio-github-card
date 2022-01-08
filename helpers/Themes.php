<?php
namespace Helpers;
class Themes {
    const BASE_DIR = __DIR__ . '/../themes';
    const all = [
        'default' => [
            'isDark' => false
        ],
        'dark'=> [
            'isDark' => true
        ]
    ];

    static private function getFile(string $filename): string {
        return file_get_contents(self::BASE_DIR . '/' . $filename);
    }

    static public function getCSS(string $theme): string {
        $keys = array_keys(self::all);
        if (in_array($theme, $keys)) {
            $theme = self::getFile($theme . '.css');
            $common = self::getFile('common.css');
            return $theme . $common;
        }
        return '';
    }
}
