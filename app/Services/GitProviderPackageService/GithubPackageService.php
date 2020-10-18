<?php

declare(strict_types=1);


namespace App\Services\GitProviderPackageService;


use App\Helpers\ComposerPackageParserTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class GithubPackageService implements GitProviderPackageInterface
{

    use ComposerPackageParserTrait;

    public function getComposerPackages(string $repositoryUrl)
    {
        $vendor = $this->parseVendor($repositoryUrl);
        $package = $this->parsePackage($repositoryUrl);
        $apiUrl = "https://api.github.com/repos/$vendor/$package/contents/composer.json";

        try {
            $response = (new Client())->get($apiUrl);

            $bodyResponse = $response->getBody();
            $contents = $bodyResponse->getContents();
            $json = json_decode($contents);
            $composerContent = json_decode(base64_decode($json->content), true);

            return array_merge($composerContent['require'], $composerContent['require-dev']);
        } catch (RequestException $e) {
            Log::info("No composer package found");
            return [];
        }
    }

    public function getNpmPackages(string $repositoryUrl)
    {
    }

}