<?php

namespace App\Http\Controllers\Tms\Procurement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dbtbs\PpbEntry;
use DataTables;
use App\Models\Dbtbs\Formula;
use App\Models\Dbtbs\Item;
use App\Classes\ButtonBuilder As ButtonBuilder;
use Carbon\Carbon;
use DB;
use Auth;
use PDF;

class PpbController extends Controller
{
    public function index(Request $request)
    {
        $getDate = Carbon::now()->format('d/m/Y');
        $getDate1 =  Carbon::now()->format('Y/m');
        $getToday = Carbon::now();

        // $part_no = DB::connection('db_tbs')->table('entry_ppb_tbl')->join('item','entry_ppb_tbl.item_code','=','item.ITEMCODE')->select('item.ITEMCODE','item.PART_NO','item.DESCRIPT','item.DESCRIPT1','item.UNIT','entry_ppb_tbl.item_code')->get();
        $data_ppb = new PpbEntry();
        $get_no_ppb = $data_ppb->getPPbNo();
        return view('tms.procurement.ppb_entry.index', compact('getDate','getDate1','get_no_ppb','getToday'));

    }

    public function getppbDatatables(Request $request)
    {


        if ($request->ajax()) {
            // $data =  PpbEntry::where('voided_date','=', NULL)->get();

            $data =  PpbEntry::groupBy('ppb_header_no')
            ->select('id_ppb', 'ppb_header_no','written_date','posted_date','voided_date','ref_no','written_by','remark')
            ->where('voided_date','=', NULL)->get();

            // dd($data);
            // if ($get_data == 1) {
            //     $data->posted()->get();
            // }
            return Datatables::of($data)
                ->editColumn('written_date', function($data){
                    $data = Carbon::parse($data->written)->format('d/m/Y');
                    return $data;
                })->editColumn('posted_date', function($data){
                    if ($data->posted_date != null) {
                        return $data->posted_date;
                    } else {
                        return '//';
                    }
                })->editColumn('voided_date', function($data){
                    if ($data->voided != null) {
                        $get_data = Carbon::parse($data->voided_date)->format('d/m/Y');
                        return $get_data;
                    } else {
                        return '//';
                    }
                })->editColumn('posted_date', function($data){
                    if ($data->posted != null) {
                        $get_data = Carbon::parse($data->posted_date)->format('d/m/Y');
                        return $get_data;
                    } else {
                        return "//";
                    }
                })
                ->addColumn('action', function($data){
                    return view('tms.procurement.ppb_entry._action_datatables._actionppb', [
                            'model' => $data,
                            'url_print' => route('tms.procurement.ppb_entry_report_pdf_ppbdata', base64_encode($data->id_ppb))
                    ]);
                })->rawColumns(['action'])
                ->make(true);
            }
    }

    public function getPopUpChoiceDataDatatables(Request $request)
    {
        if ($request->ajax()) {
            $getItem = Item::get();
            return Datatables::of($getItem)->make(true);
        }
    }

    public function StoreDataPPB(Request $request)
    {
        // $request->validate([
        //     'period' => 'required',
        //     'item_code' => 'required',
        //     'descript' => 'required',
        //     'state' => 'required',
        //     'factor' => 'required',
        //     'qty_ppb' => 'required',
        //     'qty_unit_ppb' => 'required',
        //     'qty_f_ppb' => 'required',
        //     'qty_f_unit_ppb' => 'required',
        //     'remark' => 'required',
        //     'branch' => 'required'
        // ]);

        $data = new PpbEntry();
        $get_no_ppb = $data->getPPbNo();
        $dataitem = new Item();
        $itemcode = $data->$dataitem;
        $part_no = $data->$dataitem;
        $descript = $data->$dataitem;
        $fac_unit = $data->$dataitem;
        $unit = $data->$dataitem;
        $factor = $data->$dataitem;
        $types = $data->$dataitem;
        $getDate = Carbon::now()->format('Y/m/d');
        $getDate1 =  Carbon::now()->format('Y/m');
        $user_staff = Auth::user()->FullName;
        try {
           DB::beginTransaction();
           $data = PpbEntry::create([
               'ppb_header_no' => $get_no_ppb,
               'ref_no' => $request->ref_no,
               'ppb_type' => $request->ppb_type,
               'period' => $getDate1,
               'written_date' => $request->written_date,
               'descript' => $request->descript,
               'factor' => $request->factor ,
               'qty_ppb' => $request->qty_ppb ,
               'qty_unit_ppb' => $request->qty_unit_ppb,
               'qty_f_ppb' => $request->qty_f_ppb,
               'qty_f_unit_ppb' => $request->qty_f_unit_ppb,
               'due_date' => $request->due_date,
               'ip_no' => $request->ip_no,
               'ppat_no' => $request->ppat_no,
               'remark' => $request->remark,
               'branch' => $request->branch,
               'written_by' => $user_staff,
               'printed_date' => $getDate,
               'posted_date' => $getDate,
               'finished_date' => $getDate,
               'voided_date' => $getDate,
               'data_status' => $request->data_status,
               'item_code' => $itemcode,
               'part_no' => $part_no,
               'descript' => $descript,
               'fac_unit' => $fac_unit,
               'unit' => $unit,
               'types' => $types,
           ]);

           // INSERT LOG MTO
           date_default_timezone_set("Asia/Jakarta");
           $date = Carbon::now();
           $time = Carbon::now()->format('H:i:s');
           $dataitem = new Item();
           $item_code = $dataitem->item_code;
           $status = "ADD";
           $data = new PpbEntry();
           $get_no_ppb = $data->getPPbNo();
        //    $ppb_header_no = $get_no_ppb;
           $userstaff = Auth::user()->FullName;
           $data_status = $data['procurement'] . '/' . $data['get_no_ppb'] .'/'. 'Qty:'. ' ' . $data['qty_ppb'];
           DB::connection('db_tbs')->table('entry_ppb_log')->insert([
               'ppb_header_no'=> $get_no_ppb,
               'item_code' => $item_code,
               'date' => $date,
               'time' => $time,
               'status_change' => $status,
               'user' => $userstaff,
               'note' => $data_status
           ]);

        //    Insert Data Group PPB
           date_default_timezone_set('Asia/Jakarta');
           $date = Carbon::now();
           $time = Carbon::now()->format('H:i:s');
           $dataItem = new Item();
           $itemcode = $dataItem->ITEMCODE;
           $part_no = $dataItem->PART_NO;
           $descript = $dataItem->DESCRIPT;
           $descript1 = $dataItem->DESCRIPT1;
           $types = $dataItem->TYPES;
           $unit = $dataItem->UNIT;
           $fac_unit = $dataItem->FAC_UNIT;
           $factor = $dataItem->FACTOR;
        //    $ppb_no = $get_no_ppb;
           $data = new PpbEntry();
           $get_no_ppb = $data->getPPbNo();
           $user_staff = Auth::user()->FullName;
           DB::connection('db_tbs')->table('item')->insert([
                'ppb_no' => $get_no_ppb,
                'ITEMCODE' => $itemcode,
                'PART_NO'  => $part_no,
                'DESCRIPT'  => $descript,
                'DESCRIPT1'  => $descript1,
                'TYPES'  => $types,
                'UNIT'  => $unit,
                'FAC_UNIT'  => $fac_unit,
                'FACTOR'  => $factor
           ]);

           DB::commit();
           return response()->json([ 'success' => true ]);

        } catch (Exception $ex)  {
            DB::rollback();
            return redirect()->back();
        }

    }

    public function show_view_detail_ppb()
    {
        $PPBHeader = PpbEntry::where('id_ppb', $id)->first();
        $PPBHeaderNo = $PPBHeader->ppb_header_no;
        $PPBDetail = PpbEntry::select(
            'id_ppb','ppb_header_no',
             'ppb_detail_no' , 'item_code' , 'descript',
             'state' , 'factor' , 'qty_ppb' ,'qty_unit_ppb',
             'qty_f_ppb', 'qty_f_unit_ppb', 'due_date', 'ip_no',
             'ppat_no' , 'remark' , 'branch' , 'request_by' , 'written_by' ,
             'written_date' , 'printed_date' , 'posted_date' ,'finished_date',
             'voided_date', 'data_status')->where('ppb_header_no', '=' , $PPBHeaderNo)->get();

             $output = ['header' => $PPBHeader,
             'detail' => $PPBDetail ];

             return response()->json($output);
    }

    public function editDataPPB($id)
    {
        $data        = PpbEntry::where('id_ppb', $id)->first();
        $PPBDetail   = PpbEntry::select(
                        'id_ppb', 'ppb_header_no', 'ppb_detail_no', 'ppb_type', 'descript', 'ref_no',
                        'period', 'factor', 'item_code', 'state', 'qty_ppb','qty_unit_ppb','qty_f_ppb','qty_f_unit_ppb','due_date','ip_no',
                        'ppat_no','remark','branch','request_by','written_by','written_date', 'printed_date', 'posted_date','finished_date',
                        'voided_date','data_status')
                      ->where('id_ppb', '=', $id)
                      ->get();
        $output = [
            'header' => $data,
            'detail' => $PPBDetail

        ];
        return response()->json($output);
    }

    public function updatePpbEntry(Request $request, $id)
    {
        $data = PpbEntry::find($id);
        try {
            DB::beginTransaction();
            $data->update($request->all());

            // INSERT LOG PPB
            date_default_timezone_set("Asia/Jakarta");
            $date = Carbon::now();
            $time = Carbon::now()->format('H:i:s');
            $status_change = "EDIT";
            $ppb_no = $data['ppb_header_no'];
            $userstaff = Auth::user()->FullName;
            $note = $data['procurement'] . '/' . $data['ppb_header_no'] .'/'. 'Qty:'. ' ' . $data['qty_ppb'];
            DB::connection('db_tbs')->table('entry_ppb_log')->insert([
                'ppb_header_no' => $ppb_no,
                'ppb_detail_no' => $ppb_detail_no,
                'item_code' => $item_code,
                'date' => $date,
                'time' => $time,
                'status_change' => $status_change,
                'user' => $userstaff,
                'note' => $note
            ]);

            DB::commit();

            return response()->json([
                'success' => true
            ]);

        } catch (Exception  $ex) {
            DB::rollback();
            return redirect()->back();
        }

    }

    public function postedPPbData(Request $request, $id)
    {
        $data = MtoEntry::find($id);
        try {
            DB::beginTransaction();
            $get_posted =  $data['posted_date'];
            if ($get_posted != null) {
                //un-posted
                $data['posted_date'] = NULL;
                $data->update();

                // INSERT LOG UN-FINISHED
                date_default_timezone_set("Asia/Jakarta");
                $date = Carbon::now();
                $time = Carbon::now()->format('H:i:s');
                $status_change = "UN-FINISHED";
                $ppb_no = $data['ppb_header_no'];
                $userstaff = Auth::user()->FullName;
                $note = $data['procurement'] . '/' . $data['item_code'] .'/'. 'Qty:'. ' ' . $data['qty_ppb'];
                DB::connection('db_tbs')->table('entry_ppb_log')->insert([
                    'ppb_header_no' => $ppb_no,
                    'ppb_detail_no' => $ppb_detail_no,
                    'item_code' => $item_code,
                    'date' => $date,
                    'time' => $time,
                    'status_change' => $status_change,
                    'user' => $userstaff,
                    'note' => $request->note !== '' ? $request->note : $note
                ]);
            } else {
                // posted-mto
               $data['posted_date'] = Carbon::now();
               $data->save();

            // INSERT LOG POSTED MTO
               date_default_timezone_set("Asia/Jakarta");
               $date = Carbon::now();
               $time = Carbon::now()->format('H:i:s');
               $status_change = "POST";
               $ppb_header_no = $data['ppb_header_no'];
               $userstaff = Auth::user()->FullName;
               $note = $data['procurement'] . '/' . $data['item_code'] .'/'. 'Qty:'. ' ' . $data['qty_ppb'];
               DB::connection('db_tbs')->table('entry_ppb_log')->insert([
                   'ppb_header_no' => $ppb_header_no,
                   'ppb_detail_no' => $ppb_detail_no,
                   'item_code' => $item_code,
                   'date' => $date,
                   'time' => $time,
                   'status_change' => $status_change,
                   'user' => $userstaff,
                   'note' => $note
               ]);
            }

            DB::commit();
            return response()->json([
                'success' => true
            ]);
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back();
        }

    }

    public function viewLogPpbEntry($ppb_header_no)
    {
       $data =  DB::connection('db_tbs')
                    ->table('entry_ppb_log')
                    ->where('ppb_header_no','=', $ppb_header_no)
                    ->get();

       return response()->json($data);
    }

    public function show_view_stockId_ppb_no($id)
    {
    //    $ppb_get_no = PpbEntry::select('ppb_header_no')->where('ppb_header_no',$ppb_header_no)->first();
    //    return response()->json($ppb_get_no);
        $stockidPPB = Item::where('id',$id)->first();
        $itemcodeno = Item::select('id','ITEMCODE', 'TYPES',
        'DESCRIPT', 'DESCRIPT1','UNIT', 'FAC_UNIT' , 'FACTOR', 'PART_NO')->where('ITEMCODE' ,'=', $itemcodeno)->get();

        $output = ['header' => $stockidPPB, 'detail' => $itemcodeno];

        return response()->json($output);
    }


    public function reportPdfPpb($id)
    {
        $get_id = base64_decode($id);
        $data = PpbEntry::find($get_id);
        $data['printed_date'] = Carbon::now();
        $data->save();
        $pdf = PDF::loadView('tms.procurement.ppb_entry.report.report', ['data' => $data]);
        // return $pdf->download('report_mto'.'_'.  Carbon::now()->format('d/M/Y') . '.pdf');
        return $pdf->stream();
    }

    public function GetPPBNo(){

            $reference = Model_SysNo::select(DB::raw('concat(right(year(NOW()),2),DATE_FORMAT(NOW(),"%m")) as ref'))
            ->limit('1')
            ->get();
            $dono_sysno = Model_SysNo::where('label','DO NUMBER')
            ->select('contents')
            ->get();
            $a = substr($dono_sysno[0]->contents,0,4); //4 digit do no dari sys number ex: 2007 | 20 : 2 Digit Terakhir dari Tahun 2020 | 07 " 2 digit dari Bulan pada tahun tersebut
            $b = $reference[0]->ref; //4 digit dari datetime diambil sebagai validasi 4 digit no dari sys number ex: 2007 | 20 : 2 Digit Terakhir dari Tahun 2020 | 07 " 2 digit dari Bulan pada tahun tersebut
            if ($a == $b){ //Jika 4 digit dari sys_number & date sama
                //do no dari sysnumber ditambahkan 1
                 $cek_dono = $dono_sysno[0]->contents + 1;
                //kemudian di cek di tabel do hdr
                 $cek_dohdr = Model_DO_Entry::where('do_no',$cek_dono)
                 ->select(['do_no'])
                 ->get();
                if ($cek_dohdr->isEmpty()){ //jika result dari cek_dohdr null(kosong)
                    $do_no = $cek_dono;
                    return $do_no;
                }else{
                    do{
                        $cek_dono++;
                        $cek_dohdr = Model_DO_Entry::where('do_no',$cek_dono)
                        ->select(['do_no'])
                        ->get();
                    }while (!$cek_dohdr->isEmpty()); //lopping sampai tabel do hdr mendapatkan value null
                    $do_no = $cek_dono;
                    return $do_no;
                }
            } else {
                //buat baru do no dengan ref ditambah 0001 dan cek di do no
                $cek_dono  = $b;
                $cek_dono  .= '0001';
                $cek_dohdr = Model_DO_Entry::where('do_no',$cek_dono)
                ->select(['do_no'])
                ->get();
                if ($cek_dohdr->isEmpty()){
                    $do_no = $cek_dono;
                    return $do_no;
                }else{
                    do{
                        $cek_dono++;
                        $cek_dohdr = Model_DO_Entry::where('do_no',$cek_dono)
                        ->select(['do_no'])
                        ->get();
                    }while (!$cek_dohdr->isEmpty());
                    $do_no = $cek_dono;
                    return $do_no;
                }
            }
        }


    public function createdatastockid(Request $request)
    {
        // $request->validate([
        //     'PPB_NO' => 'required',
        //     'ITEMCODE' => 'required',
        //     'DESCRIPT' => 'required',
        //     'DESCRIPT1' => 'required',
        //     'PART_NO' => 'required',
        //     'UNIT' => 'required',
        //     'FAC_UNIT' => 'required',
        //     'FACTOR' => 'required',
        // ]);

        $data = new Item();
        $data = Item::create([
            'PPB_NO' => $data->ppb_header_no,
            'ITEMCODE' => $data->ITEMCODE,
            'DESCRIPT' => $data->DESCRIPT,
            'DESCRIPT1' => $data->DESCRIPT1,
            'PART_NO' => $data->PART_NO,
            'UNIT' => $data->UNIT,
            'FAC_UNIT' => $data->FAC_UNIT,
            'FACTOR' => $data->FACTOR,
        ]);

         // INSERT ITEM STOCK ID PPB
         date_default_timezone_set("Asia/Jakarta");
         $date = Carbon::now();
         $time = Carbon::now()->format('H:i:s');
         $getDate1 = Carbon::now()->format('Y/m/d');
        //  $data = DB::connection('db_tbs')->table('entry_ppb_tbl')->select('id_ppb','ppb_header_no',
        //  'due_date')->where('id_ppb','=', NULL)->get();
         $data = new PpbEntry();
         $get_no_ppb = $data->getPPbNo();
         $get_no_ppb = $data->ppb_header_no;
         $due_date = $data->due_date;
         DB::connection('db_tbs')->table('item')->insert([
             'PPB_NO' => $get_no_ppb,
             'ITEMCODE' => $ITEMCODE,
             'PART_NO' => $PART_NO,
             'DESCRIPT' => $DESCRIPT,
             'DESCRIPT1' => $DESCRIPT1,
             'DESCRIPT1' => $DESCRIPT1,
             'UNIT' => $UNIT,
             'FAC_UNIT' => $FAC_UNIT,
             'FACTOR' => $FACTOR,
             'due_date' => $due_date,
         ]);

         DB::commit();
         return response()->json([ 'success' => true ]);
    }

    public function voidedPpbData($id)
    {
        $data = PpbEntry::find($id);
        try {
            DB::beginTransaction();
            $data['voided_date'] = Carbon::now();
            $data->save();
            // INSERT LOG ACTIVITY
            date_default_timezone_set("Asia/Jakarta");
            $date = Carbon::now();
            $time = Carbon::now()->format('H:i:s');
            $status_change = "VOID";
            $ppb_header_no = $data['ppb_header_no'];
            $userstaff = Auth::user()->FullName;
            $note = $data['procurement'] . '/' . $data['item_code'] .'/'. 'Qty:'. ' ' . $data['qty_ppb'];
            DB::connection('db_tbs')->table('entry_ppb_log')->insert([
                'ppb_header_no' => $ppb_header_no,
                'ppb_detail_no' => $ppb_detail_no,
                'item_code' => $item_code,
                'date' => $date,
                'time' => $time,
                'status_change' => $status_change,
                'user' => $userstaff,
                'note' => $note
            ]);


            DB::commit();

            return response()->json([
                'success' => true,
            ]);
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back();
        }

    }

}
