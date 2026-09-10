<?php

namespace App\Console\Commands\Autocheckin;

use Illuminate\Console\Command;
use App\BrandProduct;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;

class UpdateAutocheckinCustomFieldType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autocheckin:update-field {field} {type} {arrayName=validate_data_scan}';


    /**
     * Autochecking input types
     *
     */
    protected $fields = [
        'text',
        'number',
        'email',
        'date',
        'select',
        'autocomplete',
        'phone'
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the type of the given field';

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
        $fieldName = $this->argument('field');
        $newType = $this->argument('type');
        $arrayName = $this->argument('arrayName');

        if (!in_array($newType, $this->fields)) {
            $this->error("The given field is invalid");
            $this->line("try one of this:");
            foreach ($this->fields as $field) {
                $this->line($field);
            }
            return;
        }

        $brandProducts = BrandProduct::where(['product_id' => 21])->get();

        foreach ($brandProducts as $brandProduct) {
            $brandProductConfig = $brandProduct->brandProductConfig()
                ->where(['product_config_id' => 4])->first();

            $brandProductConfigValue = json_decode($brandProductConfig->value, true);

            $validateDataScan = collect(Arr::get($brandProductConfigValue, $arrayName)[0]);

            if ($validateDataScan->isEmpty()) {
                $this->error("The given array does not exist");
                return;
            }


            $updatedDataScan = $validateDataScan->map(function ($field) use ($fieldName, $newType) {
                if ($field['name'] == $fieldName) {
                    $field['type'] = $newType;
                }
                return $field;
            });


            $brandProductConfigValue[$arrayName] = [$updatedDataScan];
            $brandProductConfig->update(["value" => json_encode($brandProductConfigValue)]);
            Cache::tags(['brand_product_config' . $brandProductConfig->id])->flush();
        }
        $this->info('Updated successfully done!');
    }
}
