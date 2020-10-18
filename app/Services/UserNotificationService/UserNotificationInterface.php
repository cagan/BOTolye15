<?php

declare(strict_types=1);


namespace App\Services\UserNotificationService;


interface UserNotificationInterface
{

    public function addEmailsToRepository(array $emails, string $repositoryUrl): self;

    public function notifyUsers(array $outdatedPackages): self;

}