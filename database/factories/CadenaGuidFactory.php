<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 05/02/2018
 * Time: 17:05
 */

$factory->define(App\CadenaGuid::class, function () {
    if (function_exists('com_create_guid') === true){
        $guid = trim(com_create_guid(), '{}');
    }
    else{
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        $guid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    return [
        'guid'=>$guid,
    ];
});