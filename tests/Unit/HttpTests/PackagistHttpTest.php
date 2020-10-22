<?php

declare(strict_types=1);


namespace Tests\Unit\HttpTests;


use App\Services\PackageRegistryService\PackagistRegistryService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class PackagistHttpTest extends TestCase
{

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @test
     */
    public function it_should_fetch_packagist_repository_data_properly()
    {
        $responseData = [
          "package" => [
            "name" => "symfony\/polyfill-mbstring",
            "description" => "Symfony polyfill for the Mbstring extension",
            "time" => "2015-10-25T13:17:47+00:00",
            "maintainers" => [
              "name" => "fabpot",
              "avatar_url" => "https:\/\/www.gravatar.com\/avatar\/9a22d09f92d50fa3d2a16766d0ba52f8?d=identicon",
            ],
            "versions" => [
              "dev-main" => [],
              "v1.18.0" => [],
              "v.17.2" => [],
            ],
          ],
        ];

        $mock = new MockHandler([
          new Response(200, [], json_encode($responseData)),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);
        $packagistRegistryService = new PackagistRegistryService($client);
        $response = $packagistRegistryService->getLatestVersion('symfony', 'polyfill');

        $this->assertEquals('v1.18.0', $response);
    }

}