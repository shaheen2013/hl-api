<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 04/04/2019
 * Time: 16:18
 */

namespace App\Services\Emails;

interface HLEmailService
{
    public function send(array $payload);
}
