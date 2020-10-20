<?php

declare(strict_types=1);


namespace Tests\Unit\OutdatedPackagesTest;


use App\Services\GitProviderPackageService\GithubPackageService;
use App\Services\PackageReleaseService\ComposerOutdatedService;
use Composer\Semver\Comparator;
use PHPUnit\Framework\TestCase;

class ComposerOutdatedTest extends TestCase
{

    /**
     * @test
     */
    public function test_exec_version_comparation_if_both_are_equals()
    {
        $inputVersion = "2.1.1";
        $latestVersion = "2.1.1";

        $composerService = (new ComposerOutdatedService(new GithubPackageService()));
        $this->assertEquals(false, $composerService->isPackageOutdated($inputVersion, $latestVersion));
    }

    /**
     * @test
     */
    public function it_should_not_be_outdated_with_bigger_symbol()
    {
        $inputVersion = ">1.3.2";
        $latestVersion = "2.1.1";

        $composerRelease = $this->createMock(ComposerOutdatedService::class);

        $this->assertEquals(false, $composerRelease->isPackageOutdated($inputVersion, $latestVersion));
    }

    /**
     * @test
     */
    public function it_should_be_outdated_with_specified_tilda_version()
    {
        $inputVersion = "~1.2.2";
        $latestVersion = "1.3.6";

        $composerService = (new ComposerOutdatedService(new GithubPackageService()));

        $this->assertEquals(true, $composerService->isPackageOutdated($inputVersion, $latestVersion));
    }

    /**
     * @test
     */
    public function it_should_not_be_outdated_with_specified_long_tilda_version()
    {
        $inputVersion = "~2.3|~3.1|~4.0|~5.0|~6.2.1";
        $latestVersion = "6.2.1";

        $composerOutdated = $this->createMock(ComposerOutdatedService::class);

        $this->assertEquals(false, $composerOutdated->isPackageOutdated($inputVersion, $latestVersion));
    }

    /**
     * @test
     */
    public function it_should_check_caret_to_get_outdated_result()
    {
        $inputVersion = "^1.0";
        $latestVersion = "v1.18.0";

        $composerService = (new ComposerOutdatedService(new GithubPackageService()));

        $this->assertEquals(false, $composerService->isPackageOutdated($inputVersion, $latestVersion));
    }

    /**
     * @test
     */
    public function redesign_comparator()
    {
        $inputVersion = "~1.10.0";
        $latestVersion = "v1.18.0";

        $composerService = (new ComposerOutdatedService(new GithubPackageService()));

        $this->assertEquals(true, $composerService->isPackageOutdated($inputVersion, $latestVersion));
    }

}