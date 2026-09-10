<?php

use Illuminate\Database\Seeder;
use App\ExternalApi;

class GenesisExternalApisTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        ExternalApi::updateOrCreate(
            ['id' => 1],
            [
                'api_name'   => 'pushtech',
                'url'        => 'https://www.pushtech.com/api/v2/account/:account_id/',
                'api_header' => null
            ]
        );

        ExternalApi::updateOrCreate(
            ['id' => 2],
            [
                'api_name'   => 'FullContact',
                'url'        => 'https://api.fullcontact.com/v2/',
                'api_header' => 'X-FullContact-APIKey:5923065592991d2f'
            ]
        );

    }
}
