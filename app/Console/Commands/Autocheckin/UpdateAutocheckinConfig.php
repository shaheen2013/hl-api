<?php

namespace App\Console\Commands\Autocheckin;

use Illuminate\Console\Command;
use App\BrandProduct;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;

class UpdateAutocheckinConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autocheckin:update-config {arrayName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     */
    public function handle()
    {
        $arrayName = $this->argument('arrayName');
        $brandProducts = BrandProduct::where(['product_id' => 21])->get();
        foreach ($brandProducts as $brandProduct) {
            $brandProductConfig = $brandProduct->brandProductConfig()
                ->where(['product_config_id' => 4])->first();

            $brandProductConfigValue = json_decode($brandProductConfig->value, true);

            $validateDataScan = Arr::get($brandProductConfigValue, $arrayName)[0];

            $defaultValidateDataScan = collect(config("autocheckin.default_configuration.identification.$arrayName")[0]);

            // Order the array with the default order
            $orderedArray = [];
            foreach ($defaultValidateDataScan as $key => $value) {
                if (isset($validateDataScan[$key]['name'])) {
                    $search_key = array_search($value['name'], array_column($validateDataScan, 'name'));

                    array_push($orderedArray, $validateDataScan[$search_key]);
                } else {
                    array_push($orderedArray, $value);
                }
            }

            $brandProductConfigValue[$arrayName] = [$orderedArray];
            $brandProductConfig->update(["value" => json_encode($brandProductConfigValue)]);
            Cache::tags(['brand_product_config' . $brandProductConfig->id])->flush();
        }
    }
}
