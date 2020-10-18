<?php

declare(strict_types=1);


namespace App\Services\UserNotificationService;


use App\Jobs\SendMailJob;
use App\Mail\OutdatedRepositoriesMail;
use App\Models\Mail;
use App\Repository\UserMailRepositoryInterface;
use App\Services\PackageReleaseService\ComposerOutdatedService;
use Illuminate\Support\Facades\Log;

class NotifyUserEmailService implements UserNotificationInterface
{

    protected UserMailRepositoryInterface $mailRepository;

    protected ComposerOutdatedService $composerOutdated;

    protected array $emails;

    public function __construct(UserMailRepositoryInterface $mailRepository, ComposerOutdatedService $composerOutdated)
    {
        $this->mailRepository = $mailRepository;
        $this->composerOutdated = $composerOutdated;
    }

    public function addEmailsToRepository(array $emails, string $repositoryUrl): self
    {
        $this->emails = $emails;
        try {
            $this->mailRepository->subscribeEmailsToRepository($emails, $repositoryUrl);
        } catch (\Exception $e) {
            Log::error(sprintf("Can not update the database %s", $e->getMessage()));
        }

        return $this;
    }

    public function notifyUsers(array $outdatedPackages): self
    {
        if ($this->emails === null) {
            Log::info('No email list found');
            return $this;
        }

        foreach ($this->emails as $email) {
            dispatch(new SendMailJob($email, new OutdatedRepositoriesMail($outdatedPackages)));
        }

        return $this;
    }

}