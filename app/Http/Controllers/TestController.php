<?php

declare(strict_types=1);


namespace App\Http\Controllers;


use App\Jobs\SendMailJob;
use App\Mail\OutdatedRepositoriesMail;

class TestController
{

    public function test()
    {
        $emailAddress = 'cagan.sit@gmail.com';
        $composerOutdated = [];
        dispatch(new SendMailJob($emailAddress, new OutdatedRepositoriesMail($composerOutdated)));
    }

}