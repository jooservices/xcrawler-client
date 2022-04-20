<?php

namespace Jooservices\XcrawlerClient\Response;

class NowResponse extends JsonResponse
{
    public function loadData(): self
    {
        parent::loadData();

        if ($this->data['result'] !== 'success') {
            $this->isSucceed = false;
            $this->data = null;

            return $this;
        }

        $this->data = $this->data['reply'] ?? [];

        return $this;
    }
}
