<?php


namespace App\Services\PackageRegistryService;


interface DependencyRegistryInterface
{

    public function getLatestVersion(string $vendor, string $package);

}