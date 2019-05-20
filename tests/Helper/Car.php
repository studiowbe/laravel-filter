<?php

namespace Studiow\Laravel\Filtering\Test\Helper;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = ['color', 'owner'];
}
