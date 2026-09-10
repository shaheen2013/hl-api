<?php

namespace App\Services\Offers;

use App\Exceptions\DeniedAccessResourceException;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Offers\OfferRepositoryInterface;
use Illuminate\Support\Facades\Log;

class OfferService
{
    protected $offerRepository;
    protected $brandRepository;

    public function __construct(OfferRepositoryInterface $offerRepository, BrandRepositoryInterface $brandRepository)
    {
        $this->offerRepository = $offerRepository;
        $this->brandRepository = $brandRepository;
    }

    public function show(int $id, string $lang)
    {
        return $this->offerRepository->show($id, $lang);
    }

    public function getOfferAndLangs(int $id)
    {
        return $this->offerRepository->getOfferAndLangs($id);
    }

    public function store($offer)
    {
        return $this->offerRepository->store($offer);
    }

    public function update($offer, int $id)
    {
        return $this->offerRepository->update($offer, $id);
    }

    public function delete(int $offer_id, int $brand_id)
    {
        // We first check if the Offer exists with its translations
        $this->offerRepository->getOfferAndLangs($offer_id);

        $brand = $this->brandRepository->get($brand_id);

        if ($this->offerRepository->offerIsFromChain($offer_id)) {
            $chain_id = $brand->parent->chain->id;
            $deleteResult = $this->offerRepository->delete($offer_id, $chain_id, 'cadena');
        } else {
            // Offer is from Hotel
            $hotel_id = $brand->hotel->id;

            if ($this->offerRepository->checkOfferFromHotel($offer_id, $hotel_id)) {
                $deleteResult = $this->offerRepository->delete($offer_id, $hotel_id, 'hotel');
            } else {
                throw new DeniedAccessResourceException(
                    'Denied access to Resource to the current Brand',
                    [
                        'offer_id' => $offer_id,
                        'hotel_id' => $hotel_id,
                        'brand_id' => $brand->id
                    ],
                    500
                );
            }
        }

        return $deleteResult;
    }
}
