<?php

namespace App\Http\Controllers;

use App\Helpers\ComposerPackageParserTrait;
use App\Http\Requests\OutdatedPackageRequest;
use App\Services\PackageReleaseService\ComposerOutdatedService;
use App\Services\PackageReleaseService\NpmOutdatedService;
use App\Services\UserNotificationService\UserNotificationInterface;

class OutdatedPackageController
{

    use ComposerPackageParserTrait;

    public function listOutdatedPackages(
      OutdatedPackageRequest $request,
      ComposerOutdatedService $composerRelease,
      //      NpmOutdatedService $npmRelease,
      UserNotificationInterface $notification
    ) {
        $emails = $request->get('email');
        $repositoryUrl = $request->get('repository');

        $outdatedComposerPackages = $composerRelease->getOutdatedPackages($repositoryUrl);
        //        $outdatedNpmPackages = $npmRelease->getOutdatedPackages($repositoryUrl);

        // Outdated composer packages found
        if (!empty($outdatedComposerPackages)) {
            $emails = explode(',', $emails);
            $notification
              ->addEmailsToRepository($emails, $repositoryUrl)
              ->notifyUsers($outdatedComposerPackages);
        }

        return [
          'message' => 'Outdated packages',
          'data' => [
            'composer_outdated' => $outdatedComposerPackages,
              //            'npm_outdated' => $outdatedNpmPackages,
            'composer_package_found' => $composerRelease->getPackageFoundStatus(),
          ],
        ];
    }

}