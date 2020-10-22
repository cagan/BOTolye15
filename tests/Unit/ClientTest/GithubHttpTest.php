<?php

declare(strict_types=1);


namespace Tests\Unit\ClientTest;


use App\Services\GitProviderPackageService\GithubPackageService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class GithubHttpTest extends TestCase
{

    /**
     * @test
     */
    public function it_should_show_composer_packages_from_github_api()
    {
        $responseData = file_get_contents(__DIR__ . '/../Fixtures/github_fixture.json');

        $repositoryUrl = 'https://github.com/symfony/polyfill';

        $mock = new MockHandler([
          new Response(200, [], $responseData),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $githubPackageService = new GithubPackageService($client);
        $composerPackages = $githubPackageService->getComposerPackages($repositoryUrl);

        $this->assertArrayHasKey('composer/semver', $composerPackages);
        $this->assertArrayHasKey('symfony/finder', $composerPackages);
        $this->assertArrayHasKey('psr/log', $composerPackages);
        $this->assertArrayHasKey('php', $composerPackages);
        $this->assertArrayHasKey('composer/ca-bundle', $composerPackages);
    }


    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @test
     */
    public function it_should_give_empty_array_when_composer_package_not_found()
    {
        $repositoryUrl = 'https://github.com/composer/no-composer-package';

        $mock = new MockHandler([
          new RequestException('No package found', new Request('GET', $repositoryUrl)),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $githubPackageService = new GithubPackageService($client);
        $composerPackages = $githubPackageService->getComposerPackages($repositoryUrl);

        $this->assertEquals([], $composerPackages);
    }

}