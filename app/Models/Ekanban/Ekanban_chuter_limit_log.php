<?php

namespace App\Models\Ekanban;

use Illuminate\Database\Eloquent\Model;

class Ekanban_chuter_limit_log extends Model
{
    //
    protected $connection = 'ekanban';
    protected $table = 'ekanban_chuter_limit_log';
    protected $fillable = [
        'id', 'table_id', 'action_name', 'column_name', 'old_value', 'new_value', 'action_user', 'action_date'
    ];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
