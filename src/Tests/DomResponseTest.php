<?php

namespace Jooservices\XcrawlerClient\Tests;

use Jooservices\XcrawlerClient\Response\DomResponse;
use Jooservices\XcrawlerClient\XCrawlerClient;
use Symfony\Component\DomCrawler\Crawler;

class DomResponseTest extends TestCase
{
    public function test_get_dom_crawler()
    {
        $client = new XCrawlerClient();
        $client->init(
            new DomResponse(),
            [
                'isFake' => 200
            ],
            [
                'allow_redirects' =>
                    [
                        'max' => 3
                    ]
            ]
        );
        $response = $client->get($this->faker->url, ['q' => 1]);
        $this->assertTrue($response->isSuccessful());
        $this->assertInstanceOf(Crawler::class, $response->getData());
    }

    public function test_get_dom_crawler_failed()
    {
        $client = new XCrawlerClient();
        $client->init(
            new DomResponse(),
            [
                'isFake' => 500
            ],
            [
                'allow_redirects' =>
                    [
                        'max' => 3
                    ]
            ]
        );
        $response = $client->get($this->faker->url, ['q' => 1]);
        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getData());
    }
}
