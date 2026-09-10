<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 04/04/2019
 * Time: 08:39
 */

namespace App\Http\Controllers;

use App\Lang;
use App\Language;

class LangController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/api/langs",
     *     description="Gets all configurable langs",
     *     tags={"Languages"},
     *     @SWG\Parameter(
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
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Lang::where('content', 1)->get();
    }

    public function getAllLanguages()
    {
        return Language::all();
    }
}
