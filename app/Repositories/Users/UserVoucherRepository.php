<?php

namespace App\Repositories\Users;

use App\UserVoucher;

class UserVoucherRepository implements UserVoucherRepositoryInterface
{
    /**
     * @var UserVoucher
     */
    protected $userVoucher;

    /**
     * UserVoucherRepository constructor.
     * @param UserVoucher $userVoucher
     */
    public function __construct(UserVoucher $userVoucher)
    {
        $this->userVoucher = $userVoucher;
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function deleteByUserId(int $userId)
    {
        return $this->userVoucher->where('id_usuario', $userId)->delete();
    }
}
