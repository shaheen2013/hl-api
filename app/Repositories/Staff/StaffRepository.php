<?php

namespace App\Repositories\Staff;

use App\HotelStaff;
use App\Types\Staff\GetStaffDataType;
use App\Types\Staff\DeleteStaffDataType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;

class StaffRepository implements StaffRepositoryInterface
{
    /**
     * @var HotelStaff
     */
    protected $staff;


    /**
     * StaffRepository constructor.
     * @param HotelStaff $user
     */
    public function __construct(HotelStaff $staff)
    {
        $this->staff = $staff;
    }

    /**
     * @param int $hotelId
     * @return mixed
     */
    public function get(GetStaffDataType $data): LengthAwarePaginator
    {

        $staffs = $this->staff
            ->select("hotel_staff.id", "hotel_staff_hotels.hotel_id", "hotel_staff.nombre", "hotel_staff.email", DB::raw("GROUP_CONCAT(hoteles.hotelName) as hotels"), "hotel_staff.activo", "hotel_staff_roles.role_en as role")
            ->join("hotel_staff_roles", "hotel_staff_roles.id", "hotel_staff.id_role")
            ->join("hotel_staff_hotels", "hotel_staff_hotels.hotel_staff_id", "hotel_staff.id")
            ->join("hoteles", "hoteles.id", "hotel_staff_hotels.hotel_id")
            ->join("brands", "hoteles.id", "brands.hotel_id")
            ->where(["deleted" => 0]);

        $staffs = $this->addStaffBrandCondition($staffs, $data->brandType, $data->brandId)
            ->groupBy("hotel_staff.id")
            ->orderBy($data->sortField, $data->sortOrder)
            ->paginate($data->perPage);

        return $staffs;
    }

    /**
     * @param int $staffId
     * @return mixed
     */
    public function getBrandStaff(DeleteStaffDataType $data): HotelStaff
    {
        $staff = $this->staff
            ->join("hotel_staff_hotels", "hotel_staff_hotels.hotel_staff_id", "hotel_staff.id")
            ->join("hoteles", "hoteles.id", "hotel_staff_hotels.hotel_id")
            ->join("brands", "hoteles.id", "brands.hotel_id");

        return $this->addStaffBrandCondition($staff, $data->brandType, $data->brandId)
            ->where(["hotel_staff.id" => $data->staffId])
            ->first();
    }

    /**
     * @param int $staffId
     * @return mixed
     */
    public function delete(int $staffId): void
    {
        $this->staff->where(['id' => $staffId])->update(["deleted" => 1]);
    }

    private function addStaffBrandCondition($sql, $brandType, $brandId)
    {
        if ($brandType == "chain") {
            $sql = $sql->where(["brands.parent_id" => $brandId]);
        } else {
            $sql = $sql->where(["brands.id" => $brandId]);
        }

        return $sql;
    }
}
