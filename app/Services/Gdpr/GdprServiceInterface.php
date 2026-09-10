<?php

namespace App\Services\Gdpr;

use App\Types\Gdpr\GdprEventsDataType;

interface GdprServiceInterface
{
    public function get(int $brandId, string $userLanguage);
    public function updateOrCreate(int $hotelId, $gdprInfo);
    public function insertGdprEvents(GdprEventsDataType $data);
}
