<?php


namespace App\Services\PackageRegistryService;


interface DependencyRegistryInterface
{

    public static function getLatestVersion(string $vendor, string $package);

}