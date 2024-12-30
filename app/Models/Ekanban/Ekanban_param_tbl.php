<?php

namespace App\Models\Ekanban;

use Illuminate\Database\Eloquent\Model;

class Ekanban_param_tbl extends Model
{
    //
    protected $connection = 'ekanban';
    protected $table = 'ekanban_param_tbl';
    protected $fillable = [
        'id',
        'production_line',
        'ekanban_no',
        'okanban_no',
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
        'part_status',
        'box_type',
        'created_by',
        'creation_date',
        'last_updated_by',
        'last_updated_date',
        'kanban_type',
        'bf_process',
        'sloc',
        'branch',
        'base_unit'
    ];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
