<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Client;

use Larium\Pay\Exception\CurlException;

class Curl
{
    const METHOD_GET        = 'GET';

    const METHOD_POST       = 'POST';

    const METHOD_PUT        = 'PUT';

    const METHOD_DELETE     = 'DELETE';

    const METHOD_HEAD       = 'HEAD';

    const METHOD_PATCH      = 'PATCH';

    const METHOD_CONNECT    = 'CONNECT';

    const METHOD_OPTIONS    = 'OPTIONS';

    const UNIX_NEWLINE      = "\n";

    const WINDOWS_NEWLINE   = "\r\n";

    private $options = [
        CURLOPT_HEADER          => 1,
        CURLINFO_HEADER_OUT     => 1,
        CURLOPT_RETURNTRANSFER  => 1,
        //close connection when it has finished, not pooled for reuse
        CURLOPT_FORBID_REUSE    => 1,
        // Do not use cached connection
        CURLOPT_FRESH_CONNECT   => 1,
        CURLOPT_CONNECTTIMEOUT  => 5,
        CURLOPT_TIMEOUT         => 7,
    ];

    private $body;

    /**
     * @param string $uri
     * @param string $method
     * @param string $body
     * @param array $headers
     * @param array $options
     */
    public function __construct(
        $uri,
        $method,
        $body = null,
        array $headers = [],
        array $options = []
    ) {
        $this->setUri($uri);
        $this->body = $this->resolveBody($body);
        $this->setHttpMethod($method);
        $this->setHeaders($headers);
        $this->setOptions($options);
    }

    public function setBasicAuthentication($username, $password)
    {
        $this->setOptions([CURLOPT_USERPWD => "{$username}:{$password}"]);
    }

    public function execute()
    {
        $handler = curl_init();
        if (false === curl_setopt_array($handler, $this->options)) {
            throw new CurlException('Invalid options for cUrl client');
        }
        $result = curl_exec($handler);
        $this->info = curl_getinfo($handler);

        if (false === $result) {
            $curlError = curl_error($handler);
            $curlErrno = curl_errno($handler);
            curl_close($handler);
            throw new CurlException($curlError, $curlErrno);
        }

        curl_close($handler);

        return $this->resolveResponse($result);
    }

    private function setUri($uri)
    {
        $this->setOptions([CURLOPT_URL => $uri]);
    }

    private function setHttpMethod($method)
    {
        $options = [];
        $method = strtoupper($method);

        switch ($method) {
            case static::METHOD_POST:
                $options[CURLOPT_POST]       = 1;
                $options[CURLOPT_POSTFIELDS] = $this->body;
                break;
            case static::METHOD_GET:
                $options[CURLOPT_HTTPGET]    = 1;
                break;
            case static::METHOD_PUT:
                $options[CURLOPT_POST]          = 1;
                $options[CURLOPT_CUSTOMREQUEST] = static::METHOD_PUT;
                $options[CURLOPT_POSTFIELDS]    = $this->body;
                break;
            case static::METHOD_DELETE:
                $options[CURLOPT_CUSTOMREQUEST] = static::METHOD_DELETE;
                break;
            case static::METHOD_PATCH:
                $options[CURLOPT_CUSTOMREQUEST] = static::METHOD_PATCH;
                break;
            case static::METHOD_HEAD:
                $options[CURLOPT_CUSTOMREQUEST] = static::METHOD_HEAD;
                $options[CURLOPT_NOBODY]        = true;
                break;
        }

        $this->setOptions($options);
    }

    private function setOptions(array $options = [])
    {
        $this->options = array_replace($this->options, $options);
    }

    private function resolveBody($body)
    {
        if (is_array($body)) {
            return http_build_query($body);
        }
    }

    private function resolveResponse($result)
    {
        $info = $this->info;

        $statusCode = $info['http_code'];
        $headersString = substr($result, 0, $info['header_size']);
        $headers = $this->resolveResponseHeaders($headersString);
        $body = substr($result, -$info['size_download']);

        $response = [
            'status' => $statusCode,
            'headers' => $headers,
            'body' => $body,
        ];

        return $response;
    }

    private function resolveResponseHeaders($headers)
    {
        $newLine = self::UNIX_NEWLINE;

        if (strpos($headers, self::WINDOWS_NEWLINE)) {
            $newLine = self::WINDOWS_NEWLINE;
        }

        $headerArray = [];
        $parts = explode($newLine, $headers);
        array_walk($parts, function (&$part) {
            $part = trim($part);
        });
        $headers = array_filter($parts, function ($v, $k) {
            return strlen($v) && false !== strpos($v, ':');
        }, ARRAY_FILTER_USE_BOTH);

        return $headers;
    }

    private function setHeaders(array $headers)
    {
        $this->setOptions([CURLOPT_HTTPHEADER => $headers]);
    }
}
