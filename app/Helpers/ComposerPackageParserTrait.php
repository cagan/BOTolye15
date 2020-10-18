<?php

declare(strict_types=1);


namespace App\Helpers;


trait ComposerPackageParserTrait
{

    public function parseVendor(string $repositoryUrl)
    {
        $urlPath = parse_url($repositoryUrl)['path'];

        return explode('/', substr($urlPath, strpos($urlPath, "/") + 1))[0];
    }

    public function parsePackage(string $repositoryUrl)
    {
        $urlPath = parse_url($repositoryUrl)['path'];

        return explode('/', substr($urlPath, strpos($urlPath, "/") + 1))[1];
    }

}