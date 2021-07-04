<?php

namespace Jooservices\XcrawlerClient\Response;

class NowResponse extends JsonResponse
{
    public function loadData()
    {
        parent::loadData();
        if ($this->data['result'] !== 'success') {
            $this->responseSuccess = false;
            $this->data = null;

            return;
        }

        $this->data = $this->data['reply'] ?? [];
    }
}
