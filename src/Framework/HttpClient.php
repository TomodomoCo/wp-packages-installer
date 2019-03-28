<?php

namespace Tomodomo\Packages\Installer\Framework;

use Buzz\Browser;
use Buzz\Client\Curl;
use Nyholm\Psr7\Factory\Psr17Factory;

class HttpClient
{
    /**
     * Get a request client.
     *
     * @return Browser
     */
    public static function request() : Browser
    {
        $client  = new Curl(new Psr17Factory());
        $browser = new Browser($client, new Psr17Factory());

        return $browser;
    }
}
