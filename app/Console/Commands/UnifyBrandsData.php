<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BrandService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Hotel;
use App\Connection;
use App\UserHotel;
use App\ConnectionHistory;
use App\GdprHistory;
use App\Satisfaction;
use App\UserSurvey;
use App\UserShare;
use App\Booking;
use App\BookingValue;
use App\Email;
use App\BookingFunnel;
use App\OfertaReferralToken;
use App\PreOfertaToken;
use App\ReferrerToken;
use App\ReferrerUser;
use App\TrackingCookie;
use App\UsedPromocode;

class UnifyBrandsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelinking:unify-brand-data {sourceBrandId} {destinationBrandId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unifies the data of the first brand and dumps them into the second one';
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
     * @return int
     */
    public function handle()
    {
        $sourceBrandId = $this->argument('sourceBrandId');
        $destinationBrandId = $this->argument('destinationBrandId');

        try {
            $sourceBrand = $this->brandService->getBrand($sourceBrandId);
            $sourceHotel = Hotel::where('id', $sourceBrand->hotel_id)->firstOrFail();

            $destinationBrand = $this->brandService->getBrand($destinationBrandId);
            $destinationHotel = Hotel::where('id', $destinationBrand->hotel_id)->firstOrFail();
        } catch (\Exception $e) {
            Log::error("Brands not exists!", ["sourceBrandId" => $sourceBrandId, "destinationBrandId" => $destinationBrandId, "exception" => $e]);
            $this->getOutput()->error("The input brands of are not valid");
            return 0;
        }

        if ($sourceBrandId === $destinationBrandId) {
            $this->getOutput()->error("The origin and destination brand must be different");
            return 0;
        }

        if ($sourceBrand->parent_id && $sourceBrand->parent_id === $destinationBrand->parent_id) {
            $this->unifyUserBrand($sourceBrandId, $destinationBrandId);
            $this->unifyUserHotel($sourceHotel->id, $destinationHotel->id);
    
            $this->unifyConnection($sourceBrandId, $destinationBrandId);
            $this->unifyConnectionHistory($sourceHotel->id, $destinationHotel->id);
            $this->unifyGdprHistory($sourceHotel->id, $destinationHotel->id);
    
            $this->unifyUserSurvey($sourceBrandId, $destinationBrandId);
            $this->unifyUserSatisfaction($sourceHotel->id, $destinationHotel->id);
    
            $this->unifyUserShares($sourceHotel->id, $destinationHotel->id);
    
            $this->unifyBookingFunnel($sourceBrandId, $destinationBrandId);
            $this->unifyBooking($sourceHotel->id, $destinationHotel->id);
            $this->unifyBookingValue($sourceHotel->id, $destinationHotel->id);
    
            $this->unifyEmail($sourceBrandId, $destinationBrandId);
    
            $this->unifyOfferReferralToken($sourceHotel->id, $destinationHotel->id);
            $this->unifyPreOfferToken($sourceHotel->id, $destinationHotel->id);
            $this->unifyReferrerToken($sourceHotel->id, $destinationHotel->id);
            $this->unifyReferrerUser($sourceHotel->id, $destinationHotel->id);
            $this->unifyTrackingCookies($sourceHotel->id, $destinationHotel->id);
            $this->unifyUsedPromocode($sourceHotel->id, $destinationHotel->id);

            $this->getOutput()->success("The data of the two brands have been unified correctly");
            $this->getOutput()->warning("Remember to clear the Hotelinking Api and Hotelinking App cache as soon as possible.");
        } else {
            $this->getOutput()->error("You can only unify brands from the same chain");
            return 0;
        }
    }

    private function unifyUserBrand($sourceBrandId, $destinationBrandId)
    {
        $this->getOutput()->info("Updating new_user_brand table...");
        DB::statement('UPDATE IGNORE new_user_brand SET brand_id=' . $destinationBrandId .  ' where brand_id=' . $sourceBrandId);
    }

    private function unifyUserHotel($sourceHotelId, $destinationHotelId)
    {
        $this->getOutput()->info("Updating user_hotels table...");
        DB::statement('UPDATE IGNORE user_hotels SET id_hotel=' . $destinationHotelId .  ' where id_hotel=' . $sourceHotelId);
    }

    private function unifyConnection($sourceBrandId, $destinationBrandId)
    {
        $this->getOutput()->info("Updating new_connection table...");
        Connection::where('brand_id', $sourceBrandId)->update(['brand_id' => $destinationBrandId]);
    }

    private function unifyConnectionHistory($sourceHotelId, $destinationHotelId)
    {
        $this->getOutput()->info("Updating connection_history table...");
        ConnectionHistory::where('id_hotel', $sourceHotelId)->update(['id_hotel' => $destinationHotelId]);
    }

    private function unifyGdprHistory($sourceHotelId, $destinationHotelId)
    {
        $this->getOutput()->info("Updating gdpr_history table...");
        GdprHistory::where('hotel_id', $sourceHotelId)->update(['hotel_id' => $destinationHotelId]);
    }

    private function unifyUserSurvey($sourceBrandId, $destinationBrandId)
    {
        $this->getOutput()->info("Updating user_survey table...");
        UserSurvey::where('brand_id', $sourceBrandId)->update(['brand_id' => $destinationBrandId]);
    }

    private function unifyUserSatisfaction($sourceHotelId, $destinationHotelId)
    {
        $this->getOutput()->info("Updating user_satisfaction table...");
        Satisfaction::where('id_hotel', $sourceHotelId)->update(['id_hotel' => $destinationHotelId]);
    }

    private function unifyUserShares($sourceHotelId, $destinationHotelId)
    {
        $this->getOutput()->info("Updating user_shares table...");
        UserShare::where('id_hotel', $sourceHotelId)->update(['id_hotel' => $destinationHotelId]);
    }

    private function unifyBookingFunnel($sourceBrandId, $destinationBrandId)
    {
        $this->getOutput()->info("Updating new_booking_funnel table...");
        BookingFunnel::where('brand_id', $sourceBrandId)->update(['brand_id' => $destinationBrandId]);
    }

    private function unifyBooking($sourceHotelId, $destinationHotelId)
    {
        $this->getOutput()->info("Updating bookings table...");
        Booking::where('hotel_id', $sourceHotelId)->update(['hotel_id' => $destinationHotelId]);
    }

    private function unifyBookingValue($sourceHotelId, $destinationHotelId)
    {
        $this->getOutput()->info("Updating booking_value table...");
        BookingValue::where('id_hotel', $sourceHotelId)->update(['id_hotel' => $destinationHotelId]);
    }

    private function unifyEmail($sourceBrandId, $destinationBrandId)
    {
        $this->getOutput()->info("Updating email table...");
        Email::where('brand_id', $sourceBrandId)->update(['brand_id' => $destinationBrandId]);
    }

    private function unifyOfferReferralToken($sourceHotelId, $destinationHotelId)
    {
        $this->getOutput()->info("Updating oferta_referral_token table...");
        OfertaReferralToken::where('id_hotel', $sourceHotelId)->update(['id_hotel' => $destinationHotelId]);
    }

    private function unifyPreOfferToken($sourceHotelId, $destinationHotelId)
    {
        $this->getOutput()->info("Updating pre_oferta_token table...");
        PreOfertaToken::where('id_hotel', $sourceHotelId)->update(['id_hotel' => $destinationHotelId]);
    }

    private function unifyReferrerToken($sourceHotelId, $destinationHotelId)
    {
        $this->getOutput()->info("Updating referrer_tokens table...");
        ReferrerToken::where('id_hotel', $sourceHotelId)->update(['id_hotel' => $destinationHotelId]);
    }

    private function unifyReferrerUser($sourceHotelId, $destinationHotelId)
    {
        $this->getOutput()->info("Updating referrer_users table...");
        ReferrerUser::where('id_hotel', $sourceHotelId)->update(['id_hotel' => $destinationHotelId]);
    }

    private function unifyTrackingCookies($sourceHotelId, $destinationHotelId)
    {
        $this->getOutput()->info("Updating tracking_cookies table...");
        TrackingCookie::where('hotel_id', $sourceHotelId)->update(['hotel_id' => $destinationHotelId]);
    }

    private function unifyUsedPromocode($sourceHotelId, $destinationHotelId)
    {
        $this->getOutput()->info("Updating used_promocode table...");
        UsedPromocode::where('id_hotel', $sourceHotelId)->update(['id_hotel' => $destinationHotelId]);
    }
}
