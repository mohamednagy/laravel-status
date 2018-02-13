<?php

namespace Nagy\LaravelStatus;

use Illuminate\Database\Eloquent\Builder;
use Nagy\LaravelStatus\Exceptions\StatusNotExists;

trait HasStatus
{
    public static function bootHasStatus()
    {
        $status_column = (new static)->getStatusColumnName();
        foreach (static::$status as $statusValue => $statusStr) {
            Builder::macro('set'.studly_case($statusStr), function() use ($statusValue, $status_column){
                return Builder::update([$status_column => $statusValue]);
            });

            Builder::macro('only'.studly_case($statusStr), function() use ($statusValue, $status_column){
                return Builder::where($status_column, $statusValue);
            });
        }
    }

    public function is($status)
    {
        $statusValue = $this->getStatusValue($status);
        return $this->{$this->getStatusColumnName()} == $statusValue;

    }

    public function scopeOnlyHasStatus($query, $status)
    {
        $statusValue = $this->getStatusValue($status);
        return $query->where($this->getStatusColumnName(), $statusValue);
    }

    public function setStatus($status)
    {
        $statusValue = $this->getStatusValue($status);
        return $this->update([$this->getStatusColumnName() => $statusValue]);
    }

    public function getStatusValue($status)
    {
        $status = snake_case($status);
        $statusValue = array_search($status, static::$status);
        if ($statusValue !== false) {
            return $statusValue;
        }
        
        throw new StatusNotExists($status);
    }

    public function getStatusColumnName()
    {
        return static::$status_column ?? 'status';
    }

    public function __call($method, $args)
    {
        if (starts_with($method, 'is')) {
            $status = substr($method, 2);
            return $this->is($status);
        }

        if (starts_with($method, 'set')) {
            $status = substr($method, 3);
            return $this->setStatus($status);
        }

        if (starts_with($method, 'only')) {
            $status = substr($method, 4);
            return parent::__call('onlyHasStatus', [$status]);
        }

        return parent::__call($method, $args);
    }
    
}