<?php

namespace App\Models\Dbtbs;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $connection = 'ekanban';
    protected $table = 'ekanban_customermaster';
    public $timestamps = false;
}
