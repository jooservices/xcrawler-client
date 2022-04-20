<?php

namespace Jooservices\XcrawlerClient\Response;

use Symfony\Component\DomCrawler\Crawler;

class DomResponse extends AbstractBaseResponse
{
    public function loadData(): self
    {
        $this->data = new Crawler((string) $this->getBody());

        return $this;
    }
}
