<?php

namespace Jooservices\XcrawlerClient\Tests\Unit\Response;

use Jooservices\XcrawlerClient\Response\JsonResponse;
use Jooservices\XcrawlerClient\Tests\TestCase;

class JsonResponseTest extends TestCase
{
    public function test_succeed()
    {
        $response = new JsonResponse();
        $response->body = json_encode(['stat' => 'ok']);
        $response->loadData();

        $this->assertIsArray($response->getData());
    }

    public function test_failed()
    {
        $response = new JsonResponse();
        $response->body = null;
        $response->loadData();

        $this->assertNull($response->getData());
    }
}
