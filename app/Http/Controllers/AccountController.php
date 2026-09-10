<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Exceptions\InvalidRequestException;
use App\Types\Accounts\PutAccountInfoDataType;
use App\Services\AccountService;

class AccountController extends Controller
{
    private $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function updateInfo(PutAccountInfoDataType $request, int $id)
    {
        if (!$request->isInvalidData()) {
            return $this->accountService->updateAccountInfo($id, $request->account);
        }

        Log::error('AccountController', ['message' => 'Invalid payload or request', 'id' => $id, 'request' => $request->account]);
        
        throw new InvalidRequestException('Invalid payload or request', [
            'inputs' => $request->all(),
            'errors' => $request->getValidatorMessages()
        ], 400);
    }
}
