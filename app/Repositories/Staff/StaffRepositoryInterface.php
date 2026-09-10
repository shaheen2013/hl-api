<?php

namespace App\Repositories\Staff;

use App\HotelStaff;
use App\Types\Staff\GetStaffDataType;
use App\Types\Staff\DeleteStaffDataType;
use Illuminate\Pagination\LengthAwarePaginator;

interface StaffRepositoryInterface
{
    /**
     * @param GetStaffDataType $data
     * @return LengthAwarePaginator
     */
    public function get(GetStaffDataType $data): LengthAwarePaginator;

    /**
     * @param int $brandId
     * @param int $staffId
     * @return void
     */
    public function getBrandStaff(DeleteStaffDataType $data): HotelStaff;

    /**
     * @param int $hotelId
     * @return void
     */
    public function delete(int $staffId): void;
}
