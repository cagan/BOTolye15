<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OutdatedRepositoriesMail extends Mailable
{

    use Queueable, SerializesModels;

    protected array $composerOutdatedPackages;

    public function __construct(array $composerOutdatedPackages)
    {
        $this->composerOutdatedPackages = $composerOutdatedPackages;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
          ->markdown('emails.outdatedrepositories')
          ->subject('Outdated Packages')
          ->from('botolye15@example.com', 'BOTolye15')
          ->with([
            'composerOutdated' => $this->composerOutdatedPackages,
          ]);
    }

}
