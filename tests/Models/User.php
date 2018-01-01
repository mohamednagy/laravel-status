<?php

namespace Nagy\LaravelStatus\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Nagy\LaravelStatus\Traits\HasStatus;

class User extends Model
{

    use HasStatus;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'status'];

    protected static $status = [
        0 => 'pending',
        1 => 'approved',
        2 => 'rejected',
        3 => 'snake_case'
    ];

    protected static $status_column = 'status';
}
