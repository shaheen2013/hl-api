<?php

namespace App\Services\Users;

use App\Repositories\Users\UserVisitRepositoryInterface;

class UserVisitService
{
    protected $userVisit;

    public function __construct(UserVisitRepositoryInterface $userVisit)
    {
        $this->userVisit = $userVisit;
    }

    public function deleteUserVisitByUserId(int $userId)
    {
        return $this->userVisit->deleteByUserId($userId);
    }
}
