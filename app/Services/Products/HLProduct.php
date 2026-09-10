<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 14/03/2019
 * Time: 16:01
 */

namespace App\Services\Products;

use Illuminate\Http\Request;

interface HLProduct
{
    public function setDefaultConfiguration($brand_id, $active);
    public function setConfiguration($brand_id, Request $request);
    public function getConfiguration($brand_id);
}
