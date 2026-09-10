<?php

namespace App\Services\Users;

use App\UserBrand;
use App\Repositories\Visits\UserBrandRepositoryInterface;

class UserBrandService
{
    protected $userBrandRepository;

    /**
     * @param UserBrandRepositoryInterface $userBrandRepository
     */
    public function __construct(UserBrandRepositoryInterface $userBrandRepository)
    {
        $this->userBrandRepository = $userBrandRepository;
    }

    public function get(int $brandId, int $userId): UserBrand
    {
        return $this->userBrandRepository->get($brandId, $userId);
    }


    public function updateGeneration(int $brandId, int $userId, string $generation)
    {
        $userBrand = $this->get($brandId, $userId);
        return $this->userBrandRepository->updateGeneration($brandId, $userBrand, $generation);
    }
}
