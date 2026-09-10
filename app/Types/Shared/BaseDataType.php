<?php

namespace App\Types\Shared;

abstract class BaseDataType
{
    /**
     * @return bool
     */
    abstract public function isInvalidData(): bool;

    abstract protected function createRules(): void;

    /**
     * @return array
     */
    public function getAttributes()
    {
        return get_object_vars($this);
    }

    /**
     * @param $attribute
     * @return |null
     */
    public function __get($attribute)
    {
        return $this->{$attribute} ?? null;
    }

    /**
     * @param $attribute
     * @param $value
     */
    public function __set($attribute, $value)
    {
        $this->{$attribute} = $value;
    }
}
