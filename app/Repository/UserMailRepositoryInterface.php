<?php

declare(strict_types=1);


namespace App\Repository;


interface UserMailRepositoryInterface
{

    public function subscribeEmailsToRepository(array $emails, string $repositoryUrl);

}