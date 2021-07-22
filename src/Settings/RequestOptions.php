<?php

namespace Jooservices\XcrawlerClient\Settings;

use GuzzleHttp\Cookie\CookieJarInterface;
use GuzzleHttp\RedirectMiddleware;
use Jooservices\XcrawlerClient\Settings\Traits\UserAgent;

/**
 * @property array|bool $allow_redirects
 * @property array|string|null $auth
 * @property mixed $body
 * @property string|array $cert
 * @property CookieJarInterface $cookies
 * @property float $connect_timeout
 * @property mixed $debug
 * @property string|bool $decode_content
 * @property integer|float $delay
 * @property integer|bool $expect
 * @property string $force_ip_resolve
 * @property array $form_params
 * @property array $headers
 * @property  bool $http_errors
 * @property bool|integer $idn_conversion
 * @property mixed $json
 * @property array $multipart
 * @property callable $on_headers
 * @property callable $on_stats
 * @property callable $progress
 * @property string|array $proxy
 * @property string|array $query
 * @property float $read_timeout
 * @property mixed $sink
 * @property  string|array $ssl_key
 * @property bool $stream
 * @property  bool $synchronous
 * @property string|bool $verify
 * @property  float $timeout
 * @property string|float $version
 */
class RequestOptions extends AbstractSettingsContainer
{
    use UserAgent;

    public function __construct(iterable $properties = null)
    {
        $this->fromIterable([
            'allow_redirects' => RedirectMiddleware::$defaultSettings,
            'http_errors' => true,
            'decode_content' => true,
            'verify' => true,
            'cookies' => false,
            'idn_conversion' => false,
            'headers' => [
                'User-Agent' => $this->getUserAgent()
            ]
        ]);

        parent::__construct($properties);
    }

    public function sink($toFile)
    {
        $this->sink = $toFile;

        return $this;
    }
}
