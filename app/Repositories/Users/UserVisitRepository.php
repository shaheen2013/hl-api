<?php

namespace App\Repositories\Users;

use App\UserVisits;

class UserVisitRepository implements UserVisitRepositoryInterface
{
    /**
     * @var UserVisits
     */
    protected $userVisit;

    /**
     * UserVisitRepository constructor.
     * @param UserVisits $userVisit
     */
    public function __construct(UserVisits $userVisit)
    {
        $this->userVisit = $userVisit;
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function deleteByUserId(int $userId)
    {
        return $this->userVisit->where('user_id', $userId)->delete();
    }
}
