<?php

namespace App\Models\Dbtbs;

use Illuminate\Database\Eloquent\Model;

class Entry_Log_Transaction_Sap extends Model
{
    //
    protected $connection = 'db_tbs';
    protected $table = 'entry_log_transaction_sap';
    protected $fillable = [
        'id', 'created_date', 'module', 'log_message'
    ];
    protected $primaryKey = 'id';
    public $timestamps = false;

}
