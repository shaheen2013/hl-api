<?php

namespace App\Repositories\Clients;

use App\Brand;
use App\Types\Clients\SearchClientsDataType;
use App\Types\Clients\ClientsExportDataType;

interface ClientRepositoryInterface
{
    /**
     * @param int $brandId
     * @return mixed
     */
    public function get(Brand $brand, SearchClientsDataType $search);
    public function count(Brand $brand, SearchClientsDataType $search);

    /**
     * @param Brand $brand
     * @param ClientsExportDataType $search
     * @param array $headers
     * @return mixed
     */
    public function getCsv(Brand $brand, ClientsExportDataType $search, array $headers);
}
