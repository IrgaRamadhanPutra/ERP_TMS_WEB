<?php

namespace App\Models\Ekanban;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ekanban_stock_limit extends Model
{
    protected $connection = 'ekanban';
    protected $table = 'ekanban_stock_limit';
    protected $fillable = [
        'id', 'chutter_address', 'period', 'itemcode', 'part_number', 'part_name', 'part_type', 'cust_code', 'min', 'max','plant',
        'is_active', 'action_name','stock_type', 'action_user', 'action_date'
    ];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public static function Getdatastocklimit()
    {
        return DB::connection('ekanban')
            ->table('ekanban_stock_limit')
            ->select(
                'itemcode',
                DB::raw('MAX(id) as id'),
                DB::raw('MAX(chutter_address) as chutter_address'),
                DB::raw('MAX(part_number) as part_number'),
                DB::raw('MAX(part_name) as part_name'),
                DB::raw('MAX(part_type) as part_type'),
                DB::raw('MAX(min) as min'),
                DB::raw('MAX(max) as max')
            )
            // ->where('is_active', '1')
            ->groupBy('itemcode');
    }

    public static function getDetailedStockLimitData($cutOffdate, $mpname)
{
    return self::select(
        'ekanban_stock_limit.itemcode',
        DB::raw('MAX(ekanban_stock_limit.chutter_address) as chutter_address'),
        DB::raw('MAX(ekanban_stock_limit.part_number) as part_number'),
        DB::raw('MAX(ekanban_stock_limit.part_name) as part_name'),
        DB::raw('MAX(ekanban_stock_limit.part_type) as part_type'),
        DB::raw('MAX(ekanban_stock_limit.min) as min'),
        DB::raw('MAX(ekanban_stock_limit.max) as max'),
        DB::raw('MAX(ekanban_fg_tbl.balance) as balance'),
        DB::raw('COALESCE(SUM(ekanban_fgin_tbl.qty), 0) as jumlah_qty'),
        // Use a default or derived column if 'total_fgout_qty' is not available in 'ekanban_admout_tbl'
        DB::raw('COALESCE(SUM(ekanban_admout_tbl.total_fgout_qty), 0) as total_fgout_qty'),
        DB::raw('COALESCE(SUM(ekanban_admout_tbl.ekanban_qty), 0) as total_admout_qty'),
        DB::raw('COALESCE(SUM(ekanban_admout_tbl.total_fgout_qty), 0) - COALESCE(SUM(ekanban_admout_tbl.ekanban_qty), 0) as total_staging'),
        DB::raw('MAX(ekanban_fg_tbl.balance) + COALESCE(SUM(ekanban_fgin_tbl.qty), 0) as total')
    )
    ->leftJoin('ekanban_fg_tbl', function ($join) {
        $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_fg_tbl.item_code');
    })
    ->leftJoin('ekanban_fgin_tbl', function ($join) use ($cutOffdate) {
        $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_fgin_tbl.item_code')
            ->whereNull('ekanban_fgin_tbl.chutter_address')
            ->whereNull('ekanban_fgin_tbl.last_updated_by')
            ->where('ekanban_fgin_tbl.creation_date', '>', $cutOffdate);
    })
    ->leftJoin('ekanban_admout_tbl', function ($join) {
        $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_admout_tbl.item_code')
             ->on('ekanban_admout_tbl.kanban_no', '=', 'ekanban_admout_tbl.ekanban_no')
             ->on('ekanban_admout_tbl.seq', '=', 'ekanban_admout_tbl.seq');
    })
    ->where('ekanban_fg_tbl.mpname', '=', $mpname)
    ->where('ekanban_stock_limit.is_active', '1')
    ->where(function ($query) {
        $query->where('ekanban_stock_limit.chutter_address', 'LIKE', 'O%')
            ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'M%')
            ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'N%')
            ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'L%')
            ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'K%');
    })
    ->groupBy('ekanban_stock_limit.itemcode')
    ->havingRaw('MAX(ekanban_fg_tbl.balance) < MAX(ekanban_stock_limit.min) OR MAX(ekanban_fg_tbl.balance) > MAX(ekanban_stock_limit.max)')
    ->orderBy('ekanban_stock_limit.chutter_address', 'asc')
    ->get()
    ->toArray();
}


}
