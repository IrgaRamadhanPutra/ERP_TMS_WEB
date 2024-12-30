<?php

namespace App\Models\Ekanban;

use Illuminate\Database\Eloquent\Model;

class Ekanban_stock_limit_rm extends Model
{
    // Tentukan nama tabel
    protected $table = 'ekanban_stock_limit_rm';

    // Tentukan primary key
    protected $primaryKey = 'id';

    // Gunakan koneksi database khusus
    protected $connection = 'ekanban';
    protected $fillable = [
        'id',
        'chutter_address',
        'period',
        'itemcode',
        'part_number',
        'part_name',
        'part_type',
        'cust_code',
        'min',
        'max',
        'unit',
        'plant',
        'is_active',
        'action_name',
        'stock_type',
        'action_user',
        'action_date'
    ];
    public $timestamps = false;
}
