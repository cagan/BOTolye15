<?php

namespace App\Http\Controllers;

use App\Helpers\ComposerPackageParserTrait;
use App\Http\Requests\OutdatedPackageRequest;
use App\Services\PackageReleaseService\ComposerOutdatedService;
use App\Services\PackageReleaseService\NpmOutdatedService;
use App\Services\UserNotificationService\UserNotificationInterface;
use Symfony\Component\HttpFoundation\Response;

class OutdatedPackageController
{

    use ComposerPackageParserTrait;

    protected ComposerOutdatedService $composerOutdated;

    protected NpmOutdatedService $npmOutdated;

    protected UserNotificationInterface $userNotification;

    public function __construct(
      ComposerOutdatedService $composerOutdated,
      NpmOutdatedService $npmRelease,
      UserNotificationInterface $userNotification
    ) {
        $this->composerOutdated = $composerOutdated;
        $this->npmOutdated = $npmRelease;
        $this->userNotification = $userNotification;
    }

    public function listOutdatedPackages(OutdatedPackageRequest $request)
    {
        $emails = $request->get('email');
        $repositoryUrl = $request->get('repository');

        $outdatedComposerPackages = $this->composerOutdated->getOutdatedPackages($repositoryUrl);
        //        $outdatedNpmPackages = $npmRelease->getOutdatedPackages($repositoryUrl);

        if (empty($outdatedComposerPackages)) {
            return response()->json([
              'message' => 'No outdated package found',
              'data' => [],
            ])->setStatusCode(Response::HTTP_OK);
        }

        $emails = explode(',', $emails);
        $this->userNotification->addEmailsToRepository($emails, $repositoryUrl)->notifyUsers($outdatedComposerPackages);

        return response()->json([
          'message' => 'Outdated packages',
          'data' => [
            'composer_outdated' => $outdatedComposerPackages,
              //            'npm_outdated' => $outdatedNpmPackages,
            'composer_package_found' => $this->composerOutdated->getPackageFoundStatus(),
          ],
        ])->setStatusCode(Response::HTTP_OK);
    }

}