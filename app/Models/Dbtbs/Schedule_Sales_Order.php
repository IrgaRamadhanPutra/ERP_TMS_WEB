<?php

namespace App\Models\dbtbs;

use Illuminate\Database\Eloquent\Model;

class Schedule_Sales_Order extends Model
{
    protected $connection = 'db_tbs';
    protected $table = 'entry_sso_tbl';
    public $timestamps = false;
}
