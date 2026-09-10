<?php

namespace App\Services\Clients;

use App\Types\Clients\GetClientDetailDataType;
use App\Repositories\Bookings\BookingFunnelRepositoryInterface;
use App\Repositories\Brand\BrandRepository;
use App\Repositories\Clients\ClientRepositoryInterface;
use App\Repositories\SocialMedia\SocialMediaShareRepositoryInterface;
use App\Repositories\Visits\UserBrandRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;

class ClientService
{
    protected $clientRepository;
    protected $bookingFunnelRepository;
    protected $socialMediaShareRepository;
    protected $userBrandRepository;
    protected $brandRepository;

    /**
     * @param ClientRepositoryInterface $clientRepositoryInterface
     * @throws \Exception
     */
    public function __construct(
        ClientRepositoryInterface $clientRepositoryInterface,
        BookingFunnelRepositoryInterface $bookingFunnelRepository,
        SocialMediaShareRepositoryInterface $socialMediaShareRepository,
        UserBrandRepositoryInterface $userBrandRepository,
        BrandRepository $brandRepository
    ) {
        $this->clientRepository = $clientRepositoryInterface;
        $this->bookingFunnelRepository = $bookingFunnelRepository;
        $this->socialMediaShareRepository = $socialMediaShareRepository;
        $this->userBrandRepository = $userBrandRepository;
        $this->brandRepository = $brandRepository;
    }

    /**
     * @param $brandId
     * @return \Illuminate\Cache\CacheManager|mixed
     * @throws \Exception
     */
    public function getBrandClients($brand, $search)
    {
        $clientsCount = $this->clientRepository->count($brand, $search);
        $clients = $this->clientRepository->get($brand, $search);
        return ["total" => $clientsCount, "data" => $clients];
    }

    public function getClientDetail(GetClientDetailDataType $clientDataType)
    {
        $brand = $this->brandRepository->get($clientDataType->brandId);
        $clientDetail = $this->clientRepository->getClientDetail($brand, $clientDataType->userId);
        $visits = Arr::flatten(Arr::pluck($clientDetail, 'visits'));

        $visitsToProperty = array_filter($visits, function ($val) use ($brand) {
            return $val->brand_id === $brand->id;
        });

        $visitsToOtherProperties = array_filter($visits, function ($val) use ($visitsToProperty, $brand) {
            $overlappedDate = Arr::first($visitsToProperty, function ($propertyVisit) use ($val) {
                return ($propertyVisit->check_in <= $val->check_in && $propertyVisit->check_out >= $val->check_in) ||
                    ($val->check_in <= $propertyVisit->check_in && $val->check_out >= $propertyVisit->check_in);
            });

            return $val->brand_id !== $brand->id && !$overlappedDate;
        });

        return [
            "data"              => $clientDetail,
            "property_visits"   => count($visitsToProperty),
            "chain_visits"      => count($visitsToOtherProperties) + count($visitsToProperty),
            "timezone"          => $brand->hotel ?
                $brand->hotel->timeZone->time_zone :
                $brand->children[0]->hotel->timeZone->time_zone
        ];
    }

    /**
     * @param $brandId
     * @return \Illuminate\Cache\CacheManager|mixed
     * @throws \Exception
     */
    public function getBrandReportClients($brand, $search, $headers)
    {
        return $this->clientRepository->getCsv($brand, $search, $headers);
    }

    /**
     * @param int $brandId
     * @param int $clientId
     */
    public function delete(int $brandId, int $clientId)
    {
        $brand = $this->brandRepository->get($brandId);
        $children = $brand->children;
        Cache::tags(['clients_' . $brandId])->flush();

        if ($children->count()) {
            $userBrands = $this->userBrandRepository
                ->getUserBrands($clientId, $children->pluck('id')->toArray());

            foreach ($userBrands as $userBrand) {
                Cache::tags(['clients_' . $userBrand->brand_id])->flush();
                $this->bookingFunnelRepository
                    ->deleteByUserBrand($userBrand->brand_id, $userBrand->user_id);

                $this->socialMediaShareRepository
                    ->deleteByUserBrandId($userBrand->id);

                $this->userBrandRepository
                    ->delete($userBrand->id);
            }
        } else {
            $userBrand = $this->userBrandRepository
                ->get($brandId, $clientId);

            $this->bookingFunnelRepository
                ->deleteByUserBrand($brandId, $clientId);

            $this->socialMediaShareRepository
                ->deleteByUserBrandId($userBrand->id);

            $this->userBrandRepository
                ->delete($userBrand->id);
        }
    }
}
