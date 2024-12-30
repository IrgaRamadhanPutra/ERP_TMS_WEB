<?php

namespace App\Models\Dbtbs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sys_Number extends Model
{

    protected $connection = 'db_tbs';
    protected $table = 'sys_number';
    public $timestamps = false;

   
}
