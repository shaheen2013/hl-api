<?php

namespace App\Repositories\Users;

interface UserSatisfactionRepositoryInterface
{
    /**
     * @param int $userId
     * @return mixed
     */
    public function deleteByUserId(int $userId);
}
