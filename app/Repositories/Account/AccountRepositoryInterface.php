<?php

namespace App\Repositories\Account;

interface AccountRepositoryInterface
{
    public function get(int $accountId);

    public function getBrandIds(int $accountId);

    public function put(int $accountId, $accountRequest);
}
