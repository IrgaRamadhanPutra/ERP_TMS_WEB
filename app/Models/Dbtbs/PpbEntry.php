<?php

namespace App\Models\Dbtbs;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class PpbEntry extends Model
{
    //connect to Dbtbs
    protected $connection = 'db_tbs';
    //define Table
    protected $table = 'entry_ppb_tbl';
    protected $fillable = [
        'ppb_type', 'ppb_header_no' , 'ppb_detail_no' , 'ref_no' , 'period' ,
        'item_code' , 'descript' , 'state' , 'factor' , 'qty_ppb' , 'qty_unit_ppb' ,
        'qty_f_ppb' , 'qty_f_unit_ppb' , 'due_date' , 'ip_no' , 'ppat_no' , 'remark',
        'branch' , 'request_by' , 'written_by' , 'written_date' , 'printed_date',
        'posted_date' , 'finished_date' , 'voided_date' , 'data_status'
    ];
    protected $primaryKey = 'id_ppb';
    public $timestamps = false;

    public function getPPbNo()
    {
            $getperiod = Carbon::now()->format('Y');
            $get_sys_ppb_number = DB::connection('db_tbs')
            ->table('sys_number')
            ->select('contents')
            ->where('LABEL','=' , 'PPB NUMBER')
            ->first();

            $a = $get_sys_ppb_number->contents;
            // $b = '18100300';
            // $b = date('Ym').'18100300';
            $b = $getperiod .'18100300';

            if ($a==$b) {
                $cek_ppb_no = $get_sys_ppb_number->contents + 1;
                $cek_data_ppb = PpbEntry::where('ppb_header_no' , $cek_ppb_no)->select('ppb_header_no')->get();

                if ($cek_data_ppb->isEmpty()) {
                    $ppb_header_no = $cek_ppb_no;
                    return $ppb_header_no;
                }else {
                    do {
                        $cek_ppb_no++;
                        $cek_data_ppb = PpbEntry::where('ppb_header_no' , $cek_ppb_no)->select('ppb_header_no')->get();
                    } while (!$cek_data_ppb->isEmpty());
                    $ppb_header_no = $cek_ppb_no;
                    return $ppb_header_no;
                }
            }else{

                $cek_ppb_no = $b;
                $cek_ppb_no .= '1';
                $cek_data_ppb = PpbEntry::where('ppb_header_no' ,$cek_ppb_no)->select('ppb_header_no')->get();
                if ($cek_data_ppb->isEmpty()) {
                    $ppb_header_no = $cek_ppb_no;
                    return $ppb_header_no;
                }else{
                    do {
                        $cek_ppb_no++;
                        $cek_data_ppb = PpbEntry::where('ppb_header_no' , $cek_ppb_no)->select('ppb_header_no')->get();
                    } while (!$cek_data_ppb->isEmpty());
                    $ppb_header_no = $cek_ppb_no;
                    return $ppb_header_no;
                }
            }
    }

}
