<?php

declare(strict_types=1);


namespace App\Services\EmailNotificationService;


use App\Repository\UserMailListRepositoryInterface;

class NotifyUserEmailService
{

    protected UserMailListRepositoryInterface $mailListRepository;

    public function __construct(UserMailListRepositoryInterface $mailListRepository)
    {
        $this->mailListRepository = $mailListRepository;
    }

    public function sendUserToEmail(string $emails)
    {
        $emails = explode(',', $emails);
    }

    public function addUsersToEmailList(string $email, string $repositoryUrl)
    {
    }

}