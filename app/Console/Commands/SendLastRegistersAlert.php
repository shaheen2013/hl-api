<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Hotel;
use App\User;
use Hotelinking\Services\SchemaValidator;
use Hotelinking\Services\ApiGatewayConnection;

class SendLastRegistersAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelinking:check-last-registers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check date of last user registration and alert if it is greater than 24 hours';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \App\Exceptions\SchemaNotValidException
     */
    public function handle()
    {
        $schemaValidator = app(SchemaValidator::class);
        $gateway = new ApiGatewayConnection($schemaValidator);
        $yesterday = Carbon::yesterday();
        $hotels = Hotel::where('activated', 1)
            ->get();

        $event = [
            "schema"         => "com.hotelinking/Emails/send_registration_alert/1.0.0",
            "origin"         => "HotelinkingApi",
            "originalEntity" => "HotelinkingApi",
            "eventSource"    => env('APP_URL', 'https://api.hotelinking.com'),
            "context"        => [
                "brandID" => null
            ],
            "payload"        => null,
        ];

        foreach ($hotels as $hotel) {
            $lastConnection = $hotel->connectionHistory()
                ->orderBy('first_login', 'desc')
                ->first();

            if ($lastConnection && ($firstLogin = Carbon::parse($lastConnection->first_login)) < $yesterday) {
                $brand = $hotel->brand()->first();
                $chain = $hotel->chain()->first();
                $user = User::find($lastConnection->id_user);

                $event['context']['brandID'] = $brand->id ?? null;
                $event['payload'] = [
                    'chainName'      => $chain->nombre ?? null,
                    'hotelName'      => $hotel->hotelName ?? null,
                    'userName'       => $user->nombre ?? null,
                    'userEmail'      => $user->email ?? null,
                    'userLang'       => $user->lang ?? null,
                    'lastConnection' => $firstLogin->toIso8601ZuluString()
                ];

                $gateway->sendRequest($event, null);
                break;
            }
        }
    }
}
