<?php

namespace App\Models\Ekanban;

use Illuminate\Database\Eloquent\Model;

class Master_address_rm extends Model
{
    //
    protected $connection = 'ekanban';
    protected $table = 'master_address_rm';
    protected $fillable = [
        'id',
        'chuter_address',
        'itemcode',
        'part_no',
        'part_name',
        'part_type',
        'process_code',
        'cust_code',
        'supplier',
        'address_type',
        'plant',
        'status',
        'action_name',
        'created_date',
        'update_date',
        'update_by',
        'void_date'
    ];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
