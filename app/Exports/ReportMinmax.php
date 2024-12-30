<?php

namespace App\Exports;

use App\Models\Ekanban\Ekanban_stock_limit;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;

class ReportMinmax implements FromView, ShouldAutoSize
{
    protected $mpname;
    protected $date;

    public function __construct($mpname, $date)
    {
        $this->mpname = $mpname;
        $this->date = $date;
    }

    public function view(): View
    {
        $data = Ekanban_stock_limit::select(
            'ekanban_stock_limit.itemcode',
            DB::raw('MAX(ekanban_stock_limit.id) as id'),
            DB::raw('MAX(ekanban_stock_limit.chutter_address) as chutter_address'),
            DB::raw('MAX(ekanban_stock_limit.part_number) as part_number'),
            DB::raw('MAX(ekanban_stock_limit.part_name) as part_name'),
            DB::raw('MAX(ekanban_stock_limit.part_type) as part_type'),
            DB::raw('MAX(ekanban_stock_limit.min) as min'),
            DB::raw('MAX(ekanban_stock_limit.max) as max'),
            DB::raw('MAX(ekanban_fg_chuter_tbl.balance) as balance'),
            DB::raw('MAX(ekanban_param_tbl.qty_kanban) as lot')
        )
            ->leftJoin('ekanban_fg_chuter_tbl', function ($join) {
                $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_fg_chuter_tbl.item_code');
            })
            ->leftJoin('ekanban_param_tbl', 'ekanban_stock_limit.itemcode', '=', 'ekanban_param_tbl.item_code')
            ->where('ekanban_fg_chuter_tbl.mpname', '=', $this->mpname)
            ->where('ekanban_stock_limit.is_active', '1')
            ->groupBy('ekanban_stock_limit.itemcode')
            ->orderByDesc('ekanban_stock_limit.action_date')
            ->get();

        // dd($data);
        return view('tms.warehouse.min-max.ekspor.export_excel', ['data' => $data, 'mpname' => $this->mpname, 'date' => $this->date]);
    }

}
