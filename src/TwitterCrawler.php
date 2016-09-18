<?php
namespace SocialNetworkCrawler;

require_once '../vendor/autoload.php';

use Simplon\Twitter\Twitter;
require_once 'FileHandler.php';
require_once 'Page.php';

class TwitterCrawler
{

    const API_KEY = 'CwhHBkzItWEY7572GObyUSu0u';
    const API_SECRET = 'SpFWOFtyzSyOYCs2MFBMHfdRJGGu0Q5O0Cf11QJT2fLc0zZ94T';
    const ACCESS_TOKEN = '624065056-TuQ6fLL0Kn58MfUBS7UTWEgBaIzX5AMwGvqE0lpL';
    const ACCESS_SECRET = 'DR2TZ1KNAsMNS1ZP8ybbP3aBk7933uR5KIk6v3XDF0hb3';

    public $twitter;
    public $stopRequests = false;
    public $maxId = null;
    public $statuses;
    public $requestCount = 0;

    public function __construct()
    {
        $this->twitter = new Twitter(self::API_KEY, self::API_SECRET);
    }

    public function run()
    {
        $fileHandler = new FileHandler();
        $fileHandler->createCsv();

        // Create pages
        foreach($fileHandler->readCsv() as $networkId) {
            $page = new Page();
            $page->networkId = $networkId;
            $page->id = $networkId;

            while(!$this->stopRequests) {
                $this->getPosts($page);
            }

            $page->count = sizeof($this->statuses);
            /*foreach($this->statuses as $status) {
                $page->content .= $status['text'] . ' ### ';
            }*/

            $fileHandler->toCsv($page);
            $this->stopRequests = false;
            $this->maxId = null;
            $this->requestCount = 0;
            $this->statuses = [];
        }



        print_r($this->statuses);

        //$consoleHandler = new ConsoleHandler();
        //$consoleHandler->toString($page);
    }

    public function authorize()
    {
        $this->twitter->setOauthTokens(self::ACCESS_TOKEN, self::ACCESS_SECRET);
    }

    public function getPosts(&$page)
    {

        $getConfig = array(
            'user_id' => $page->networkId,
            'trim_user' => true,
            'exclude_replies' => true,
            'count' => 200
        );

        if(!empty($this->maxId)) {
            //$getConfig['max_id'] = (string) floatval((string) $this->maxId);
            $getConfig['max_id'] = number_format($this->maxId, 0, '.', '');
        }

        $this->requestCount++;

        /*if($this->requestCount > 100) {
            print_r('Sleeping 15 minutes...');
            sleep(15 * 60);
            print_r('Continuing...' . PHP_EOL);
        }*/

        print_r($getConfig);

        $rawStatuses = $this->twitter->get('statuses/user_timeline', $getConfig);

        if(empty($rawStatuses) || sizeof($rawStatuses) == 1) {
            $this->stopRequests = true;
            return;
        }

        foreach($rawStatuses as $rawStatus) {

            $this->maxId = $rawStatus['id'];
           // print_r($this->maxId . PHP_EOL);

            if(strtotime($rawStatus['created_at']) > strtotime('2015-12-31')) {
                print_r('Too new. Continuing...' . PHP_EOL);
                continue;
            }

            if(strtotime($rawStatus['created_at']) < strtotime('2015-01-01')) {
                print_r('Too old. Stopping...' . PHP_EOL);
                $this->stopRequests = true;
                break;
            }

            $this->statuses[] = array(
                'id' => $rawStatus['id'],
                'text' => $rawStatus['text'],
                'date' => $rawStatus['created_at']
            );
        }
    }
}

$crawler = new TwitterCrawler();
$crawler->run();