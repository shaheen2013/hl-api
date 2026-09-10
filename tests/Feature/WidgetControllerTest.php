<?php

namespace Tests\Feature;

use App\Cadena;
use App\Hotel;
use App\Product;
use Tests\TestCase;
use App\Services\Cognito;

class WidgetControllerTest extends TestCase
{
    public function testRegisterHotelWidget()
    {
        $cognitoServiceMock = $this->createMock(Cognito::class);
        $cognitoServiceMock->expects($this->any())
            ->method('setCognitoUserIfNotExists')
            ->willReturn(true);
        $this->app->instance(Cognito::class, $cognitoServiceMock);

        $hotel = factory(Hotel::class)->create();
        Product::firstOrCreate(['producto' => 'widget']);

        $user = [
            'email'    => $hotel->email,
            'password' => $hotel->password,
            'type'     => 'hotel'
        ];

        $this->post('/api/register/widget', $user)->assertStatus(200);
    }

    public function testRegisterChainWidget()
    {
        $cognitoServiceMock = $this->createMock(Cognito::class);
        $cognitoServiceMock->expects($this->any())
            ->method('setCognitoUserIfNotExists')
            ->willReturn(true);
        $this->app->instance(Cognito::class, $cognitoServiceMock);

        $chain = factory(Cadena::class)->create();
        $user = [
            'email'    => $chain->email,
            'password' => $chain->password,
            'type'     => 'chain'
        ];

        $this->post('/api/register/widget', $user)->assertStatus(200);
    }
}
