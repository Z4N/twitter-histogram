<?php

require_once 'vendor/autoload.php';

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use App\Controller\TwitterApiController;
use Rpodwika\Silex\YamlConfigServiceProvider;

// init Silex app
$app = new Application();

// Get settings
$app->register(new YamlConfigServiceProvider("settings.yml"));

// Set twig views
$app->register(new TwigServiceProvider(), array(
    'twig.path' => array(
        __DIR__ . '/../src/App/views'
    ),
));

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