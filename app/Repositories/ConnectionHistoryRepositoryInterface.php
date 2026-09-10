<?php

namespace App\Repositories;

interface ConnectionHistoryRepositoryInterface
{
    /**
     * @param int $userId
     * @return mixed
     */
    public function deleteByUserId(int $userId);
}
