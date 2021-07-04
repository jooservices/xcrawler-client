<?php

namespace Jooservices\XcrawlerClient\Request;

use GuzzleHttp\RedirectMiddleware;

class Options
{
    public function __construct(public array $options = [])
    {
        $this->reset();
    }

    public function sink($toFile)
    {
        $this->options['sink'] = $toFile;

        return $this;
    }

    public function reset()
    {
        $this->options = [
            'allow_redirects' => RedirectMiddleware::$defaultSettings,
            'http_errors' => true,
            'decode_content' => true,
            'verify' => true,
            'cookies' => false,
            'idn_conversion' => false,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114  Safari/537.36'
            ]
        ];
    }
}
