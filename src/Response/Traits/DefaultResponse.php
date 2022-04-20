<?php

namespace Jooservices\XcrawlerClient\Response\Traits;

use Jooservices\XcrawlerClient\Interfaces\ResponseInterface;

trait DefaultResponse
{
    public function reset()
    {
        $this->endpoint = null;
        $this->request = [];
        $this->headers = [];
        $this->body = null;
        $this->responseSuccess = true;
        $this->responseCode = null;
        $this->responseMessage = null;
    }

    /**
     * Check the status of the response
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->responseSuccess;
    }

    /**
     * Get the response message
     *
     * @return string|null
     */
    public function getResponseMessage(): ?string
    {
        return $this->responseMessage;
    }

    /**
     * Get response headers
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers ?? [];
    }

    /**
     * Get the endpoint
     *
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Get the request
     *
     * @return array
     */
    public function getRequest(): array
    {
        return $this->request;
    }

    /**
     * Set Error
     *
     * @param string $error
     *
     * @return $this
     */
    public function error(string $error = 'Error'): self
    {
        $this->responseSuccess = false;
        $this->responseMessage = $error;

        return $this;
    }

    /**
     * Set response headers
     *
     * @param array $headers
     *
     * @return $this
     */
    public function headers(array $headers = []): self
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Cast the object to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return json_decode(json_encode($this), true);
    }

    /**
     * Transform from other response
     *
     * @param ResponseInterface $response
     * @return $this
     */
    public function from(ResponseInterface $response): self
    {
        foreach (get_object_vars($response) as $property => $value) {
            $this->{$property} = $value;
        }

        return $this;
    }

    /**
     * Get the Data
     *
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data ?? null;
    }

    /**
     * Get the Body
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
}
