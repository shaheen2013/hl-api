<?php

namespace App\Repositories\Users;

interface UserVoucherRepositoryInterface
{
    /**
     * @param int $userId
     * @return mixed
     */
    public function deleteByUserId(int $userId);
}
