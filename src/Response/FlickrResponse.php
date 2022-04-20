<?php

namespace Jooservices\XcrawlerClient\Response;

class FlickrResponse extends AbstractBaseResponse
{
    public function loadData(): self
    {
        $this->data = json_decode($this->getBody(), true);
        if (!$this->data) {
            $this->isSucceed = false;
            $this->data = null;
            return $this;
        }

        if ($this->data['stat'] === 'fail') {
            $this->isSucceed = false;
            $this->data = null;
            return $this;
        }

        $this->data = $this->cleanTextNodes($this->data);

        return $this;
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
