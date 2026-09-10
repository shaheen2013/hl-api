<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\User;
use App\Jobs\EmailResultRevalidatorJob;
use App\Services\Users\UserService;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;

class EmailResultRevalidatorJobTest extends TestCase
{
    /**
     * Method to instanciate a mocked job
     */
    private function getMockedJobInstance($userId, $emailResult = "deliverable", $sendex = 0.85)
    {
        return $this->instance(
            EmailResultRevalidatorJob::class,
            Mockery::mock(
                EmailResultRevalidatorJob::class . '[publishEventOnBus,sendRequest]',
                [
                    $userId
                ],
                function ($mock) use ($emailResult, $sendex) {
                    // Mock publishEventOnBus
                    $mock
                        ->shouldReceive('publishEventOnBus')
                        ->once()
                        ->andReturnTrue();

                    if ($sendex >= 0.5 && $emailResult != "undeliverable") {
                        // Mock sendRequest
                        $mock
                            ->shouldReceive('sendRequest')
                            ->once()
                            ->andReturn([
                                "email_quality" => $emailResult,
                                "email_rate" => $sendex
                            ]);
                    } else {
                        // A invalidation response
                        $mock
                            ->shouldReceive('sendRequest')
                            ->once()
                            ->andThrow(new ClientException(
                                "Invalid email",
                                new Request("GET", "localhost"),
                                new Response(
                                    401,
                                    [],
                                    json_encode([
                                        "email_quality" => $emailResult,
                                        "email_rate" => $sendex
                                    ])
                                )
                            ));
                    }
                }
            )
        );
    }

    /**
     * Test revalidate a user email from RISKY to DELIVERABLE
     *
     * @return void
     */
    public function testRevalidateADeliverableUserEmailAndGetItUpdated()
    {
        $user = factory(User::class)->create([
            'nombre' => 'Test user',
            'email_result' => 'risky',
            'sendex' => 0.5
        ]);

        // Instanciate job and execute it to get user updated
        $job = $this->getMockedJobInstance($user->id);
        $job->handle(app(UserService::class));

        // Retrieve user updated
        $updatedUser = User::findOrFail($user->id);

        // Check it user was updated
        $this->assertEquals($user->id, $updatedUser->id);
        $this->assertEquals($user->email, $updatedUser->email);
        $this->assertEquals($updatedUser->email_result, 'deliverable');
        $this->assertEquals($updatedUser->sendex, "0.85");
    }

    /**
     * Test revalidate a user email from RISKY to UNDELIVERABLE
     *
     * @return void
     */
    public function testRevalidateAUndeliverableUserEmailAndGetItUpdated()
    {
        $user = factory(User::class)->create([
            'nombre' => 'Test user',
            'email_result' => 'risky',
            'sendex' => 0.5
        ]);

        // Instanciate job and execute it to get user updated
        $job = $this->getMockedJobInstance($user->id, "undeliverable", 0.0);
        $job->handle(app(UserService::class));

        // Retrieve user updated
        $updatedUser = User::findOrFail($user->id);

        // Check it user was updated
        $this->assertEquals($user->id, $updatedUser->id);
        $this->assertEquals($user->email, $updatedUser->email);
        $this->assertEquals($updatedUser->email_result, 'undeliverable');
        $this->assertEquals($updatedUser->sendex, "0.00");
    }
}
