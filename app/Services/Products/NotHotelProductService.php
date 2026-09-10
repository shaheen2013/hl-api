<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 14/03/2019
 * Time: 15:58
 */

namespace App\Services\Products;

use App\Brand;
use App\BrandCustomContent;
use App\BrandEprivacy;
use App\CustomContent;
use App\CustomContentState;
use Illuminate\Http\Request;

class NotHotelProductService implements HLProduct
{
    public function setConfiguration($brand_id, Request $request)
    {
        //
    }

    public function getConfiguration($brand_id)
    {
        //
    }

    public function setDefaultConfiguration($brand_id, $active)
    {

        $brand = Brand::findOrFail($brand_id);
        $hotel = $brand->hotel;
        if ($active) {
            $this->setBrandCustomContent($hotel->id, null, 'first_eprivacy_page', 'restrictive_not_hotel', 1, 'default');
            $this->setBrandCustomContent($hotel->id, null, 'second_eprivacy_page', 'restrictive_not_hotel', 1, 'default');
            $this->setBrandCustomContent($hotel->id, null, 'legal_text', 'restrictive_not_hotel', 1, 'default');
            $this->setBrandCaptivePortalType($hotel->id, 1);
        } else {
            $this->setBrandCustomContent($hotel->id, null, 'first_eprivacy_page', 'classic', 1, 'default');
            $this->setBrandCustomContent($hotel->id, null, 'second_eprivacy_page', 'classic', 1, 'default');
            $this->setBrandCustomContent($hotel->id, null, 'legal_text', 'classic', 1, 'default');
            $this->setBrandCaptivePortalType($hotel->id, 0);
        }
    }

    private function setBrandCustomContent($hotel_id, $chain_id, $pageName, $pageState, $active, $configuration)
    {
        $brand_id = $hotel_id ? $hotel_id : $chain_id;
        $brand_type = $hotel_id ? 'hotel_id' : 'chain_id';

        $customContent = CustomContent::where("name", $pageName)->first();
        $customContentState = CustomContentState::where("name", $pageState)->where("custom_content_id", $customContent->id)->first();
        BrandCustomContent::updateOrCreate(
            [
                $brand_type => $brand_id,
                'custom_content_id' => $customContent->id
            ],
            [
                'active' => $active,
                'configuration' => $configuration,
                'custom_content_state_id' => $customContentState->id
            ]
        );
    }

    private function setBrandCaptivePortalType($hotel_id, $restricted_portal)
    {
         BrandEprivacy::updateOrCreate(
             [
                'hotel_id' => $hotel_id
             ],
             [
                'restricted_portal' =>  $restricted_portal
             ]
         );
    }
}
