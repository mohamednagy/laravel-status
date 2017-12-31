<?php

namespace Nagy\LaravelStatus\Exceptions;

use InvalidArgumentException;

class StatusNotExists extends InvalidArgumentException
{
    public function __construct($tatus)
    {
        $message = $status. " doesn't exists";
        parent::__construct($message);
    }
}