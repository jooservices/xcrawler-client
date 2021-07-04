<?php

namespace Jooservices\XcrawlerClient\Response;

use Jooservices\XcrawlerClient\Interfaces\ResponseInterface;
use Jooservices\XcrawlerClient\Response\Traits\DefaultResponse;
use Symfony\Component\DomCrawler\Crawler;

class DomResponse implements ResponseInterface
{
    use DefaultResponse;

    public function loadData()
    {
        $this->data = new Crawler($this->body);
    }
}
