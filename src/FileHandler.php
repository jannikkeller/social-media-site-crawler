<?php
namespace SocialNetworkCrawler;

class FileHandler {

    const FILE_OUTPUT = '../dist/output.csv';
    const FILE_INPUT = '../dist/input.csv';

    public $file;

    public function toCsv($page)
    {
        fputcsv($this->file, array(
            $page->id,
            $page->count,
            $page->content
        ));
    }

    public function createCsv()
    {
        $this->file = fopen(self::FILE_OUTPUT, 'w');
        fputcsv($this->file, array(
            'pageID',
            'count',
            'content'
        ));
    }

    public function readCsv()
    {
        $networkIdsString = file_get_contents(self::FILE_INPUT);
        return explode(',', $networkIdsString);
    }
}