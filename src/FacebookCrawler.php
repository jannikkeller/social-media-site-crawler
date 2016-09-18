<?php
namespace FacebookCrawler\Main;

use Facebook\FacebookRequest;
use Facebook\FacebookApp;
use Facebook\Facebook;

class FacebookCrawler extends \SocialNetworkCrawler {

    const APP_ID = '';
    const APP_SECRET = '';
    const ACCESS_TOKEN = '';

    private $app;
    private $fb;

    public function __construct()
    {
        $this->app = new FacebookApp(
            self::APP_ID,
            self::APP_SECRET
        );

        $this->fb = new Facebook(array(
                'app_id' => self::APP_ID,
                'app_secret' => self::APP_SECRET)
        );
    }

    public function run() {

    }
}

$crawler = new FacebookCrawler();
$crawler->main();