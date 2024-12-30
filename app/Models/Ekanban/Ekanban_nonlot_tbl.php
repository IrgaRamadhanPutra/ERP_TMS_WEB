<?php

namespace App\Models\Ekanban;

use Illuminate\Database\Eloquent\Model;

class Ekanban_nonlot_tbl extends Model
{
    //
    protected $connection = 'ekanban';
    protected $table = 'ekanban_nonlot_tbl';
    protected $fillable = [
        'id_nonlot', 'itemcode', 'part_no', 'part_name', 'part_type', 'kanban_no', 'lot_qty', 'action_name',
        'created_date', 'created_by', 'upate_date', 'upadate_by', 'void'
    ];
    protected $primaryKey = 'id_nonlot';
    public $timestamps = false;
}
