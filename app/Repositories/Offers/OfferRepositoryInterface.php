<?php

namespace App\Repositories\Offers;

interface OfferRepositoryInterface
{
    public function show(int $id, string $lang);

    public function getOfferAndLangs(int $id);

    public function store($offer);

    public function update($offerParams, int $id);

    public function delete(int $offer_id, int $brand_id, string $brand_type);

    public function checkOfferFromHotel(int $offer_id, int $brand_id);

    public function offerIsFromChain(int $offer_id);
}
