<?php

namespace App\Services\Products;

use App\BrandCustomContent;
use App\CustomContent;
use App\CustomContentState;
use App\Services\BrandService;
use Illuminate\Http\Request;
use App\Services\BrandProductService;

class EprivacyResponsibleProductService implements HLProduct
{
    private $brandProductService;
    private $brandService;

    public function __construct(BrandProductService $brandProductService, BrandService $brandService)
    {
        $this->brandProductService = $brandProductService;
        $this->brandService = $brandService;
    }

    public function setConfiguration($brand_id, Request $request)
    {
        //
    }

    public function getConfiguration($brandID)
    {
        $product = $this->productService->getByName('eprivacy_responsible');
        return $this->brandProductService->get($brandID, $product->id);
    }

    public function setDefaultConfiguration($brand_id, $beeingActivated)
    {
        $brand = $this->brandService->getBrand($brand_id);
        $hotel = $brand->hotel;

        if ($hotel) {
            $editablesTexts = ['first_eprivacy_page' => 'eprivacy_text', 'second_eprivacy_page' => 'second_eprivacy_text', 'legal_text' => 'legal_text'];

            foreach ($editablesTexts as $pageName => $editablesText) {
                $customContent = CustomContent::where(['name' => $pageName])->first();

                $customContentState = CustomContentState::where([
                    'name' => 'classic',
                    'custom_content_id' => $customContent->id
                ])->first();

                BrandCustomContent::updateOrCreate(
                    [
                        'hotel_id' => $hotel->id,
                        'custom_content_id' => $customContent->id,
                    ],
                    [
                        'custom_content_state_id' => $customContentState->id,
                        'active' => 1,
                        'configuration' => 'default'
                    ]
                );
            }
        }
    }
}
