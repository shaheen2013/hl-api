<?php

namespace App\Console\Commands;

use App\Services\BrandService;
use App\Services\BrandProductService;
use Illuminate\Console\Command;

class FireBrandUpdatedEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelinking:fire-brand-updated-event {productId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $brandService;
    protected $brandProductService;



    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BrandService $brandService, BrandProductService $brandProductService)
    {
        $this->brandService = $brandService;
        $this->brandProductService = $brandProductService;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $productId = $this->argument('productId');

        $brandProducts = $this->brandProductService->getActiveBrands($productId);

        foreach ($brandProducts as $brandProduct) {
            $this->brandService->sendBrandUpdatedEvent($brandProduct->brand_id);
        }
    }
}
