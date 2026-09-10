<?php

namespace App\Repositories\Gdpr;

interface GdprRepositoryInterface
{
    public function get(int $brandId, string $userLanguage);
    public function updateOrCreate(int $brandId, $gdprInfo);
    public function insertGdprEvents($events);
}
