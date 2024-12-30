<?php

namespace App\Models\Ekanban;

use Illuminate\Database\Eloquent\Model;

class Ekanban_nonlot_tbl_log extends Model
{
    //
    protected $connection = 'ekanban';
    protected $table = 'ekanban_nonlot_tbl_log';
    protected $fillable = [
        'id', 'table_id', 'action_name', 'new_qty', 'old_qty', 'action_user', 'action_date'
    ];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
