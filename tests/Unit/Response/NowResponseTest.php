<?php

namespace Jooservices\XcrawlerClient\Tests\Unit\Response;

use Jooservices\XcrawlerClient\Response\NowResponse;
use Jooservices\XcrawlerClient\Tests\TestCase;

class NowResponseTest extends TestCase
{
    public function test_succeed()
    {
        $response = new NowResponse();
        $response->reset(200,[],json_encode(['result' => 'success']));

        $this->assertIsArray($response->getData());
    }

    public function test_failed()
    {
        $response = new NowResponse();
        $response->reset(200,[],json_encode(['result' => 'false']));

        $this->assertNull($response->getData());
    }
}
