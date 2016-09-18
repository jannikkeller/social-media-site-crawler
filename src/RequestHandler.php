<?php

class Requesthandler {

    private function send()
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
}