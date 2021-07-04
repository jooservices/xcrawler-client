<?php

namespace Jooservices\XcrawlerClient\Response;

use Jooservices\XcrawlerClient\Interfaces\ResponseInterface;
use Jooservices\XcrawlerClient\Response\Traits\DefaultResponse;

class FlickrResponse implements ResponseInterface
{
    use DefaultResponse;

    public function loadData()
    {
        $this->data = json_decode($this->body, true);
        if (!$this->data) {
            $this->responseSuccess = false;
            $this->responseMessage = 'Unable to decode Flickr response';
            $this->data = null;
            return;
        }

        if ($this->data['stat'] === 'fail') {
            $this->responseSuccess = false;
            $this->responseMessage = 'Request failed';
            $this->data = null;
            return;
        }

        $this->data = $this->cleanTextNodes($this->data);
    }

    private function cleanTextNodes($arr)
    {
        if (!is_array($arr)) {
            return $arr;
        } elseif (count($arr) == 0) {
            return $arr;
        } elseif (count($arr) == 1 && array_key_exists('_content', $arr)) {
            return $arr['_content'];
        } else {
            foreach ($arr as $key => $element) {
                $arr[$key] = $this->cleanTextNodes($element);
            }
            return ($arr);
        }
    }
}
