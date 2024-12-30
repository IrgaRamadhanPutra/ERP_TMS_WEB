<?php

namespace App\Models\Ekanban;

use Illuminate\Database\Eloquent\Model;

class Ekanban_param_log_tbl extends Model
{
    protected $table = 'ekanban_param_log_tbl';
    protected $connection = 'ekanban';
    // Define the fillable fields
    protected $fillable = [
        'status_change',
        'ekanban_no',
        'item_code',
        'part_no',
        'part_name',
        'model',
        'customer',
        'lot_size',
        'qty_kanban',
        'lead_time',
        'line_code',
        'nextline_code',
        'line_code_pw',
        'nextline_code_pw',
        'cycle_time',
        'created_by',
        'creation_date',
        'last_updated_by',
        'last_updated_date',
        'part_status',
    ];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
