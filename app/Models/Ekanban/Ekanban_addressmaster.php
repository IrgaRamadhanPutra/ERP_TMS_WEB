<?php

namespace App\Models\Ekanban;

use Illuminate\Database\Eloquent\Model;

class Ekanban_addressmaster extends Model
{
    protected $connection = 'ekanban';
    protected $table = 'ekanban_addressmaster';
    protected $fillable = [
        'id', 'AddressCode', 'AddressName', 'WHCode', 'ActiveCls', 'Dimensi_Panjang', 'Dimensi_Lebar', 'Dimensi_Tinggi', 'chutter_type', 'RegisterUser', 'RegisterDate', 'UpdateUser', 'UpdateDate'
    ];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
