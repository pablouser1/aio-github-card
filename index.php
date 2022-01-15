<?php
require './vendor/autoload.php';

use Wrappers\Trakt;
use Wrappers\TheMovieDB;
use Steampixel\Route;
use Helpers\Misc;
use Helpers\Modes;
use Helpers\Themes;

// LOAD DOTENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

Route::add('/', function () {
    $latte = Misc::latte();
    $latte->render(Misc::getTemplate('index'), ['themes' => array_keys(Themes::all), 'modes' => Modes::all]);
});

Route::add('/api', function () {
    // Requiered checks
    if (!isset($_GET['mode'])) {
        die('You need to send a mode!');
    }

    if (!isset($_GET['username'])) {
        die('You need to send a username!');
    }
    // Themes
    $theme = 'default';
    if (isset($_GET['theme']) && !empty($_GET['theme'])) {
        $theme = $_GET['theme'];
    }
    if (!in_array($theme, array_keys(Themes::all))) {
        return 'Invalid theme';
    }
    $isDarkTheme = Themes::all[$theme]['isDark'];
    $mode = $_GET['mode'];
    $username = $_GET['username'];
    $latte = Misc::latte();
    $trakt = new Trakt($username);
    header('Content-Type: image/svg+xml');
    header('Cache-Control: s-maxage=60');
    $params = [
        'username' => $username,
        'theme' => $theme,
        'isDarkTheme' => $isDarkTheme
    ];
    switch ($mode) {
        case 'stats':
            $stats = $trakt->stats();
            $params['data'] = $stats;
            break;
        case 'watch':
            $watching = $trakt->watching();
            if (!$watching) {
                // If user is not watching, get latest stuff he saw
                $watched = $trakt->watched();
                if ($watched) {
                    $num_elements = count($watched);
                    $chosen = rand(0, $num_elements - 1);
                    $element = $watched[$chosen];
                    $params['data'] = $element;
                    $params['isWatching'] = false;
                }
            } else {
                $params['data'] = $watching;
                $params['isWatching'] = true;
            }
            $type = $params['data']->type;
            $id = $params['data']->{$type}->ids->tmdb;
            if ($type === 'episode') {
                $id = $params['data']->show->ids->tmdb;
                $type = 'tv';
            }
            $moviedb = new TheMovieDB($id, $type);
            $image = $moviedb->poster();
            $params['data']->poster = $image;
            break;
        default:
            die('Invalid mode!');
    }
    $latte->render(Misc::getTemplate($mode), $params);
});

Route::run(Misc::getSubDir());
