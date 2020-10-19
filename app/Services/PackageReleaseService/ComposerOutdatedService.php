<?php

declare(strict_types=1);


namespace App\Services\PackageReleaseService;


use App\Services\GitProviderPackageService\GitProviderPackageInterface;
use App\Services\PackageRegistryService\PackagistRegistryService;

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

    /**
     * I did things done by manually becuase packagist api doesn't show is the package outdated or not.
     * I just compared by myself with some checks. It may not working properly for now. But it can be fixed.
     *
     * @param  string  $inputVersion
     * @param  string  $latestVersion
     *
     * @return bool
     */
    public function isPackageOutdated(string $inputVersion, string $latestVersion)
    {
        // Remove Pipes or spaces
        $inputVersion = preg_replace('/(\s|\|\|)+/', '', $inputVersion);
        // Remove
        if (substr($latestVersion, 0, 1) === 'v') {
            $latestVersion = ltrim($latestVersion, 'v');
        }

        if (strlen($inputVersion) === strlen($latestVersion)) {
            return $inputVersion !== $latestVersion;
        }

        $splitedVersion = str_split($inputVersion);
        $latestVersion = str_split($latestVersion);

        // Example: inputVersion = ">1.2.1"
        if ($inputVersion[0] === '>') {
            return false;
        }

        if ($inputVersion[0] === '^') {
            return true;
        }

        if ($inputVersion[0] === '~') {
            // Example: inputVersion = "~1.2.2"
            if (count($splitedVersion) === 6) {
                return $inputVersion[3] !== $latestVersion[2];
            }

            if (count($splitedVersion) > 6 && in_array('|', $splitedVersion)) {
                $pipeVersions = explode("|", $inputVersion);
                foreach ($pipeVersions as $pipeVersion) {
                    // Example: inputVersion = "~2.3|~3.0|~4.0|~5.0"
                    if (strlen($pipeVersion) === 4) {
                        if (intval(substr($pipeVersion, 1, 1)) === intval($latestVersion[0])) {
                            return false;
                        }
                        // Example: inputVersion = "~2.3.5"
                    } elseif (strlen($pipeVersion) === 6) {
                        if (intval(substr($pipeVersion, 3, 1)) === intval($latestVersion[2])) {
                            return false;
                        }
                    }
                }

                return true;
            }
        }

        return false;
    }

    public function getPackageFoundStatus()
    {
        return $this->packageFound;
    }

}