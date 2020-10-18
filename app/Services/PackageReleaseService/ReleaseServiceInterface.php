<?php


namespace App\Services\PackageReleaseService;


interface ReleaseServiceInterface
{

    public function getOutdatedPackages(string $repositoryUrl);

    public function isPackageOutdated(string $inputVersion, string $latestVersion);

}