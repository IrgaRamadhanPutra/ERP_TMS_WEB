<?php

namespace App\Models\Ekanban;

use Illuminate\Database\Eloquent\Model;

class Master_address_rm_log_tbl extends Model
{
    //
    protected $connection = 'ekanban';
    protected $table = 'master_address_rm_log_tbl';
    protected $fillable = [
        'id',
        'status_change',
        'chuter_address',
        'tbl_id',
        'create_user',
        'create_date',

    ];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
