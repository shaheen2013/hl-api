<?php

namespace App\Services\Users;

use App\Repositories\Users\UserSatisfactionRepositoryInterface;

class UserSatisfactionService
{
    protected $userSatisfaction;

    public function __construct(UserSatisfactionRepositoryInterface $userSatisfaction)
    {
        $this->userSatisfaction = $userSatisfaction;
    }

    public function deleteUserSatisfactionByUserId(int $userId)
    {
        return $this->userSatisfaction->deleteByUserId($userId);
    }
}
