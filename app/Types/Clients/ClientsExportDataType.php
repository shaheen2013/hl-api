<?php

namespace App\Types\Clients;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ClientsExportDataType extends BaseDataType
{
    /** @var array $emailList */
    protected $emails;

    /** @var string $searchText */
    protected $searchText;

    /** @var string $searchBy */
    protected $searchBy;

    /** @var string $from */
    protected $from;

    /** @var string $to */
    protected $to;

    /** @var boolean $hosted */
    protected $hosted;

    /** @var boolean $hosted */
    protected $subscribed;

    /** @var string $sortField */
    protected $sortField;

    /** @var string ?sortOrder */
    protected $sortOrder;

    /** @var boolean $phone_active */
    protected $phone_active;

    /** @var boolean $phone_active */
    protected $commercial_profile;

    /**
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->emails = $request->get('emails');

        $this->searchText = $request->get('search_text');

        $this->searchBy = $request->get('search_by');

        $this->from = $request->get('from');

        $this->to = $request->get('to');

        $this->hosted = $request->get('hosted') ?
            (bool) $request->get('hosted') : null;

        $this->subscribed = $request->get('subscribed') ?
            (bool) $request->get('subscribed') : null;

        $this->sortField = $this->getSortField($request->get('sort_field'));

        $this->sortOrder = $request->get('sort_order') ?
            $request->get('sort_order') :
            'desc';

        $this->phone_active = $request->get('phone_active');

        $this->commercial_profile = $request->get('commercial_profile');

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
            'emails'        => 'required|array|min:1',
            'emails.*'      => 'required|email',
            'searchText'    => 'nullable|string',
            'searchBy'      => 'nullable|string|in:name,email,res_id,pms_id,access_code',
            'from'          => 'nullable|date',
            'to'            => 'nullable|date',
            'hosted'        => 'nullable|bool',
            'subscribed'    => 'nullable|bool',
            'sortField'     => 'nullable|string|in:users.nombre,users.email,hoteles.hotelName,new_user_brand.date,new_user_brand.unsubscribed,check_in,check_out,channel,agency,new_user_brand.user_id',
            'sortOrder'     => 'nullable|string|in:asc,desc',
            'phone_active'  => 'nullable|bool',
            'commercial_profile'  => 'nullable|bool',
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
