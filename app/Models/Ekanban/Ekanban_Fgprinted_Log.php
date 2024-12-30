<?php

namespace App\Models\Ekanban;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ekanban_Fgprinted_Log extends Model
{
    protected $connection = 'ekanban';
    protected $table = 'ekanban_fgprinted_log_tbl';
    protected $fillable = [
        'id',
        'ekanban_no',
        'item_code',
        'seq',
        'seq_tot',
        'mpname',
        'kanban_qty',
        'kanban_qty_tot',
        'doc_no_rec',
        'doc_no_send',
        'created_by',
        'creation_date',
        'msg_sap',
        'type_sap'
    ];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public static function getQueryWithJoin()
    {
        return self::select(
            'ekanban_fgprinted_log_tbl.ekanban_no',
            'ekanban_fgprinted_log_tbl.item_code',
            'ekanban_fgprinted_log_tbl.seq',
            'ekanban_fgprinted_log_tbl.seq_tot',
            'ekanban_fgprinted_log_tbl.mpname',
            'ekanban_fgprinted_log_tbl.kanban_qty',
            'ekanban_fgprinted_log_tbl.kanban_qty_tot',
            'ekanban_fgprinted_log_tbl.doc_no_rec',
            'ekanban_fgprinted_log_tbl.doc_no_send',
            'ekanban_fgprinted_log_tbl.created_by',
            'ekanban_fgprinted_log_tbl.creation_date',
            'ekanban_param_tbl.part_no',
            'ekanban_param_tbl.part_name'
        )
            ->leftJoin('ekanban_param_tbl', 'ekanban_fgprinted_log_tbl.ekanban_no', '=', 'ekanban_param_tbl.ekanban_no');
    }
}
