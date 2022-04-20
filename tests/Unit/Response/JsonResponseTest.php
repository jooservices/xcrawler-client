<?php

namespace Jooservices\XcrawlerClient\Tests\Unit\Response;

use Jooservices\XcrawlerClient\Response\JsonResponse;
use Jooservices\XcrawlerClient\Tests\TestCase;

class JsonResponseTest extends TestCase
{
    public function test_succeed()
    {
        $response = new JsonResponse();
        $response->reset(200,[],json_encode(['stat' => 'ok']));

        $this->assertIsArray($response->getData());
    }

    public function test_failed()
    {
        $response = new JsonResponse();

        $this->assertNull($response->getData());
    }
}
