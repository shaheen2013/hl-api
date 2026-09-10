<?php

namespace App\Services\Staffs;

use App\Repositories\Staff\StaffRepositoryInterface;
use Illuminate\Support\Facades\Log;

class StaffService
{
    protected $staffRepository;

    public function __construct(StaffRepositoryInterface $staffRepository)
    {
        $this->staffRepository = $staffRepository;
    }

    public function get($brandId)
    {
        return $this->staffRepository->get($brandId);
    }

    public function delete($data)
    {
        $staff = $this->staffRepository->getBrandStaff($data);

        if ($staff) {
            $this->staffRepository->delete($data->staffId);
        }
    }
}
