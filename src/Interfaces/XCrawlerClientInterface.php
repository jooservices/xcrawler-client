<?php

namespace Jooservices\XcrawlerClient\Interfaces;

use Jooservices\XcrawlerClient\Settings\RequestOptions;
use Psr\Http\Message\ResponseInterface;

interface XCrawlerClientInterface
{
    public function init(ResponseInterface $response, array $options = [], RequestOptions $requestOptions = null): self;

    public function getResponse(): ResponseInterface;

    public function setHeaders(array $headers): self;

    public function setContentType(string $contentType = 'json'): self;

    public function get(string $endpoint, array $payload = []): ResponseInterface;

    public function post(string $endpoint, array $payload = []): ResponseInterface;

    public function put(string $endpoint, array $payload = []): ResponseInterface;

    public function patch(string $endpoint, array $payload = []): ResponseInterface;

    public function delete(string $endpoint, array $payload = []): ResponseInterface;
}
