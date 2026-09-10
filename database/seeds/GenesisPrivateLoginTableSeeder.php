<?php

use Illuminate\Database\Seeder;
use App\PrivateLogin;

class GenesisPrivateLoginTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        switch (config('app.env')) {
            case "production":
                // In production we have one account per worker
                break;
            case "beta":
                PrivateLogin::updateOrCreate(
                    ['id' => 1],
                    [
                        'username' => 'Hotelinking',
                        'password' => '2e1e15c261f6e5bf3ad0ecce266bd62afd28fb45'
                    ]
                );
                break;
            case "dev":
                PrivateLogin::updateOrCreate(
                    ['id' => 1],
                    [
                        'username' => 'Hotelinking',
                        'password' => 'db51785dda5e67d37a7e2dae5a2fa03bd4fc1ac1'
                    ]
                );
                break;
            default:
                PrivateLogin::updateOrCreate(
                    ['id' => 1],
                    [
                        'username' => 'Hotelinking',
                        'password' => '270af5d6a0fc22f716abaa504c0525899b81eee0'
                    ]
                );
                break;
                
        }
    }
}
