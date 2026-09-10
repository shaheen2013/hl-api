<?php

namespace App\Console\Commands\Autocheckin;

use Illuminate\Console\Command;
use App\BrandProduct;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;

class UpdateAutocheckinCustomFieldAttribute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autocheckin:update-attribute {arrayName} {inputName} {attribute} {newValue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates or creates an input paramenter of the given array';

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
        $attribute = $this->argument('attribute');
        $newValue = $this->argument('newValue');
        $arrayName = $this->argument('arrayName');
        $inputName = $this->argument('inputName');

        $brandProducts = BrandProduct::where(['product_id' => 21])->get();
        foreach ($brandProducts as $brandProduct) {
            $brandProductConfig = $brandProduct->brandProductConfig()
                ->where(['product_config_id' => 4])->first();

            $brandProductConfigValue = json_decode($brandProductConfig->value, true);

            $arrayToUpdate = collect(Arr::get($brandProductConfigValue, $arrayName)[0]);

            if ($arrayToUpdate->isEmpty()) {
                $this->error("The given array does not exist");
                return;
            }

            $updatedArray = $arrayToUpdate->map(function ($item) use ($inputName, $attribute, $newValue) {

                if (!isset($item[$attribute]) && $item['name'] == $inputName) {
                    $item[$attribute] = $newValue;
                }

                return $item;
            });

            $brandProductConfigValue[$arrayName] = [$updatedArray];
            $brandProductConfig->update(["value" => json_encode($brandProductConfigValue)]);
            Cache::tags(['brand_product_config' . $brandProductConfig->id])->flush();
        }
    }
}
