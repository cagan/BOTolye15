<?php

declare(strict_types=1);


namespace Tests\Unit\ClientTest;


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
        $responseData = file_get_contents(__DIR__ . '/../Fixtures/packagist_fixture.json');

        $mock = new MockHandler([
          new Response(200, [], $responseData),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $packagistRegistryService = new PackagistRegistryService($client);
        $response = $packagistRegistryService->getLatestVersion('symfony', 'polyfill');

        $this->assertEquals('v1.18.0', $response);
    }

}