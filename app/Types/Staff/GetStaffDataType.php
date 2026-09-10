<?php

namespace App\Types\Staff;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetStaffDataType extends BaseDataType
{
    /** @var int $brandId */
    protected $brandId;

    /** @var string $brandType */
    protected $brandType;

    /** @var int $page */
    protected $page;

    /** @var int $perPage */
    protected $perPage;

    /** @var string $sortField */
    protected $sortField;

    /** @var string ?sortOrder */
    protected $sortOrder;

    /**
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->brandId = $request->brand_id;

        $this->brandType = $request->get('brand_type');

        $this->page = $request->get('page') ?
            (int) $request->get('page') : 1;

        $this->perPage = $request->get('per_page') ?
            (int) $request->get('per_page') : 50;

        $this->sortField = $this->getSortField($request->get('sort_field'));

        $this->sortOrder = $request->get('sort_order') ?
            strtolower($request->get('sort_order')) :
            'asc';

        $this->createRules();
    }

    /**
     * @return bool
     */
    public function isInvalidData(): bool
    {
        $data = $this->getAttributes();
        $validator = Validator::make($data, $this->rules);

        return $validator->fails();
    }

    protected function createRules(): void
    {
        $this->rules = [
            'brandId'   => 'required|numeric|min:1',
            'brandType' => 'required|string|in:hotel,chain',
            'page'      => 'nullable|numeric|min:1',
            'perPage'   => 'nullable|numeric|min:1',
            'sortField' => 'nullable|string|in:nombre,email,hotels,role,activo',
            'sortOrder' => 'nullable|string|in:asc,desc',
        ];
    }

    private function getSortField($name): string
    {
        switch ($name) {
            case "nombre":
                return "nombre";
            case "email":
                return "email";
            case "hotelName":
                return "hotels";
            case "role":
                return "role";
            case "verified":
                return "activo";
            default:
                return "nombre";
        }
    }
}
