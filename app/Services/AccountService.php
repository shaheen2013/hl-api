<?php

namespace App\Services;

use App\Events\Account\AccountUpdated;
use Illuminate\Support\Facades\Log;
use App\Repositories\Account\AccountRepositoryInterface;
use App\Repositories\Brand\BrandRepositoryInterface;
use Exception;

class AccountService
{
    protected $accountRepository;
    protected $brandRepository;

    public function __construct(AccountRepositoryInterface $accountRepository, BrandRepositoryInterface $brandRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->brandRepository = $brandRepository;
    }

    public function getAccountInfo($accountId)
    {
        return $this->accountRepository->get($accountId);
    }

    public function getBrandIds($accountId)
    {
        return $this->accountRepository->getBrandIds($accountId);
    }

    public function updateAccountInfo(int $id, $accountRequest)
    {
        try {
            // Update all the information of the chain in the chain table
            $chainUpdate = $this->accountRepository->put($id, $accountRequest);
            // Update the background color of the chain in the brands table
            $brandUpdate = $this->brandRepository->updateBrandInfo($id, $accountRequest);
            $this->sendAccountUpdatedEvent($id);
        } catch (Exception $e) {
            Log::error("Error", ["message" => "Failed to update ChainInfo", "request" => $accountRequest, "error" => $e->getMessage(), "file" => $e->getFile(), "line" => $e->getLine()]);
        }

        return [
            "chain" => $chainUpdate,
        ];
    }

    public function sendAccountUpdatedEvent(int $accountId)
    {
        $accountInfo = [
            "account" => $this->getAccountInfo($accountId),
            "brands" => $this->getBrandIds($accountId)
        ];
        
        event(new AccountUpdated($accountInfo));
    }
}
