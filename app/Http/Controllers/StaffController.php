<?php

namespace App\Http\Controllers;

use App\Services\Staffs\StaffService;
use App\Types\Staff\GetStaffDataType;
use App\Types\Staff\DeleteStaffDataType;
use App\Http\Resources\Staff\StaffResourcePaginate;
use App\Exceptions\InvalidRequestException;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    protected $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }


    public function get(GetStaffDataType $data)
    {
        if ($data->isInvalidData()) {
            throw new InvalidRequestException('Bad fields in request body', $data->getAttributes(), 400);
        }

        $staffs = new StaffResourcePaginate($this->staffService->get($data));

        return response($staffs, '200');
    }


    public function delete(DeleteStaffDataType $data)
    {
        if ($data->isInvalidData()) {
            throw new InvalidRequestException('Bad fields in request body', $data->getAttributes(), 400);
        }

        $this->staffService->delete($data);

        return response(null, 204);
    }
}
