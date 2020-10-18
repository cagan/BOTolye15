<?php

namespace App\Http\Controllers;

use App\Helpers\ComposerPackageParserTrait;
use App\Http\Requests\OutdatedPackageRequest;
use App\Services\EmailNotificationService\NotifyUserEmailService;
use App\Services\GitProviderPackageService\GitProviderPackageInterface;
use App\Services\PackageReleaseService\ComposerOutdatedService;
use App\Services\PackageReleaseService\NpmOutdatedService;

class OutdatedPackageController
{

    use ComposerPackageParserTrait;

    private GitProviderPackageInterface $providerPackage;

    public function __construct(GitProviderPackageInterface $providerPackage)
    {
        $this->providerPackage = $providerPackage;
    }

    public function listOutdatedPackages(
      OutdatedPackageRequest $request,
      ComposerOutdatedService $composerRelease,
      NpmOutdatedService $npmRelease,
      NotifyUserEmailService $notifyUserEmail
    ) {
        $email = $request->get('email');
        $repositoryUrl = $request->get('repository');

        $outdatedComposerPackages = $composerRelease->getOutdatedPackages($repositoryUrl);
        $outdatedNpmPackages = $npmRelease->getOutdatedPackages($repositoryUrl);

        // Outdated package found
        if (!empty($outdatedComposerPackages)) {
            $notifyUserEmail->addUsersToEmailList($email, $repositoryUrl);
            $notifyUserEmail->sendUserToEmail($email);
        }

        return [
          'message' => 'Outdated packages',
          'data' => [
            'composer_outdated' => $outdatedComposerPackages,
            'npm_outdated' => $outdatedNpmPackages,
            'composer_package_found' => $composerRelease->getPackageFoundStatus(),
          ],
        ];
    }

}