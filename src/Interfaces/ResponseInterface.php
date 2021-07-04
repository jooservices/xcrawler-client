<?php

namespace Jooservices\XcrawlerClient\Interfaces;

/**
 * @property string $endpoint
 * @property array $request
 * @property array $headers
 * @property ?string $body
 * @property ?mixed $data
 * @property bool $responseSuccess
 * @property int $responseCode
 * @property string $responseMessage
 */
interface ResponseInterface
{
    public function reset();

    public function isSuccessful(): bool;

    public function getResponseMessage(): ?string;

    public function getHeaders(): array;

    /**
     * Request endpoint
     * @return string
     */
    public function getEndpoint(): string;

    /**
     * Payload
     * @return array
     */
    public function getRequest(): array;

    public function error(string $error = 'Error'): self;

    public function headers(array $headers = []): self;

    public function toArray(): array;

    public function loadData();

    /**
     * Parsed data from body
     * @return mixed
     */
    public function getData(): mixed;

    /**
     * Raw body
     * @return string
     */
    public function getBody(): string;
}
