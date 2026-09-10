<?php

use Illuminate\Database\Seeder;
use App\HotelRoom;
use App\BrandAccess;
use Illuminate\Support\Facades\Log;

class PopulateBrandAccess extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HotelRoom::chunk(50, function($hotelRooms)
        {

            foreach ($hotelRooms as $hotelRoom)
            {
                BrandAccess::firstOrCreate(
                    ['brand_id' => $hotelRoom->hotel->brand->id],
                    ['codes' => 
                        json_encode([
                            'room' => [
                                "codes" => explode(",", $hotelRoom->rooms)
                            ],
                            'guest' => [
                                "codes" => $hotelRoom->access_codes ? explode(",", $hotelRoom->access_codes) : null
                            ],
                            'premium' => [
                                "codes" => null,
                                "byParent" => false
                            ]
                        ])
                    ]
                );   
            }
        });
    }
}
