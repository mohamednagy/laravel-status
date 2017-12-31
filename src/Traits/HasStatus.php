<?php

namespace Nagy\LaravelStatus\Traits;

use Nagy\LaravelStatus\Exceptions\StatusNotExists;

trait HasStatus
{

    public function is($status)
    {
        $statusValue = $this->getStatusValue($status);
        return $this->{self::$status_column} == $statusValue;

    }

    public function scopeOnlyHasStatus($query, $status)
    {
        $statusValue = $this->getStatusValue($status);
        return $query->where(self::$status_column, $statusValue);
    }

    public function setStatus($status)
    {
        $statusValue = $this->getStatusValue($status);
        $this->update([self::$status_column => $statusValue]);
    }

    public function getStatusValue($status)
    {
        $statusValue = array_search($status, static::$status);
        if ($statusValue === false) {
            throw new StatusNotExists($status);
        }

        return $statusValue;
    }

    public function __call($method, $args)
    {
        if (starts_with($method, 'is')) {
            $status = strtolower(substr($method, 2));
            return $this->is($status);
        }

        if (starts_with($method, 'set')) {
            $status = strtolower(substr($method, 3));
            return $this->setStatus($status);
        }

        if (starts_with($method, 'only')) {
            $status = strtolower(substr($method, 4));
            return parent::__call('onlyHasStatus', [$status]);
        }

        return parent::__call($method, $args);
    }
    
}