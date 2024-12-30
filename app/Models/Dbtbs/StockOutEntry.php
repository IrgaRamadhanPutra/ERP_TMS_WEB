<?php

namespace App\Models\Dbtbs;

use Illuminate\Database\Eloquent\Model;
use DB;
use DataTables;
// use Illuminate\Database\Eloquent\SoftDeletes;
class StockOutEntry extends Model
{
    // use SoftDeletes;
    protected $connection = 'db_tbs';
    protected $table = 'entry_stock_out_tbl';
    protected $fillable = [ 
        'out_no','refs_no','itemcode','part_no','descript','fac_unit','fac_qty','factor',
        'unit','quantity','period','staff','types','operator','written','printed','voided',
        'posted','remark_header','remark_detail','po_no','branch'
      ];
    protected $primaryKey = 'id_stout';
    public $timestamps = false;
    
    // protected $dates = ['deleted_at'];

    public function getStoutNo()
    {
        $get_sys_number1 = DB::connection('db_tbs')
                     ->table('sys_number')
                     ->select('contents')
                     ->where('LABEL','=', 'STOCK OUT NUMBER')
                     ->first();


        $cek_stoutno = $get_sys_number1->contents + 1;
        $cek_data_stockout = StockOutEntry::where('out_no', $cek_stoutno)->select('out_no')->get();

        if ($cek_data_stockout->isEmpty()) {
                $out_no = $cek_stoutno;
                return $out_no;
        } else {
            do {
                $cek_stoutno++;
                $cek_data_stockout = StockOutEntry::where('out_no', $cek_stoutno)->select('out_no')->get();
            } while (!$cek_data_stockout->isEmpty());
                $out_no = $cek_stoutno;
                return $out_no;
        }
    }
    // public function validationItemSame()
    // {
    //             $cek = DB::connection('db_tbs')
    //                  ->table('entry_stock_out_tbl')
    //                  ->select('itemcode')->first();
    //            $cek_itemcode =  DB::connection('db_tbs')
    //                             ->table('entry_stock_out_tbl')
    //                             ->select('itemcode')
    //                             ->where('itemcode', $cek->itemcode)->get();
    //             if ($cek_itemcode) {
    //                   # code...
    //               }  
    // }
    // public function editDetail($out_no)
    // {
    //     // $StoutHeader   = StockOutEntry::where('out_no', $id)->first();
    //     // $stoutDetail = $StoutHeader->out_no;
    //     $StoutEditDetail   = StockOutEntry::select('id_stout',
    //         'out_no','refs_no','itemcode','part_no','descript','fac_unit','fac_qty','factor',
    //         'unit','quantity','period','staff','types','operator','written','printed','voided',
    //         'posted','remark','po_no')   
    //     ->where('out_no', '=', $out_no)
    //     ->first();
  
    //     return $StoutEditDetail;
    //     // return Datatables::of($StoutEditDetail)
    //     //     ->addColumn('action', function($data){
    //     //         return '<a href="/warehouse/stock_out_entry/'. $data->id_stout .'/stock_out_entry_edit" class="btn btn-info btn-xs">tes</a>';
    //     //     })->rawColumns(['action'])
    //     //     ->make(true);     
    // }


    
}
