<?php

namespace Jooservices\XcrawlerClient\Tests;

use Jooservices\XcrawlerClient\Response\NowResponse;

class NowResponseTest extends TestCase
{
    public function test_succeed()
    {
        $response = new NowResponse();
        $response->body = json_encode(['result' => 'success']);
        $response->loadData();

        $this->assertIsArray($response->getData());
    }

    public function test_failed()
    {
        $response = new NowResponse();
        $response->body = json_encode(['result' => 'false']);
        $response->loadData();

        $this->assertNull($response->getData());
    }
}
