<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BrandProtocol;
use DB;
use Illuminate\Support\Arr;

class ProtocolController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/api/protocols",
     *     description="Return all brands protocol",
     *     tags={"Protocols"},
     *     @SWG\Response(
     *         response=200,
     *         description="success",
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return BrandProtocol[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return BrandProtocol::all();
    }

    /**
     * @SWG\Post(
     *     path="/api/protocols",
     *     description="Store a new brand protocol",
     *     tags={"Protocols"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="query",
     *         type="integer",
     *         description="brand id",
     *         required=true,
     *     ),
     *      @SWG\Parameter(
     *         name="service",
     *         in="query",
     *         type="string",
     *         description="Service type to apply protocol",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="treatment",
     *         in="query",
     *         type="string",
     *         description="Treatment type to apply",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Protocols result",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!empty($request->brand_id) && !empty($request->service) && !empty($request->treatment)) {
            $protocol = BrandProtocol::create($request->all());

            return response($protocol, 201);
        }

        return response('Missing Data', 422);
    }

    /**
     * @SWG\Get(
     *     path="/api/protocols/{id}",
     *     description="Shows the required protocol",
     *     tags={"Protocols"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="BrandProtocol id",
     *         required=true,
     *     ),
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
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return BrandProtocol::findOrFail($id);
    }

    /**
     * @SWG\Put(
     *     path="/api/protocol/{id}",
     *     description="Update the specified brand protocol",
     *     tags={"Protocols"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="Protocol id",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="treatment",
     *         in="query",
     *         type="string",
     *         description="Treatment to update",
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
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (empty($request->treatment)) {
            return response('Missing Data', 422);
        }

        $protocol = BrandProtocol::findOrFail($id);
        $protocol->treatment = $request->treatment;
        $protocol->save();

        return response($protocol, 200);
    }

    /**
     * @SWG\Delete(
     *     path="/api/protocol/{id}",
     *     description="Remove the specified brand protocol",
     *     tags={"Protocols"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="Protocol id",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="success",
     *     )
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $protocol = BrandProtocol::findOrFail($id);
        $protocol->delete();

        return response('success', 204);
    }

    /**
     * @SWG\Get(
     *     path="/api/protocols/services",
     *     description="Shows options from enum field",
     *     tags={"Protocols"},
     *     @SWG\Response(
     *         response=200,
     *         description="success",
     *     )
     * )
     *
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getServicesAvailable()
    {
        $serviceEvents = DB::select('SHOW COLUMNS FROM ' . BrandProtocol::getTableName() . ' WHERE Field = ?', ['service']);
        $serviceEvents = (array)Arr::get($serviceEvents, '0', []);
        $enum = [];

        if (!empty($serviceEvents)) {
            preg_match("/^enum\(\'(.*)\'\)$/", $serviceEvents['Type'], $matches);
            $enum = explode("','", $matches[1]);
        }

        return response($enum, 200);
    }
}
