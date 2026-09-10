<?php

namespace App\Services\Users;

use App\Repositories\Users\UserVoucherRepositoryInterface;

class UserVoucherService
{
    protected $userVoucher;

    public function __construct(UserVoucherRepositoryInterface $userVoucher)
    {
        $this->userVoucher = $userVoucher;
    }

    public function deleteUserVoucherByUserId(int $userId)
    {
        return $this->userVoucher->deleteByUserId($userId);
    }
}
