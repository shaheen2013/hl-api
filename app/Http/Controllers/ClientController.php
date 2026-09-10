<?php

namespace App\Http\Controllers;

use App\Services\Clients\ClientService;
use App\Services\BrandService;
use App\Http\Resources\Clients\ClientsResource;
use App\Http\Resources\Clients\ClientDetailResource;
use App\Jobs\ProcessClientsExport;
use App\Types\Clients\SearchClientsDataType;
use App\Types\Clients\GetClientDetailDataType;
use App\Types\Clients\ClientsExportDataType;
use App\Exceptions\Clients\InvalidClientsRequestDataException;
use App\Exceptions\Clients\InvalidClientsExportRequestDataException;
use App\Exceptions\InvalidRequestException;

class ClientController extends Controller
{
    protected $clientService;
    protected $brandService;

    public function __construct(ClientService $clientService, BrandService $brandService)
    {
        $this->clientService = $clientService;
        $this->brandService = $brandService;
    }

    /**
     * @param $brand_id
     * @param SearchClientsDataType $searchData
     * @return \Illuminate\Http\JsonResponse
     * @throws InvalidClientsRequestDataException
     */
    public function getBrandClients($brand_id, SearchClientsDataType $searchData)
    {
        if ($searchData->isInvalidData()) {
            throw new InvalidClientsRequestDataException('Bad fields in request body', $searchData->getAttributes(), 400);
        }

        $brand = $this->brandService->getBrand($brand_id);
        $clientData = $this->clientService->getBrandClients($brand, $searchData);
        $serviceResponse = new ClientsResource($brand, data_get($clientData, 'data'), data_get($clientData, 'total'), $searchData->page, $searchData->perPage);

        return $serviceResponse->response()->setStatusCode(200);
    }

    /**
     * @param $brand_id
     * @param SearchClientsDataType $searchData
     * @return \Illuminate\Http\JsonResponse
     * @throws InvalidClientsRequestDataException
     */
    public function getClientDetail(GetClientDetailDataType $clientDataType)
    {
        if ($clientDataType->isInvalidData()) {
            throw new InvalidRequestException('Bad fields in request body', $clientDataType->getErrors()->toArray(), 400);
        }

        $clientDetail = $this->clientService->getClientDetail($clientDataType);
        $serviceResponse = new ClientDetailResource(data_get($clientDetail, 'property_visits'), data_get($clientDetail, 'chain_visits'), data_get($clientDetail, 'timezone'), data_get($clientDetail, 'data'));

        return $serviceResponse->response()->setStatusCode(200);
    }


    /**
     * @param $brand_id
     * @param ClientsExportDataType $data
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     * @throws InvalidClientsExportRequestDataException
     */
    public function queueReport($brand_id, ClientsExportDataType $data)
    {
        if ($data->isInvalidData()) {
            throw new InvalidClientsExportRequestDataException('Bad fields in request body', $data->getAttributes(), 400);
        }

        ProcessClientsExport::dispatch($brand_id, $data)->onQueue('reports');

        return response(null, 202);
    }

    /**
     * @param int $brandId
     * @param int $clientId
     */
    public function deleteClient(int $brandId, int $clientId)
    {
        $this->clientService
            ->delete($brandId, $clientId);

        return response(null, 200);
    }
}
