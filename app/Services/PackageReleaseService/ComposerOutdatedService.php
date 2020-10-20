<?php

declare(strict_types=1);


namespace App\Services\PackageReleaseService;


use App\Services\GitProviderPackageService\GitProviderPackageInterface;
use App\Services\PackageRegistryService\PackagistRegistryService;
use Composer\Semver\Comparator;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;

class ComposerOutdatedService implements ReleaseServiceInterface
{

    protected GitProviderPackageInterface $gitProvider;

    protected bool $packageFound = false;

    public function __construct(GitProviderPackageInterface $gitProvider)
    {
        $this->gitProvider = $gitProvider;
    }

    public function getOutdatedPackages(string $repositoryUrl)
    {
        $outdatedPackages = [];
        $composerPackages = $this->gitProvider->getComposerPackages($repositoryUrl);

        if (empty($composerPackages)) {
            return $outdatedPackages;
        }

        $this->packageFound = true;

        foreach ($composerPackages as $packageName => $version) {
            $vendorAndPackage = explode('/', $packageName);

            if (count($vendorAndPackage) === 2) {
                [$vendor, $package] = $vendorAndPackage;
                $latestVersion = PackagistRegistryService::getLatestVersion($vendor, $package);

                if ($this->isPackageOutdated($version, $latestVersion)) {
                    $outdatedPackages[] = [
                      'vendor' => $vendor,
                      'package' => $package,
                      'version' => $version,
                      'latest_version' => $latestVersion,
                    ];
                }
            }
        }

        return $outdatedPackages;
    }

    // 1.0.0 , 1.2.3
    public function isPackageOutdated(string $inputVersion, string $latestVersion)
    {
        if ($inputVersion[0] === '^' || $inputVersion[0] === '~') {
            $inputVersion = substr($inputVersion, 1);
        }

        if ($latestVersion[0] === 'v') {
            $latestVersion = substr($latestVersion, 1);
        }

        return Comparator::compare($inputVersion, '<', $latestVersion);
    }

    public function getPackageFoundStatus()
    {
        return $this->packageFound;
    }

}