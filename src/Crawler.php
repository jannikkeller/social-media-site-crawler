<?php
namespace FacebookCrawler\Main;

use Facebook\FacebookRequest;
use Facebook\FacebookApp;
use Facebook\Facebook;

require_once '../vendor/autoload.php';

class Crawler
{

    private $app;
    private $fb;
    private $file;

    private $pageId;
    private $month;
    private $firstDay;
    private $lastDay;
    private $count;
    private $content;
    

    const APP_ID = '1768633400074543';
    const APP_SECRET = 'fb48b763427ed9a40179d062c8d5dec2';
    const ACCESS_TOKEN = 'EAAZAIkA2MoS8BAJ2r2GhrkamFaWXfaD3B7woK77XcNvBgbuMcRiZCn6oq4H46b4sHZBDuIJSvtLRFNn4ZAGvxbqeTrVknAQ6A8vZB2mUfS7XZCPSgMj3MtEkUWucobSbTZBO7WT0gC2NLBRWiJ3MLmsZAuW9OZBZAjtBQRQW9Fq6uO9AZDZD';
    const FILE_OUTPUT = 'output.csv';
    const FILE_INPUT = 'input.csv';

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

    public function main()
    {

        $this->createCsv();

        foreach($this->getPageIds() as $this->pageId) {
            $this->performRequestForEachMonth();
        }
    }

    private function getPageIds()
    {
        $pageIdsString = file_get_contents(self::FILE_INPUT);
        $pageIds = explode(',', $pageIdsString);

        return $pageIds;
    }

    private function performRequestForEachMonth()
    {
        for($i=1; $i<=12; $i++) {

            $this->firstDay = sprintf('2015-%s-01', $i);
            $numberOfDays = date('t', strtotime($this->firstDay));
            $this->lastDay = sprintf('2015-%s-%s', $i, $numberOfDays);
            $this->month = date('F', strtotime($this->firstDay));
            $this->content = '';

            $this->performRequest();
        }
    }

    private function performRequest()
    {
        $request = new FacebookRequest(
            $this->app,
            self::ACCESS_TOKEN,
            'GET',
            sprintf('/%s/posts', $this->pageId),
            array(
                'limit' => '100',
                'since' => $this->firstDay,
                'until' => $this->lastDay
            )
        );

        $this->handleRequest($request);
    }

    private function handleRequest($request)
    {

        $response = $this->fb->getClient()->sendRequest($request);
        $structuredResponse = $response->getDecodedBody()['data'];

        foreach($structuredResponse as $sR) {

            if(empty($sR['message'])) {
                continue;
            }

            $cleanMessage = preg_replace( "/\r|\n/", "", $sR['message']);
            $cleanMessage = str_replace(',', '', $cleanMessage);
            $this->content .= $cleanMessage . ' ### ';
        }

        $this->count = count($structuredResponse);
        echo $this->toString();
        $this->toCsv();
    }

    private function toString()
    {
        return $this->pageId . ' | ' . $this->firstDay . ' - ' . $this->lastDay . ' | ' . $this->count . PHP_EOL;
    }

    private function toCsv()
    {
        fputcsv($this->file, array(
            $this->pageId,
            $this->month,
            $this->firstDay,
            $this->lastDay,
            $this->count,
            $this->content
        ));
    }

    private function createCsv()
    {
        $this->file = fopen(self::FILE_OUTPUT, 'w');
        fputcsv($this->file, array(
            'pageID',
            'month',
            'firstDay',
            'lastDay',
            'count',
            'content'
        ));
    }
}

$crawler = new Crawler();
$crawler->main();