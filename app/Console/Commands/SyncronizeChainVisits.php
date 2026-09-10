<?php

namespace App\Console\Commands;

use App\UserVisits;
use App\Cadena;
use App\Services\BrandService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use DB;

class SyncronizeChainVisits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelinking:syncronize_chain_visits {parent_brand_id} {brand_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sums all visits of each user to all hotels of a chain and sets this result as the number of visits to the chain.';

    protected $brandService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get brand Id argument
        $parentBrandId = $this->argument('parent_brand_id');
        $brandId = $this->argument('brand_id');

        $introMsg = $brandId
            ? "Synchronize the chain visits of brand {$brandId} with parent brand: {$parentBrandId}"
            : "Synchronize the chain visits of parent brand: {$parentBrandId}";
        // Print intro
        $this->getOutput()->title($introMsg);

        $parentBrand = $this->brandService->getBrand($parentBrandId);
        $chainId = $parentBrand->chain_id;
        $childBrandIds = $parentBrand->children->pluck('id');

        // If specified brand doesnt belong to the parent brand, stop and output error
        if ($brandId && !in_array($brandId, $childBrandIds->all())) {
            $this->getOutput()->error("The entered child brand doesn't belong to this parent brand");
            return 0;
        }

        if ($brandId) {
            $brand = $parentBrand->children->where('id', $brandId)->first();
            if (!$brand->hotel_id) {
                $this->getOutput()->error("The entered child brand doesn't have hotel_id");
                return 0;
            }
        }

        $childHotelsIds = !empty($brand)
            ? [$brand->hotel_id]
            : $parentBrand->children->pluck('hotel_id');

        if (!$chainId || empty($childHotelsIds)) {
            $this->getOutput()->error("You have to enter a parent brand id that corresponds to a chain");
            return 0;
        }

        $minVisits = Cadena::find($chainId)->loyalty_min_visits ?? 2;

        $visits = UserVisits::whereIn('hotel_id', $childHotelsIds)
            ->select('user_id', DB::raw('SUM(num_visits) AS chain_visits, max(last_login) as last_login'))
            ->groupBy('user_id');

        $this->getOutput()->progressStart($visits->getQuery()->getCountForPagination());

        try {
            foreach ($visits->cursor() as $visit) {
                $recurrent = $visit->chain_visits >= $minVisits ? 1 : 0;

                UserVisits::updateOrCreate(
                    ['chain_id' => $chainId, 'user_id' => $visit->user_id, 'hotel_id' => null],
                    ['last_login' => $visit->last_login, 'recurrent' => $recurrent, 'num_visits' => $visit->chain_visits]
                );

                $this->getOutput()->progressAdvance();
            }

            $this->getOutput()->progressFinish();
        } catch (\Throwable $th) {
            $this->getOutput()->error("An error occurred while updating chain visits.");
            Log::error("Error syncronizing chain visits", [$th->getMessage()]);
        }
    }
}
