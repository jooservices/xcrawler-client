<?php

namespace Jooservices\XcrawlerClient\Tests\Unit\Response;

use Jooservices\XcrawlerClient\Response\FlickrResponse;
use Jooservices\XcrawlerClient\Tests\TestCase;

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
