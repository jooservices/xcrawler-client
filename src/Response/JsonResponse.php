<?php

namespace Jooservices\XcrawlerClient\Response;

class JsonResponse extends AbstractBaseResponse
{
    public function loadData(): self
    {
        $this->data = json_decode($this->getBody(), true);

        return $this;
    }
}
