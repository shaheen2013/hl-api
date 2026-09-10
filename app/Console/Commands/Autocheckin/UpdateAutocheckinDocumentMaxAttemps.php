<?php

namespace App\Console\Commands\Autocheckin;

use Illuminate\Console\Command;
use App\BrandProduct;

class UpdateAutocheckinDocumentMaxAttemps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autocheckin:update-document-attempt {number}';

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
        $number = (int)$this->argument('number');
        $brandProducts = BrandProduct::where(['product_id' => 21])->get();

        foreach ($brandProducts as $brandProduct) {
            $brandProduct->brandProductConfig()
                ->where(['product_config_id' => 8])
                ->update(['value' => $number]);
        }
    }
}
