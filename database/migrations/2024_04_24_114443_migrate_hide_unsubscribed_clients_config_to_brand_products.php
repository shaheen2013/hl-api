
<?php

use App\BrandProduct;
use App\BrandProductConfig;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class MigrateHideUnsubscribedClientsConfigToBrandProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    
        $brandProductConfigs = BrandProductConfig::where('product_config_id', 20)->get();
        foreach ($brandProductConfigs as $row) {
            $brandProduct = BrandProduct::find($row->brand_product_id);    
            
            if ($brandProduct && $brandProduct->active) {
                $configData = ['hide_unsubscribed_clients' => $row->value == 1];
        
                $brandProduct->config = json_encode($configData);
                $brandProduct->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $brandProductConfigs = BrandProductConfig::where('product_config_id', 20)->get();

        foreach ($brandProductConfigs as $row) {
            $brandProduct = BrandProduct::find($row->brand_product_id);

            if ($brandProduct && $brandProduct->active) {
                $brandProduct->config = null;
                $brandProduct->save();
                }

        }
    }


}
