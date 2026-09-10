<?php

namespace App\Http\Controllers;

use App\Exceptions\Products\ProductNotFoundException;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\Products\ProductsStatusResource;
use App\Services\BrandProductService;
use App\Services\Products\ProductService;
use Illuminate\Http\Request;

class BrandProductController extends Controller
{
    private $productService;
    private $brandProductService;

    public function __construct(ProductService $productService, BrandProductService $brandProductService)
    {
        $this->productService = $productService;
        $this->brandProductService = $brandProductService;
    }

    /**
     * @SWG\Get(
     *     path="/api/brands/{brand_id}/products/{product_id}/configuration",
     *     description="Gets the specified brand product configuration",
     *     tags={"Protocols"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="Brand id",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="product_id",
     *         in="path",
     *         type="integer",
     *         description="Product id",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     *
     * Update the specified resource in storage.
     *
     * @param $brand_id
     * @param $product_id
     * @return \Illuminate\Http\Response
     */
    public function getConfiguration($brand_id, $product_id)
    {
        $brandProductConfiguration = $this->brandProductService->getConfiguration($brand_id, $product_id);
        return response($brandProductConfiguration, 200);
    }

    /**
     * @SWG\Get(
     *     path="/api/products",
     *     description="Gets all products",
     *     tags={"Products"},
     *     @SWG\Response(
     *         response=200,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     *
     * List all products
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function getProducts(Request $request)
    {
        return ProductResource::collection($this->productService->getAll()->values());
    }

    /**
     * @SWG\Put(
     *     path="/api/brands/{brand_id}/products/{product_id}/configuration",
     *     description="Gets the specified brand product configuration",
     *     tags={"Protocols"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="Brand id",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="product_id",
     *         in="path",
     *         type="integer",
     *         description="Product id",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     *
     * Update the specified resource in storage.
     *
     * @param $brand_id
     * @param $product_id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function setConfiguration($brand_id, $product_id, Request $request)
    {
        $brandProductConfiguration = $this->brandProductService->updateConfiguration($brand_id, $product_id, $request);
        return response($brandProductConfiguration, 204);
    }

    public function setJsonConfig($brand_id, $product_id, Request $request)
    {
        $brandProduct = $this->brandProductService->setJsonConfig($brand_id, $product_id, $request);
        return response($brandProduct, 204);
    }

    public function getJsonConfig($brand_id, $product_id)
    {
        $brandProduct = $this->brandProductService->getJsonConfig($brand_id, $product_id);
        return response($brandProduct, 200);
    }

    /**
     * @SWG\Put(
     *     path="/api/brands/{brand_id}/products/{product_id}/{active}",
     *     description="Update the specified brand product, activating or deactivating the requested product",
     *     tags={"Protocols"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="Brand id",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="product_id",
     *         in="path",
     *         type="integer",
     *         description="Product id",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="active",
     *         in="path",
     *         type="integer",
     *         description="parameter that establish if the product will be enabled or disabled",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     *
     * Update the specified resource in storage.
     *
     * @param $brand_id
     * @param $product_id
     * @param $active
     * @return \Illuminate\Http\Response
     */

    public function activate($brand_id, $product_id, $active)
    {
        $brandProduct = $this->brandProductService->activate($brand_id, $product_id, $active);

        return response($brandProduct, 204);
    }

    public function getProductsStatus($brand_id)
    {
        return response(["data" => ProductsStatusResource::collection($this->brandProductService->getAllStatus($brand_id))], 200);
    }

    public function getProductStatus($brand_id, $product_id)
    {
        return response(["data" => ProductsStatusResource::collection([$this->brandProductService->getProductStatus($brand_id, $product_id)])], 200);
    }
}
