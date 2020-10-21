<?php

declare(strict_types=1);


namespace Tests\Unit\OutdatedControllerTest;


use App\Http\Requests\OutdatedPackageRequest;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

use function Illuminate\Events\queueable;

class OutdatedControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        Session::start();
    }

    /**
     * @test
     */
    public function it_should_return_200_when_send_proper_data()
    {
        $requestData = [
          'email' => 'cagan.sit@gmail.com',
          'repository' => 'https://github.com/symfony/polyfill',
          '_token' => csrf_token(),
        ];

        $response = $this->post('/list/outdated-packages/', $requestData);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_should_not_return_success_when_request_data_is_not_proper()
    {
        $request = new OutdatedPackageRequest();

        $requestData = [
          'repository' => 'https://github.com/symfony/polyfill',
          '_token' => csrf_token(),
        ];

        $validator = Validator::make($requestData, $request->rules());

        $response = $this->post('/list/outdated-packages', $requestData);
        $response->assertStatus(302);
        $this->assertTrue($validator->fails());
    }

}