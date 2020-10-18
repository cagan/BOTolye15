<?php

declare(strict_types=1);


namespace App\Services\PackageRegistryService;


use GuzzleHttp\Client;

class PackagistRegistryService implements DependencyRegistryInterface
{

    protected const API_URL = 'https://packagist.org/packages/';

    public static function getLatestVersion(string $vendor, string $package)
    {
        $client = new Client();
        $response = $client->get(sprintf("%s%s/%s.json", self::API_URL, $vendor, $package));
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