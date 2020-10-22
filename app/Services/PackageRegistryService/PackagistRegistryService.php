<?php

declare(strict_types=1);


namespace App\Services\PackageRegistryService;


use GuzzleHttp\Client;

class PackagistRegistryService implements DependencyRegistryInterface
{

    protected const API_URL = 'https://packagist.org/packages/';

    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getLatestVersion(string $vendor, string $package)
    {
        $response = $this->client->request('GET',
          sprintf("%s%s/%s.json", self::API_URL, $vendor, $package));
        $bodyResponse = $response->getBody()->getContents();
        $result = json_decode($bodyResponse, true);
        $versions = array_keys($result['package']['versions']);
        $versionRegex = '/v?[0-9]\.([0-9]|[0-9][0-9])\.([0-9]|[0-9][0-9])/';

        foreach ($versions as $version) {
            if (preg_match($versionRegex, $version)) {
                return $version;
            }
        }

        return null;
    }

}