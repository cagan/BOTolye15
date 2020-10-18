<?php

declare(strict_types=1);


namespace App\Services\GitProviderPackageService;


interface GitProviderPackageInterface
{

    public function getComposerPackages(string $repositoryUrl);

    public function getNpmPackages(string $repositoryUrl);

}