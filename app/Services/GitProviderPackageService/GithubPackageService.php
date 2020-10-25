<?php

declare(strict_types=1);


namespace App\Services\GitProviderPackageService;


use App\Helpers\ComposerPackageParserTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GithubPackageService implements GitProviderPackageInterface
{

    use ComposerPackageParserTrait;

    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param  string  $repositoryUrl
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getComposerPackages(string $repositoryUrl)
    {
        $vendor = $this->parseVendor($repositoryUrl);
        $package = $this->parsePackage($repositoryUrl);
        $apiUrl = "https://api.github.com/repos/$vendor/$package/contents/composer.json";

        try {
            $response = $this->client->request('GET', $apiUrl);
            $bodyResponse = $response->getBody();
            $contents = $bodyResponse->getContents();
            $json = json_decode($contents);
            $composerContent = json_decode(base64_decode($json->content), true);

            return array_merge($composerContent['require'], $composerContent['require-dev']);
        } catch (RequestException $e) {
            return [];
        }
    }

    public function getNpmPackages(string $repositoryUrl)
    {
        // TODO: Implement getNpmPackages() method.
    }

}