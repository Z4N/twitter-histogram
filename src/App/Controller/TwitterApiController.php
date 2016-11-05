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

        $controllers->get('/{twitterUsername}.json','App\Controller\TwitterApiController::getHistogramJsonAction');
        $controllers->get('/{twitterUsername}.formatted','App\Controller\TwitterApiController::getHistogramFormattedAction');
        $controllers->get('/{twitterUsername}','App\Controller\TwitterApiController::getHistogramAction');

        return $controllers;
    }

    /**
     * Counts tweets per hour in a day in JSON format
     *
     * @return JsonResponse
     */
    public function getHistogramJsonAction($twitterUsername, Application $app)
    {
        $data = $this->getHourCounts($twitterUsername, $app);

        return new JsonResponse($data);
    }

    /**
     * Counts tweets per hour in a day in a human readable format
     *
     * @return string
     */
    public function getHistogramFormattedAction($twitterUsername, Application $app)
    {
        return '<pre>'.json_encode($this->getHourCounts($twitterUsername, $app), JSON_PRETTY_PRINT).'<pre>';
    }

    /**
     * Counts tweets per hour in a day in a human readable format
     *
     * @return string
     */
    public function getHistogramAction($twitterUsername, Application $app)
    {
        $data = $this->getHourCounts($twitterUsername, $app);

        return $app['twig']->render('histogram.html.twig', array(
            'data' => $data,
            'is_average' => $app['config']['twitter']['average_count'])
        );
    }

    /**
     * Counts number of tweets per hour in the day
     *
     * @return array
     */
    private function getHourCounts($twitterUsername, Application $app)
    {
        $tweets = array();
        $tweets_limit = $app['config']['twitter']['tweets_limit'];
        $tweets_limit = $tweets_limit > 3200 ? 3200 : ($tweets_limit < 201 ? 201 : $tweets_limit);

        $max_id = null;
        for ($count = 200; $count < $tweets_limit; $count += 200) {
            if (null !== $max_id && $max_id == '') {
                break;
            }

            $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
            $getfield = '?screen_name='.$twitterUsername.'&count='.$count;
            $getfield = is_null($max_id) ? $getfield : $getfield.'&max_id='.$max_id;
            $requestMethod = 'GET';

            // Perform Twitter API call
            $twitter = new TwitterAPIExchange($app['config']['twitter']['keys']);
            $response = json_decode($twitter->setGetfield($getfield)
                ->buildOauth($url, $requestMethod)
                ->performRequest());


            $tweets = array_merge($tweets, $response);

            // Get the last index of $response array
            $max_id = $response[count($response) - 1]->id_str;
        }

        // Initialize all day hours
        $hourCounts = array();
        for ($i=0;$i<=24;$i++) {
            $hourCounts[sprintf("%02d", $i).'h'] = 0;
        }
    
        // Counts per hour for the user's tweets
        if($app['config']['twitter']['average_count'])
        {
            $dayCounts = array();
            foreach ($tweets as $tweet) {
                $tweetDate = \DateTime::createFromFormat('D M d H:i:s P Y', (string)$tweet->created_at);
                $hour = $tweetDate->format('H').'h';
                $date = $tweetDate->format('dmy');

                $dayCounts[$date] = isset($dayCounts[$date]) ? $dayCounts[$date] + 1 : 1;
                $hourCounts[$hour] ++;
            }
            $nbDays = count($dayCounts);
            foreach ($hourCounts as $hour => $count) {
                $hourCounts[$hour] = round($count/$nbDays, 2);
            }
        }
        else
        {
            foreach ($tweets as $tweet) {
                $tweetDate = \DateTime::createFromFormat('D M d H:i:s P Y', (string)$tweet->created_at);
                $hour = $tweetDate->format('H').'h';
                $hourCounts[$hour] ++;
            }
        }

        return $hourCounts;
    }
}