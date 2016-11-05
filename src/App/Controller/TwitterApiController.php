<?php

namespace App\Controller;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use TwitterAPIExchange;

class TwitterApiController implements ControllerProviderInterface
{
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

    private function getHourCounts($twitterUsername, Application $app)
    {
        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $getfield = '?screen_name='.$twitterUsername.'&count=200';
        $requestMethod = 'GET';
    
        // Perform Twitter API call
        $twitter = new TwitterAPIExchange($app['config']['twitter']);
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
    public function getHistogramAction($twitterUsername, Application $app)
    {
        $data = $this->getHourCounts($twitterUsername, $app);

        // Create and return a JSON response
        return new JsonResponse($data);
    }

    /**
     *
     * @return Counts of tweets per hour in a day in a human readable format
     */
    public function getHistogramPrettyAction($twitterUsername, Application $app)
    {
        return '<pre>'.json_encode($this->getHourCounts($twitterUsername, $app), JSON_PRETTY_PRINT).'<pre>';
    }
}