<?php

require_once 'vendor/autoload.php';

use Silex\Application;
use App\Controller\TwitterApiController;

// init Silex app
$app = new Application();

// Mount Twitter API controller provider
$app->mount('/histogram', new TwitterApiController());

// default route
$app->get('/', function () {
    return "Try /hello/:name";
});

// define route for /hello/{text}
$app->get('/hello/{text}', function ($text) {
    return "Hello $text";
});

$app->run();