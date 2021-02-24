<?php

namespace KunicMarko\GraphQLTest\Bridge\Symfony;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use KunicMarko\GraphQLTest\Bridge\TestCaseTrait;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
class TestCase extends WebTestCase
{
    use TestCaseTrait;

    protected KernelBrowser $client;

    private function call(
        string $method,
        string $uri,
        array $parameters = [],
        array $cookies = [],
        array $files = [],
        array $headers = []
    ) {
        if (!isset($this->client)) {
            $this->client = static::createClient();
        }
        $client = $this->client;
        $cookieJar = $client->getCookieJar();

        foreach ($cookies as $cookie) {
            $cookieJar->set($cookie);
        }

        $client->request(
            $method,
            $uri,
            $parameters,
            $files,
            $headers
        );

        return $client;
    }
}
