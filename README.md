# twitter-histogram

This application consist of theses endpoints:

* **/** -> will respond with "Try /hello/:name" as text
* **/hello/World** -> will respond with "Hello World" as text
* **/histogram/username** -> will show number of tweets per hour of the day for user named "username"
* **/histogram/pretty/username** -> same return as /histogram but in html readable view

## Install and run app

**Requirements**

* php 5.4+
* composer


**Install the app depedencies using composer**  
`composer install`

**Configure Twitter API settings**  
In order to use the twitter histogram call you need to enter valid Twitter API credentials.
If you don't already have a Twitter account, you can sign up for one at https://twitter.com/.
You'll need to create a Twitter application for accessing tweets, you can create one at https://dev.twitter.com/apps.
It only needs to be enabled for read access to Twitter data.

You will need to generate Keys and Access tokens in order to replace the values from those lines in **settings.yml** file :
```
twitter:
    consumer_key: YOUR_TWITTER_CONSUMER_KEY
    consumer_secret: YOUR_TWITTER_CONSUMER_SECRET
    oauth_access_token: YOUR_TWITTER_ACCESS_TOKEN
    oauth_access_token_secret: YOUR_TWITTER_ACCESS_TOKEN_SECRET
```


The easiest way to test the app without having to do server configuration is to run the PHP built in server on your environment (available since php 5.4)

**Launch the app with web directory as document root**  
From the app root directory execute this command:  
`php -S localhost:8080 -t web web/index.php`



## Testing the app using Codeception

Codeception acceptance tests are configured to run and check on localhost:8080 as the app URL by default.
If you configured it differently you will need to edit the "url" value in the acceptance configuration file (tests/acceptance.suite.yml).


**To run tests :**  
`php vendor/bin/codecept run`