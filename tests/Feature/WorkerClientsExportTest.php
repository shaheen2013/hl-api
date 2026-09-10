<?php

namespace Tests\Feature;

use App\Hotel;
use App\User;
use App\Brand;
use App\UserBrand;
use App\Visit;
use App\Device;
use App\Connection;
use Carbon\Carbon;
use App\Jobs\ProcessClientsExport;
use App\Types\Clients\ClientsExportDataType;
use App\Services\Clients\ClientService;
use App\Services\BrandService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class WorkerClientsExportTest extends TestCase
{
    public function testCreateCsvClientReportSuccessfully()
    {
        Storage::fake('s3');

        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $user = factory(User::class)->create();

        $userBrand = factory(UserBrand::class)->create([
            'brand_id'  => $brand->id,
            'user_id'   => $user->id,
            'date'      => Carbon::yesterday()
        ]);

        $visit = factory(Visit::class)->create([
            'user_brand_id' => $userBrand->id,
            'brand_id'      => $userBrand->brand_id,
            'user_id'       => $userBrand->user_id,
        ]);

        $device = factory(Device::class)->create();

        $connection = factory(Connection::class)->create([
            'brand_id'  => $brand->id,
            'visit_id'  => $visit->id,
            'device_id' => $device->id
        ]);

        $request = new \Illuminate\Http\Request();
        $request->replace([
            'emails' => ['test@testing.com']
        ]);
        $data = new ClientsExportDataType($request);


        $job = new ProcessClientsExport($brand->id, $data);
        $job->handle(app('App\Services\Clients\ClientService'), app('App\Services\BrandService'));

        $this->assertNotEquals([], Storage::disk('s3')->allFiles());
    }
}
