<?php

declare(strict_types=1);


namespace App\Services\PackageReleaseService;


use App\Services\GitProviderPackageService\GitProviderPackageInterface;

class NpmOutdatedService implements ReleaseServiceInterface
{

    protected GitProviderPackageInterface $gitProvider;

    public function __construct(GitProviderPackageInterface $gitProvider)
    {
        $this->gitProvider = $gitProvider;
    }

    public function getOutdatedPackages(string $repositoryUrl)
    {
        $npmPackages = $this->gitProvider->getNpmPackages($repositoryUrl);

        return [];
    }

    public function isPackageOutdated(string $inputVersion, string $latestVersion)
    {
        // TODO: Implement isPackageOutdated() method.
    }

}