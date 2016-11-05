<?php

namespace App\Controller;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use TwitterAPIExchange;

class TwitterApiController implements ControllerProviderInterface
{
    const YOUR_TWITTER_CONSUMER_KEY = 'yJaTc5Hdr1PWi8vH720fWYls7';
    const YOUR_TWITTER_CONSUMER_SECRET = 'sf37ZkToVRWXKMwnGZpi9gtFemrRtXu7xG3hUDwcbch6Pr4IDS';
    const YOUR_TWITTER_ACCESS_TOKEN= '16628367-xoVvxNnUTe7AMCl9xrqZZKxJg7W0bvp0qBeYlpGUt';
    const YOUR_TWITTER_ACCESS_TOKEN_SECRET = 'SjviGkF7CCukIaDoiTEALpJ3vyJ0E86zH0UpZKMW9i2e9';

    /**
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/{twitterUsername}','App\Controller\TwitterApiController::getHistogramAction');
        $controllers->get('/pretty/{twitterUsername}','App\Controller\TwitterApiController::getHistogramPrettyAction');

        return $controllers;
    }

    private function getTwitterSettings()
    {
        return array(
            'consumer_key' => self::YOUR_TWITTER_CONSUMER_KEY,
            'consumer_secret' => self::YOUR_TWITTER_CONSUMER_SECRET,
            'oauth_access_token' => self::YOUR_TWITTER_ACCESS_TOKEN,
            'oauth_access_token_secret' => self::YOUR_TWITTER_ACCESS_TOKEN_SECRET
        );
    }
    
    private function getHourCounts($twitterUsername, $pretty = false)
    {
        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $getfield = '?screen_name='.$twitterUsername.'&count=200';
        $requestMethod = 'GET';
    
        // Perform Twitter API call
        $twitter = new TwitterAPIExchange($this->getTwitterSettings());
        $tweets =  json_decode($twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest());
    
        // Initialize all day hours
        $hourCounts = array();
        for ($i=0;$i<=24;$i++) {
            $hourCounts[sprintf("%02d", $i).'h'] = 0;
        }
    
        // Counts per hour for the user's tweets
        foreach ($tweets as $tweet) {
            $tweetDate = \DateTime::createFromFormat('D M d H:i:s P Y', (string)$tweet->created_at);
            $hour = $tweetDate->format('H').'h';
            $hourCounts[$hour] ++;
        }
        
        return $hourCounts;
    }

    /**
     *
     * @return Counts of tweets per hour in a day in JSON format
     */
    public function getHistogramAction($twitterUsername)
    {
        $data = $this->getHourCounts($twitterUsername);

        // Create and return a JSON response
        return new JsonResponse($data);
    }

    /**
     *
     * @return Counts of tweets per hour in a day in a human readable format
     */
    public function getHistogramPrettyAction($twitterUsername)
    {
        return '<pre>'.json_encode($this->getHourCounts($twitterUsername), JSON_PRETTY_PRINT).'<pre>';
    }
}