<?php

class ConsoleHandler
{
    public function toString($page)
    {
        return $page->id . ' | ' . $page->firstDay . ' - ' . $page->lastDay . ' | ' . $page->count . PHP_EOL;
    }
}