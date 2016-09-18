<?php
namespace SocialNetworkCrawler;

require_once 'Month.php';

class Page {

    public $id;
    public $networkId;
    public $networkName;
    public $months;
    public $content;

    public function __construct()
    {
        //$this->months = $this->getMonths();
    }

    private function getMonths()
    {
        $months = [];

        for($i=1; $i<=12; $i++) {

            $firstDay = sprintf('2015-%s-01', $i);
            $numberOfDays = date('t', strtotime($firstDay));

            $month = new Month();
            $month->firstDay = $firstDay;
            $month->lastDay = sprintf('2015-%s-%s', $i, $numberOfDays);
            $month->name = date('F', strtotime($firstDay));
            $month->content = '';

            $months[] = $month;
        }

        return $months;
    }
}