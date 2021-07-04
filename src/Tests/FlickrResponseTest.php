<?php

namespace Jooservices\XcrawlerClient\Tests;

use Jooservices\XcrawlerClient\Response\FlickrResponse;

class FlickrResponseTest extends TestCase
{
    public function test_succeed()
    {
        $response = new FlickrResponse();
        $response->body = json_encode(['stat' => 'ok']);
        $response->loadData();

        $this->assertIsArray($response->getData());
    }

    public function test_failed()
    {
        $response = new FlickrResponse();
        $response->body = json_encode(['stat' => 'fail']);
        $response->loadData();

        $this->assertNull($response->getData());
    }
}
