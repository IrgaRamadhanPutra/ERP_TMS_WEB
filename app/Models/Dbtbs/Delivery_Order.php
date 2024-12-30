<?php

namespace App\Models\dbtbs;

use Illuminate\Database\Eloquent\Model;

class Delivery_Order extends Model
{
    protected $connection = 'db_tbs';
     
    protected $table = 'entry_do_tbl';

    public $timestamps = false;
}
