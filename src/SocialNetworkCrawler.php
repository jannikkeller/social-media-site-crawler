<?php
require_once '../vendor/autoload.php';

class SocialNetworkCrawler
{

    public $pageId;
    public $month;
    public $firstDay;
    public $lastDay;
    public $count;
    public $content;

    public $file;

    public function main()
    {

        $this->createCsv();

        foreach($this->getPageIds() as $this->pageId) {
            $this->performRequestForEachMonth();
        }
    }


}