<?php

namespace App\Repositories\Account;

use App\Brand;
use Illuminate\Support\Facades\Log;
use Exception;

class AccountRepository implements AccountRepositoryInterface
{
    protected $brand;

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    public function get(int $accountId)
    {
        return $this->brand->where(["id" => $accountId])->whereNotNull("chain_id")->first();
    }

    public function getBrandIds(int $accountId)
    {
        $parentBrand = $this->brand->findOrFail($accountId);
        
        if ($parentBrand) {
            $brandIds = $parentBrand->children->pluck('id');
            return $brandIds;
        }
    }

    public function put(int $accountId, $accountRequest)
    {
        try {
            $brand = $this->brand->findOrFail($accountId);
           
            if ($brand->chain) {
                $brand->chain->update([
                    'nombre' => $accountRequest['account_name'],
                    'descripcion' => $accountRequest['description'],
                    'email_contacto' => $accountRequest['contact_email'],
                    'telefono_contacto' => $accountRequest['contact_tel'],
                    'stay_time' => $accountRequest['stay_time'],
                    'loyalty_min_visits' => $accountRequest['loyalty_min_visits']
                ]);
            }
        } catch (Exception $e) {
            Log::error("Error", [
                "message" => "Failed to update ChainInfo",
                "request" => $accountRequest,
                "error" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine()
            ]);
        }
    }
}
