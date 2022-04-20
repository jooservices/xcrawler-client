<?php

namespace Jooservices\XcrawlerClient\Response;

use Jooservices\XcrawlerClient\Interfaces\ResponseInterface;
use Jooservices\XcrawlerClient\Response\Traits\DefaultResponse;

class JsonResponse implements ResponseInterface
{
    use DefaultResponse;

    public function loadData()
    {
        $this->data = json_decode($this->body, true);
    }
}
