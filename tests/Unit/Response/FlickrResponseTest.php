<?php

namespace Jooservices\XcrawlerClient\Tests\Unit\Response;

use Jooservices\XcrawlerClient\Response\FlickrResponse;
use Jooservices\XcrawlerClient\Tests\TestCase;

class FlickrResponseTest extends TestCase
{
    public function test_succeed()
    {
        $response = new FlickrResponse();
        $response->reset(200,[],json_encode(['stat' => 'ok']));

        $this->assertIsArray($response->getData());
    }

    public function test_failed()
    {
        $response = new FlickrResponse();
        $response->reset(200,[],json_encode(['stat' => 'fail']));

        $this->assertNull($response->getData());
    }
}
