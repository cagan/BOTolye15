<?php

declare(strict_types=1);


namespace App\Repository;


use App\Models\Mail;
use App\Models\Repository;

class MailRepository implements UserMailRepositoryInterface
{

    protected Mail $mail;

    protected Repository $repository;

    public function __construct(Mail $mail, Repository $repository)
    {
        $this->mail = $mail;
        $this->repository = $repository;
    }

    public function subscribeEmailsToRepository(array $emails, string $repositoryUrl)
    {
        $repository = Repository::where('repository_url', $repositoryUrl)->first();
        $mailIds = [];

        if ($repository === null) {
            $repository = Repository::create([
              'repository_url' => $repositoryUrl,
            ]);
        }

        foreach ($emails as $email) {
            $mail = Mail::where('email_address', $email)->first();

            if ($mail === null) {
                $mail = Mail::create([
                  'email_address' => $email,
                ]);
            }

            $mailIds[] = $mail->id;
        }

        $repository->mails()->sync($mailIds);
    }

    public function getAllMails()
    {
        return Mail::all();
    }

}