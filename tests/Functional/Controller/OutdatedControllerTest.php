<?php

declare(strict_types=1);


namespace Tests\Functional\Controller;


use Illuminate\Support\Facades\Session;
use Tests\TestCase;


class OutdatedControllerTest extends TestCase
{

    /**
     * @test
     */
    public function it_should_return_200_when_send_proper_data()
    {
        Session::start();
        $response = $this->post('/list/outdated-packages', [
          'email' => 'cagan.sit@gmail.com',
          'repository' => 'https://github.com/symfony/polyfill',
          '_token' => csrf_token(),
        ]);
        $response->assertStatus(200);
    }

}