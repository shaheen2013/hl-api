<?php

namespace App\Repositories\Users;

interface UserVisitRepositoryInterface
{
    /**
     * @param int $userId
     * @return mixed
     */
    public function deleteByUserId(int $userId);
}
