<?php

namespace App\Console\Commands;

use App\Brand;
use App\HotelStaff;
use App\HotelStaffHotels;
use Illuminate\Console\Command;
use League\Csv\Reader;
use Illuminate\Support\Facades\Log;

class ImportStaffs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelinking:import-staffs {data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate staff users based on pasted csv string';

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
     * @return int
     */
    public function handle()
    {
        $csvData = $this->argument('data');

        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $csvData);
        rewind($stream);

        // Read the CSV data
        $csv = Reader::createFromStream($stream);
        $csv->setHeaderOffset(0);

        // Iterate over each record and insert it into the database
        foreach ($csv as $record) {
            try {
                $brand = Brand::findOrFail($record['brand_id']);
                $hotelId = $brand->hotel_id ? $brand->hotel_id : $brand->children[0]->hotel->id;
                $existingHotelStaff = HotelStaff::where([
                    "email" => $record['email'],
                    "activo" => 1,
                    "deleted" => 0
                ])->first();

                if (!$brand || !$hotelId || $existingHotelStaff) {
                    throw new \Exception("Bad data on import");
                }

                $hotelStaff = HotelStaff::insertGetId([
                    "nombre" => $record['name'] . ' ' . $record['surname'],
                    "email" => $record['email'],
                    "lang" => strtolower($record['lang']),
                    "id_role" => $record['group'],
                    "password" => sha1("Temporal123@"),
                    "activo" => 1,
                    "fecha_creado" => date("Y-m-d H:i:s")
                ]);

                HotelStaffHotels::insert([
                    "hotel_id" => $hotelId,
                    "hotel_staff_id" => $hotelStaff
                ]);
            } catch (\Exception $e) {
                Log::error("Unable to import user staff", [
                    "exception" => $e,
                    "record" => $record
                ]);
            }
        }

        fclose($stream);

        return 0;
    }
}
