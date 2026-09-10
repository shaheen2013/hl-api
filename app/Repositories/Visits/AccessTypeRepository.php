<?php

namespace App\Repositories\Visits;

use App\AccessType;

class AccessTypeRepository implements AccessTypeRepositoryInterface
{
    /**
     * @var AccessType
     */
    protected $accessType;

    /**
     * AccessTypeRepository constructor.
     * @param AccessType $accessType
     */
    public function __construct(AccessType $accessType)
    {
        $this->accessType = $accessType;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function get($name)
    {
        return $this->accessType->where(['name' => $name])->first();
    }
}
