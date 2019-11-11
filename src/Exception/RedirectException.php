<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class RedirectException extends Exception
{

    /** @var string */
    private $url;

    /** @var array */
    private $headers;

    /** @var Cookie[] */
    private $cookies;

    public function __construct($url, $code = Response::HTTP_MOVED_PERMANENTLY, array $headers = [], array $cookies = [])
    {
        $this->url = $url;
        $this->headers = $headers;
        $this->cookies = $cookies;

        parent::__construct('', $code);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }

}
