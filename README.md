# twitter-histogram

This application consist of theses endpoints:

* **/** -> will respond with "Try /hello/:name" as text
* **/hello/World** -> will respond with "Hello World" as text
* **/histogram/username** -> will show number of tweets per hour of the day for user named "username"

## Install and run app

**Requirements**

* php 5.4+
* composer


**First thing to do is to install the app depedencies using composer:**
`composer install`


The easiest way to test the app without having to do server configuration is to run the PHP built in server on your environment (available since php 5.4)
**To launch the app with our web directory as document root, go in the app directory and :**
`php -S localhost:8080 -t web web/index.php`



## Testing the app using Codeception

Codeception acceptance tests are configured to run and check on localhost:8080 as the app URL by default.
If you configured it differently you will need to edit the "url" value in the acceptance configuration file (tests/acceptance.suite.yml).


**To run tests :**
`php vendor/bin/codecept run`