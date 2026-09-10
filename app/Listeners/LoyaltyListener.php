<?php

namespace App\Listeners;

use App\Product;
use App\BrandProduct;
use App\LoyaltyConfig;
use Carbon\Carbon;
use App\Services\Products\ProductService;
use App\Services\BrandProductService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LoyaltyListener
{
    private $productService;
    private $brandProductService;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ProductService $productService, BrandProductService $brandProductService)
    {
        $this->productService = $productService;
        $this->brandProductService = $brandProductService;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $event
     */
    public function subscribe($event)
    {
        $event->listen(
            'App\Events\BrandVisited',
            'App\Listeners\LoyaltyListener@sendLoyaltySummary'
        );
    }

    /**
     * @param $event
     * @throws \Exception
     */
    public function sendLoyaltySummary($event)
    {
        Log::debug("START SEND LOYALTY SUMMARY");
        $brand = json_decode($event->getBrand());
        $user = json_decode($event->getUser());

        $brandID = $brand->parent_id ? $brand->parent_id : $brand->id;
        $loyaltyConfiguration = LoyaltyConfig::where('brand_id', $brandID)->first();

        $product = $this->productService->getByName('loyalty');
        $loyaltyProduct = $this->brandProductService->get($brand->id, $product->id);

        if ($loyaltyConfiguration && $loyaltyConfiguration->summary_active && $loyaltyProduct && $loyaltyProduct->active) {
            Log::debug("Loyalty can be sent with this configuration");

            $loyaltyService = app('App\Services\Products\LoyaltyProductService');

            $loyaltyOffers = $loyaltyService->getOffers($brand, $user->lang);
            $offers = $loyaltyService->getSummaryOffers($loyaltyOffers);
            Log::debug("The summary offers", [$offers]);

            $brandTreatment = Arr::first($brand->brand_protocol, function ($value, $key) {
                return $value->service === 'emails';
            });
            Log::debug("The treatment", [$brandTreatment]);

            if ($offers) {
                Log::debug("ALL prepared, we can send event!");

                $sendLoyaltyOfferEmail =  [
                    "schema"         => "com.hotelinking/Emails/send_loyalty_summary_email/1.0.0",
                    "origin"         => "HotelinkingApi/" . $brand->id,
                    "originalEntity" => $event->getOriginalEntity("hotelinking"),
                    "eventSource"    => url()->current(),
                    "context"        => $event->getContext(),
                    "payload"        => [
                        "sendDate" => Carbon::now()->addDays($loyaltyConfiguration->summary_send_days)->addHours($loyaltyConfiguration->summary_send_hours)->toIso8601ZuluString(),
                        "user" => [
                            "id"  => $user->id,
                            "name"  => $user->name,
                            "guid"  => data_get($user, 'user_guid.guid', 'none'),
                            "email"  => $user->email,
                            "lang"  => $user->lang,
                            "gender"  => $user->gender,
                        ],
                        "brand" => [
                            "id" => $brand->id,
                            "hotelID" => $brand->hotel_id,
                            "hotelGuid" => $brand->brandInfo->hotel_guid->guid,
                            "chainID" => $brand->chain_id,
                            "name" => $brand->brandInfo->name,
                            "email" => $brand->brandInfo->sending_email ? $brand->brandInfo->sending_email : 'noreply@hotelinking.com',
                            "treatment" => data_get($brandTreatment, 'treatment', 'formal'),
                            "offers" => $offers
                        ]
                    ]
                ];
                $event->integration->sendRequest($sendLoyaltyOfferEmail, null);
            }
        }
    }
}
