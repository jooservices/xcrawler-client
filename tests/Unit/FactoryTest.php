<?php

namespace Jooservices\XcrawlerClient\Tests;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Jooservices\XcrawlerClient\Factory;
use Kevinrob\GuzzleCache\CacheEntry;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\FlysystemStorage;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;
use League\Flysystem\Adapter\Local;
use LogicException;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class FactoryTest extends TestCase
{
    /**
     * @var mixed|MockObject|LoggerInterface
     */
    private mixed $logger;
    private Factory $factory;

    public function setUp(): void
    {
        parent::setUp();

        $this->logger = $this->createMock(LoggerInterface::class);
        $this->factory = new Factory($this->logger, 200);
    }

    public function test_request_error()
    {
        $factory = new Factory($this->logger, $this->faker->numberBetween(400, 599));
        $client = $factory->make();
        $this->expectException(RequestException::class);
        $client->request('GET', $this->faker->url);
    }

    /**
     * @dataProvider data_provider_logging_level
     * @throws GuzzleException
     */
    public function test_with_logging(string $level)
    {
        $url = $this->faker->url;
        $client = $this->factory->enableLogging('log request: {method} {uri}', $level)->make();
        $this->logger->expects($this->once())
            ->method('log')
            ->with(constant('Psr\\Log\\LogLevel::' . strtoupper($level)), 'log request: GET ' . $url);
        $client->request('get', $url);
    }

    public function data_provider_logging_level()
    {
        return [
            [LogLevel::INFO,],
            [LogLevel::ALERT,],
            [LogLevel::CRITICAL,],
            [LogLevel::ERROR,],
            [LogLevel::WARNING,],
            [LogLevel::NOTICE,],
            [LogLevel::INFO,],
            [LogLevel::DEBUG,],
        ];
    }

    public function test_fake_response_code()
    {
        $client = $this->factory->addOptions(['base_uri', $this->faker->url])->make();
        $this->assertEquals(200, $client->request('get', 'path')->getStatusCode());
    }

    public function test_get_history()
    {
        $url = $this->faker->url;
        $client = $this->factory->make();
        $client->request('get', $url);

        $history = $this->factory->getHistory($client);

        $this->assertNotEmpty($history[0]);
        $request = $history[0]['request'];
        $response = $history[0]['response'];

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals($url, $request->getUri());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Fake test response for request: GET ' . $url, $response->getBody());
    }

    public function test_retries()
    {
        $client = $this->factory->enableRetries(2, 0.001, 200)->make();
        $client->request('get', $this->faker->url);
        $this->assertEquals(3, count($this->factory->getHistory($client)));
    }

    public function test_retries_with_higher_min_error_code()
    {
        $factory = new Factory($this->logger, 202);
        $client = $factory->enableRetries(2, 0.001, 200)->make();
        $client->request('get', $this->faker->url);
        $this->assertEquals(0, count($this->factory->getHistory($client)));
    }

    public function test_exception_with_no_logger_instance()
    {
        $factory = new Factory(null, 200);
        $this->expectException(LogicException::class);
        $factory->enableLogging();
    }

    public function test_with_cache()
    {
        $local = new FlysystemStorage(new Local(__DIR__ . '/cache'));
        $url = $this->faker->url;
        $cache = new CacheMiddleware(
            new PrivateCacheStrategy(
                $local
            )
        );

        $factory = new Factory($this->logger, 200);
        $client = $factory->enableCache($cache)->make();
        $client->request('GET', $url);

        $key = hash('sha256', 'GET' . $url);
        $this->assertInstanceOf(CacheEntry::class, $local->fetch($key));
    }
}