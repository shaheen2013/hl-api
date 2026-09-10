<?php

namespace App\Console\Commands;

use Aws\Sdk;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Brand;
use App\HotelStaff;
use Illuminate\Support\Arr;

class SyncronizeCognitoUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelinking:syncronize-congnito-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize database Accounts with Cognito';

    private $congnitoConfig;
    private $cognitoClient;
    

    /**
     * Create a new command instance.
     *
     * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->congnitoConfig = config('services.aws.cognito-suite');
        $aws = new Sdk($this->congnitoConfig);
        $this->cognitoClient = $aws->createCognitoIdentityProvider(["suppress_php_deprecation_warning" => true]);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->getOutput()->info("Starting syncronizing staffs...");
        $staffs = HotelStaff::where(["activo" => 1, "deleted" => 0])->get();
        $this->getOutput()->progressStart(count($staffs));
        $groups = [
            "1" => "ACCOUNT_ADMINS",
            "2" => "BRAND_ADMINS",
            "3" => "STAFF"
        ];

        foreach ($staffs as $staff) {
            try {
                $userAttributes = [
                    [
                        'Name' => 'email',
                        'Value' => $staff->email,
                    ],
                    [
                        'Name' => 'email_verified',
                        'Value' => 'true',
                    ],
                    [
                        'Name' => 'name',
                        'Value' => $staff->nombre,
                    ],
                    [
                        'Name' => 'custom:lang',
                        'Value' => $staff->lang,
                    ],
                ];

                $brands = [];
                foreach ($staff->hotel as $hotelStaff) {
                    $brand = Brand::where(['hotel_id' => data_get($hotelStaff, 'hotel_id')])->first();

                    if ($brand) {
                        array_push($brands, $brand);
                    }
                }

                if (data_get($brands, '0.parent_id')) {
                    array_push($userAttributes, [
                        'Name' => 'custom:account_id',
                        'Value' => (string) $brands[0]->parent_id,
                    ]);
                }
                
                $group = $groups[$staff->id_role];
                if ($group !== "ACCOUNT_ADMINS" && !empty($brands)) {
                    array_push($userAttributes, [
                        'Name' => 'custom:brand_id',
                        'Value' => implode(',', Arr::pluck($brands, 'id')),
                    ]);
                }

                $this->cognitoClient->adminCreateUser([
                    'DesiredDeliveryMediums' => ["EMAIL"],
                    'UserAttributes' => $userAttributes,
                    'UserPoolId' => $this->congnitoConfig['user_pool_id'],
                    'Username' => $staff->email
                ]);
        
                $this->cognitoClient->adminAddUserToGroup([
                    'GroupName' => $group,
                    'Username' => $staff->email,
                    'UserPoolId' => $this->congnitoConfig['user_pool_id']
                ]);
            } catch (\Exception $e) {
                Log::error("Error Syncronizing Cognito User", ["email" => $staff->email, "exception" => $e]);
            }

            $this->getOutput()->progressAdvance();
            usleep(100000);
        }

        $this->getOutput()->progressFinish();

        return 0;
    }
}
