<?php

require_once 'vendor/autoload.php';

// Twitter API configuration
$twitter_consumer_key = 'YOUR_TWITTER_CONSUMER_KEY';
$twitter_consumer_secret = 'YOUR_TWITTER_CONSUMER_SECRET';
$twitter_access_token= 'YOUR_TWITTER_ACCESS_TOKEN';
$twitter_access_secret = 'YOUR_TWITTER_ACCESS_TOKEN_SECRET';

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


// Twitter API keys and tokens used
$twitterSettings = array(
    'consumer_key' => $twitter_consumer_key,
    'consumer_secret' => $twitter_consumer_secret,
    'oauth_access_token' => $twitter_access_token,
    'oauth_access_token_secret' => $twitter_access_secret
);

// define route for /histogram/{twitterUsername}
$app->get('/histogram/{twitterUsername}', function ($twitterUsername) use ($twitterSettings) {

    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $getfield = '?screen_name='.$twitterUsername.'&count=200';
    $requestMethod = 'GET';

    // Perform Twitter API call
    $twitter = new TwitterAPIExchange($twitterSettings);
    $tweets =  json_decode($twitter->setGetfield($getfield)
        ->buildOauth($url, $requestMethod)
        ->performRequest());

    // Initialize all day hours
    $response = array();
    for ($i=0;$i<=24;$i++) {
        $response[sprintf("%02d", $i).'h'] = 0;
    }

    // Counts per hour for the user's tweets
    foreach ($tweets as $tweet) {
        $tweetDate = DateTime::createFromFormat('D M d H:i:s P Y', (string)$tweet->created_at);
        $hour = $tweetDate->format('H').'h';
        $response[$hour] ++;
    }

    return json_encode($response);
});

$app->run();