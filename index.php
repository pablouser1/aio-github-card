<?php
require './vendor/autoload.php';
use Steampixel\Route;
use Helpers\Misc;
use Helpers\Api;
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

    $theme = 'default';
    if (isset($_GET['theme']) && !empty($_GET['theme'])) {
        $theme = $_GET['theme'];
    }
    $mode = $_GET['mode'];
    $username = $_GET['username'];
    $latte = Misc::latte();
    $api = new Api($username);
    header('Content-Type: image/svg+xml');
    header('Cache-Control: s-maxage=1');
    $data = [
        'username' => $username,
        'theme' => $theme
    ];
    switch ($mode) {
        case 'stats':
            $stats = $api->stats();
            $data['data'] = $stats;
            break;
        case 'watch':
            $watching = $api->watching();
            if (!$watching) {
                // If user is not watching, get latest stuff he saw
                $watched = $api->watched();
                if ($watched) {
                    $data['data'] = $watched[0];
                    $data['isWatching'] = false;
                }
            } else {
                $data['data'] = $watching;
                $data['isWatching'] = true;
            }
            break;
        default:
            die('Invalid mode!');
    }
    $latte->render(Misc::getTemplate($mode), $data);
});

Route::run(Misc::getSubDir());
