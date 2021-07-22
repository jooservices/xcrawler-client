<?php

namespace Jooservices\XcrawlerClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Jooservices\XcrawlerClient\Interfaces\ResponseInterface;
use Jooservices\XcrawlerClient\Interfaces\SettingsContainerInterface;
use Jooservices\XcrawlerClient\Interfaces\XCrawlerClientInterface;
use Jooservices\XcrawlerClient\Settings\RequestOptions;

/**
 * Wrapped factory to make request with customized options
 */
class XCrawlerClient implements XCrawlerClientInterface
{
    protected array $options;
    protected SettingsContainerInterface $requestOptions;
    protected Client $client;
    protected ResponseInterface $response;
    protected Factory $factory;
    protected array $headers;
    protected string $contentType;

    public function init(ResponseInterface $response, array $options = [], RequestOptions $requestOptions = null): self
    {
        $this->response = $response;
        $this->options = array_merge(
            $this->options ??
            [
                'maxRetries' => 3,
                'delayInSec' => 1,
                'minErrorCode' => 500,
                'logger' => [
                    'instance' => null,
                    'formatter' => null
                ],
                'caching' => [
                    'instance' => null
                ],
            ],
            $options
        );
        $this->requestOptions = $requestOptions ?? new RequestOptions();
        $this->factory = new Factory($this->options['logger']['instance'] ?? null, $options['isFake'] ?? null);
        $this->factory
            ->enableRetries($this->options['maxRetries'], $this->options['delayInSec'], $this->options['minErrorCode'])
            ->addOptions($this->requestOptions->toArray());

        if ($this->options['logger']['instance']) {
            $this->factory->enableLogging($this->options['logger']['formatter'] ?? Formatter::DEFAULT_FORMAT);
        }

        if ($this->options['caching']['instance']) {
            $this->factory->enableCache($this->options['caching']['instance']);
        }

        /**
         * Client inited w/ options
         */
        $this->client = $this->factory->make();

        return $this;
    }

    /**
     * Get the Response
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * Set the headers
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers ?? [], $headers);

        return $this;
    }

    /**
     * Set Client options
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->requestOptions->fromIterable($options);

        return $this;
    }

    /**
     * Set the content type
     *
     * @param string $contentType
     *
     * @return $this
     */
    public function setContentType(string $contentType = 'json'): self
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * GET Request
     *
     * @param string $endpoint
     * @param array $payload
     * @return ResponseInterface
     */
    public function get(string $endpoint, array $payload = []): ResponseInterface
    {
        return $this->request($endpoint, $payload);
    }

    /**
     * POST Request
     *
     * @param string $endpoint
     * @param array $payload
     * @return ResponseInterface
     */
    public function post(string $endpoint, array $payload = []): ResponseInterface
    {
        return $this->request($endpoint, $payload, 'POST');
    }

    /**
     * PUT Request
     *
     * @param string $endpoint
     * @param array $payload
     * @return ResponseInterface
     */
    public function put(string $endpoint, array $payload = []): ResponseInterface
    {
        return $this->request($endpoint, $payload, 'PUT');
    }

    /**
     * PATCH Request
     *
     * @param string $endpoint
     * @param array $payload
     * @return ResponseInterface
     */
    public function patch(string $endpoint, array $payload = []): ResponseInterface
    {
        return $this->request($endpoint, $payload, 'PATCH');
    }

    /**
     * DELETE Request
     *
     * @param string $endpoint
     * @param array $payload
     * @return ResponseInterface
     */
    public function delete(string $endpoint, array $payload = []): ResponseInterface
    {
        return $this->request($endpoint, $payload, 'DELETE');
    }

    /**
     * Perform the request
     *
     * @param string $endpoint
     * @param array $payload
     * @param string $method
     * @return ResponseInterface
     */
    protected function request(string $endpoint, array $payload = [], string $method = 'GET')
    {
        /**
         * Request options
         */
        $options = array_merge($this->requestOptions->toArray(), ['headers' => $this->headers ?? []]);

        if (isset($this->headers['auth'])) {
            $options['auth'] = $this->headers['auth'];
        }
        $payload = $this->convertToUTF8($payload);

        if ($method == 'GET') {
            $options['query'] = $payload;
        } else {
            switch ($this->contentType) {
                case 'application/x-www-form-urlencoded':
                    $options['form_params'] = $payload;
                    break;
                default:
                case 'json':
                    $options['json'] = $payload;
                    break;
            }
        }

        $this->response->reset();
        $this->response->endpoint = $endpoint;
        $this->response->request = $payload;

        try {
            $response = $this->client->request($method, $endpoint, $options);
            $this->response->body = (string)$response->getBody();
            $this->response->headers = $response->getHeaders();
            $this->response->loadData();
        } catch (GuzzleException | ClientException $e) {
            $this->response->responseSuccess = false;
            $this->response->responseCode = $e->getCode();
            $this->response->responseMessage = $e->getMessage();
            $this->response->body = $e->getResponse()->getBody()->getContents();
        } finally {
            return $this->response;
        }
    }

    /**
     * Sanitize payload to UTF-8
     *
     * @param array $array
     *
     * @return array
     */
    protected function convertToUTF8(array $array): array
    {
        array_walk_recursive($array, function (&$item) {
            if (!mb_detect_encoding($item, 'utf-8', true)) {
                $item = utf8_encode($item);
            }
        });

        return $array;
    }
}
