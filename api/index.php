<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Wrappers\Trakt;
use App\Wrappers\TheMovieDB;
use App\Helpers\Misc;
use App\Helpers\Modes;
use App\Helpers\Themes;

// LOAD DOTENV
$dotenv = new josegonzalez\Dotenv\Loader(__DIR__ . '/../.env');
$dotenv->raiseExceptions(false);
$result = $dotenv->parse();
if ($result !== false) {
    $dotenv->toEnv();
}

$router = new \Bramus\Router\Router();

$router->get('/', function () {
    Misc::plates('home', [
        'themes' => array_keys(Themes::all),
        'modes' => Modes::all
    ]);
});

$router->get('/card', function () {
    // Requiered checks

    // Modes
    $mode = 'stats';
    if (isset($_GET['mode']) && !empty($_GET['mode'])) {
        $mode = trim($_GET['mode']);
    }
    if (!in_array($mode, Modes::all)) {
        die('Invalid mode');
    }

    // Themes
    $theme = 'default';
    if (isset($_GET['theme']) && !empty($_GET['theme'])) {
        $theme = trim($_GET['theme']);
    }
    if (!in_array($theme, array_keys(Themes::all))) {
        die('Invalid theme');
    }

    // Width
    if (isset($_GET['width']) && is_numeric($_GET['width'])) {
        $width = intval($_GET['width']);
    }

    // -- Starting --
    $isDarkTheme = Themes::all[$theme]['isDark'];
    $username = trim($_GET['username']);
    $trakt = new Trakt($username);
    header('Content-Type: image/svg+xml');
    header('Cache-Control: s-maxage=1');
    $params = [
        'username' => $username,
        'theme' => $theme,
        'width' =>  isset($width) ? $width : [
            'stats' => 300,
            'watch' => 380
        ][$mode],
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
    }
    Misc::plates($mode, $params);
});

$router->run();
