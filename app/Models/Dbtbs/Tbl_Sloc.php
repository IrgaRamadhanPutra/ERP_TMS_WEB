<?php
namespace App\Models\dbtbs;

use Illuminate\Database\Eloquent\Model;

class Tbl_Sloc extends Model
{
    //
    protected $connection = 'db_tbs';
    protected $table = 'tbl_sloc';
    protected $fillable = [
        'id', 'plant', 'sloc', 'desc_sloc', 'created_date', 'created_by', 'updated_date','updated_by','voided','status'
    ];
    protected $primaryKey = 'id';
    public $timestamps = false;

}
