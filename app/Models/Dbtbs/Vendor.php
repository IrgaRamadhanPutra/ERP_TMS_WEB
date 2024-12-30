<?php

namespace App\Models\Dbtbs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Vendor extends Model
{

    protected $connection = 'db_tbs';
    protected $table = 'vendor';
    protected $fillable = ['VENDCODE', 'COMPANY', 'INDUSTRY', 'CONTACT', 'ADDRESS1', 'ADDRESS2', 'ADDRESS3', 'PHONE', 'FAX', 'HP', 'EMAIL'];
    protected $primaryKey = 'id'; //set id to primary key
    public $timestamps = false;

   
}
