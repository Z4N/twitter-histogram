<?php

require_once 'vendor/autoload.php';

// init Silex app
$app = new Silex\Application();

// default route
$app->get('/', function () {
    return "Try /hello/:name";
});

// define route for /hello/{text}
$app->get('/hello/{text}', function ($text) {
    return "Hello $text";
});

// define route for /histogram/{twitterUsername}
$app->get('/histogram/{twitterUsername}', function ($twitterUsername) {
    return "Should return Twitter hour stats for user with screen name $twitterUsername";
});

$app->run();