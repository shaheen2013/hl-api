<?php

namespace App\Types\Clients;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchClientsDataType extends BaseDataType
{
    /** @var string $searchText */
    protected $searchText;

    /** @var string $from */
    protected $from;

    /** @var string $to */
    protected $to;

    /** @var boolean $hosted */
    protected $hosted;

    /** @var boolean $subscribed */
    protected $subscribed;

    // Pagination and ordering attributes
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
        $this->searchText = $request->get('search_text');

        $this->searchBy = $request->get('search_by');

        $this->from = $request->get('from');

        $this->to = $request->get('to');

        $this->hosted = $request->get('hosted') ?
            (bool) $request->get('hosted') : null;

        $this->subscribed = $request->get('subscribed') ?
            (bool) $request->get('subscribed') : null;

        $this->page = $request->get('page') ?
            (int) $request->get('page') : 1;

        $this->perPage = $request->get('per_page') ?
            (int) $request->get('per_page') : 50;

        $this->sortField = $this->getSortField($request->get('sort_field'));

        $this->sortOrder = $request->get('sort_order') ?
            $request->get('sort_order') :
            'desc';

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
            'searchText'    => 'nullable|string',
            'searchBy'      => 'nullable|string|in:name,email,res_id,pms_id,access_code',
            'from'          => 'nullable|date',
            'to'            => 'nullable|date',
            'hosted'        => 'nullable|bool',
            'subscribed'    => 'nullable|bool',
            'page'          => 'nullable|numeric|min:1',
            'perPage'       => 'nullable|numeric|min:1',
            'sortField'     => 'nullable|string|in:users.nombre,users.email,hoteles.hotelName,new_user_brand.date,new_user_brand.unsubscribed,check_in,check_out,channel,agency,new_user_brand.user_id',
            'sortOrder'     => 'nullable|string|in:asc,desc',
        ];
    }

    private function getSortField($name): string
    {
        switch ($name) {
            case "client_name":
                return "users.nombre";
            case "client_email":
                return "users.email";
            case "brand_name":
                return "hoteles.hotelName";
            case "first_login":
                return "new_user_brand.date";
            case "unsubscribed":
                return "new_user_brand.unsubscribed";
            case "check_in":
                return "check_in";
            case "check_out":
                return "check_out";
            case "channel":
                return "channel";
            case "agency":
                return "agency";
            default:
                return "new_user_brand.user_id";
        }
    }
}
