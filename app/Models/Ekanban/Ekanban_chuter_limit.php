<?php

namespace App\Models\Ekanban;

use Illuminate\Database\Eloquent\Model;

class Ekanban_chuter_limit extends Model
{
    //
    protected $connection = 'ekanban';
    protected $table = 'ekanban_chuter_limit';
    protected $fillable = [
        'id', 'chutter_address', 'period','kanban_no', 'itemcode', 'part_number', 'part_name', 'part_type', 'cust_code', 'min', 'max',
        'is_active', 'action_name', 'action_user', 'action_date'
    ];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
