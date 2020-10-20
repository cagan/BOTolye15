<?php

namespace App\Console\Commands;

use App\Jobs\SendMailJob;
use App\Mail\OutdatedRepositoriesMail;
use App\Repository\MailRepository;
use App\Services\PackageReleaseService\ComposerOutdatedService;
use App\Services\UserNotificationService\UserNotificationInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyUsers extends Command
{

    protected UserNotificationInterface $userNotification;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:users';

    protected ComposerOutdatedService $composerOutdated;

    protected MailRepository $mailRepository;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to users';

    public function __construct(
      UserNotificationInterface $userNotification,
      ComposerOutdatedService $composerOutdated,
      MailRepository $mailRepository
    ) {
        parent::__construct();
        $this->userNotification = $userNotification;
        $this->composerOutdated = $composerOutdated;
        $this->mailRepository = $mailRepository;
    }

    /**
     * Execute the console command.
     * This will be executed every day
     *
     * @return int
     */
    public function handle()
    {
        $mailList = $this->mailRepository->getAllMails();

        date_default_timezone_set('Europe/Istanbul');
        $now = date("Y-m-d H:i", strtotime(Carbon::now()));
        logger($now);

        foreach ($mailList as $mail) {
            foreach ($mail->repositories as $repository) {
                $emailAddress = $mail->email_address;
                $repositoryUrl = $repository->repository_url;
                $composerOutdated = $this->composerOutdated->getOutdatedPackages($repositoryUrl);

                dispatch(new SendMailJob($emailAddress, new OutdatedRepositoriesMail($composerOutdated)));
            }
        }
    }

}
