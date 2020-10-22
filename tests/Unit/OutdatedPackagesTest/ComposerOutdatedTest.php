<?php

declare(strict_types=1);


namespace Tests\Unit\OutdatedPackagesTest;


use App\Services\GitProviderPackageService\GithubPackageService;
use App\Services\GitProviderPackageService\GitProviderPackageInterface;
use App\Services\PackageRegistryService\PackagistRegistryService;
use App\Services\PackageReleaseService\ComposerOutdatedService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ComposerOutdatedTest extends TestCase
{

    protected GitProviderPackageInterface $gitProvider;

    protected PackagistRegistryService $packagistRegistry;

    protected ComposerOutdatedService $composerService;

    protected MockHandler $mock;


    protected function setUp(): void
    {
        $githubResponseData = file_get_contents(__DIR__ . '/../Fixtures/github_fixture.json');
        $packagistResponseData = file_get_contents(__DIR__ . '/../Fixtures/packagist_fixture.json');

        $this->mock = new MockHandler([
          new Response(200, [], $githubResponseData),
          new Response(200, [], $packagistResponseData),
        ]);

        $handlerStack = HandlerStack::create($this->mock);
        $client = new Client(['handler' => $handlerStack]);

        $this->gitProvider = new GithubPackageService($client);
        $this->packagistRegistry = new PackagistRegistryService($client);
        $this->composerService = new ComposerOutdatedService($this->gitProvider, $this->packagistRegistry);
    }

    /**
     * @test
     */
    public function test_exec_version_comparation_if_both_are_equals()
    {
        $inputVersion = "2.1.1";
        $latestVersion = "2.1.1";

        $this->assertEquals(false, $this->composerService->isPackageOutdated($inputVersion, $latestVersion));
    }

    /**
     * @test
     */
    public function it_should_be_outdated_when_input_version_is_smaller()
    {
        $inputVersion = "1.3.2";
        $latestVersion = "2.1.1";

        $this->assertEquals(true, $this->composerService->isPackageOutdated($inputVersion, $latestVersion));
    }

    /**
     * @test
     */
    public function it_should_check_correctly_with_two_digit_versions()
    {
        $inputVersion = "1.3";
        $latestVersion = "2.1";

        $this->assertEquals(true, $this->composerService->isPackageOutdated($inputVersion, $latestVersion));

        $inputVersion = "2.3";
        $latestVersion = "1.1";

        $this->assertEquals(false, $this->composerService->isPackageOutdated($inputVersion, $latestVersion));
    }

}