<?php

namespace App\Http\Controllers\tms\warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dbtbs\StockOutEntry;
use App\Models\Dbtbs\Item;
use DataTables;
use Carbon\Carbon;
use Auth;
use Datetime;
use PDF;
use DB;
use Illuminate\Support\Str;
class StockOutEntryController extends Controller
{
    public function indexStockOutEntry()
    {
        $getDate = Carbon::now()->format('d/m/Y');
        $getDate1 =  Carbon::now()->format('Y/m');
        $data_stout = new StockOutEntry();
        $get_stoutno = $data_stout->getStoutNo();
        $stout = \DB::connection('db_tbs')->table('entry_stock_out_tbl')->selectRaw('itemcode')->count();
        $stout1 = \DB::connection('db_tbs')->table('entry_stock_out_tbl')->get();
        $stoutCount = \DB::connection('db_tbs')->table('entry_stock_out_tbl')->count();
        return view('tms.warehouse.stock-out-entry.index', compact('get_stoutno','getDate','getDate1','stout','stoutCount','stout1'));
    }

    public function GetStoutdatatablesDashboard(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::connection('db_tbs')
                ->table('entry_stock_out_tbl')
                ->select('id_stout','out_no','written','posted', 'refs_no','staff',
                'remark_header','po_no','descript')
                ->where('voided','=', NULL)
                ->groupByRaw('out_no')
                ->get();

            return Datatables::of($data)
            ->editcolumn('posted', function($data){
                $dt = $data->posted;
                if ($dt != NULL) {
                    return Carbon::parse($dt)->format('d/m/Y');
                } else {
                    return "//";
                }
            })->editColumn('written', function($data){
                $dt = $data->written;
                if ($dt != NULL) {
                    return Carbon::parse($dt)->format('d/m/Y');
                } else {
                    return '//';
                }
            })
            ->rawColumns(['action'])
            ->editColumn('action', function($data){
                return view('tms.warehouse.stock-out-entry._actiondatatables._action',[
                    'model' => $data,
                    'url_print' => route('tms.warehouse.stock_out_entry_report', base64_encode($data->out_no))
                ]);
            })
            ->make(true);        
        }
    }

    public function getChoiceDataItemDatatables(Request $request)
    {
        if ($request->ajax()) {
            $data = Item::query();
            return Datatables::of($data)->make(true);
        }
    }

    public function SysWarehouse(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::connection('db_tbs')
                    ->table('sys_warehouse')
                    ->select('warehouse_id','descript')
                    ->get();
            return Datatables::of($data)->make(true);        
        }
    }

    public function storeStockOut(Request $request)
    {
   
        if ($request->ajax()) {
            $rules = [
                'refs_no'=> 'required',
                 'types'=> 'required',
                 'itemcode.*'=> 'required',
                //  'descript.*'=> 'required',
                 'fac_qty.*'=> 'required'
            ];
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'error'=> $validator->errors()->all()
                ]);
            }
            
            $getDate = Carbon::now()->format('d/m/Y');
            $data_stout = new StockOutEntry();
            $get_stoutno = $data_stout->getStoutNo();
            $date = new Datetime;
            $getDate1 =  Carbon::now()->format('Y/m');
            $getSession = Auth::user()->FullName;
            $getItemcode = $request->itemcode;
            $outno = $get_stoutno;
            $refs_no = $request->refs_no;
            $itemcode = $request->itemcode;
            $part_no = $request->part_no;
            $descript = $request->descript;
            $fac_unit = $request->fac_unit;
            $fac_qty = $request->fac_qty;
            $factor = $request->factor;
            $unit = $request->unit;
            $quantity = $request->quantity;
            $period = Carbon::now()->format('Y-m');
            $staff = $getSession;
            $remark_header = $request->remark_header;
            $remark_detail = $request->remark_detail;
            $types = $request->types;
            $written = Carbon::now();
            // $remark = $request->remark;
            $po_no = $request->po_no;
            $data = $request->all();
            $periodCheck = $data['period'];
            // $check = StockOutEntry::where('itemcode', $itemcode)->get();
            $convert = Carbon::createFromFormat('Y/m',  $periodCheck)->format('Y-m-d');
            if (count($request->input('itemcode')) > 0) {
             $check = DB::connection('db_tbs')->table('stclose')->where('DATE', $convert)->get();
             $msg = "Sudah closing tidak bisa entry";
             if ($check->isEmpty()) {
                foreach ($request->input('itemcode') as $item => $value) {
                    $data = array(
                        'out_no'=> $get_stoutno,
                        'remark_header'=>  Str::upper($remark_header),
                        'refs_no'=> Str::upper($refs_no),
                        'itemcode'=>$itemcode[$item],
                        'part_no'=>$part_no[$item],
                        'descript'=>$descript[$item],
                        'fac_unit'=>$fac_unit[$item],
                        'fac_qty'=>$fac_qty[$item],
                        'factor'=>$factor[$item],
                        'unit'=>$unit[$item],
                        'quantity'=>$quantity[$item],
                        'period'=> $period,
                        'staff'=>  $getSession,
                        'types'=>$types,
                        'operator'=> $getSession,
                        'written'=> $written,
                        'remark_detail'=>$remark_detail[$item],
                        'po_no'=>$po_no,
                        'branch'=> $request->branch
                    );
    
                    $get_data = StockOutEntry::create($data);  
                }
             } else {
                return response()->json([
                    'errors'=> $msg,
                    'checkdata'=> $check
                ]);
             }
            
        }
        $get_count = $request->all();
        $get_itemcode = count($get_count['itemcode']);
        $get_quantity = $get_count['quantity'];
        $jumlah = 0;
        for ($i=0; $i < count($get_quantity) ; $i++) { 
            $kalkulasi = $jumlah += $get_count['quantity'][$i];
        }
        date_default_timezone_set("Asia/Jakarta");
        $date = Carbon::now();
        $time = Carbon::now()->format('H:i:s');
        $status = "ADD";
        $out_no =  $get_stoutno;
        $userstaff = Auth::user()->FullName;
        $note = 'Wh :' . $get_data['types'] . '/' . 'Item:'.  $get_itemcode .'/'. 'Qty:'. $kalkulasi;
        DB::connection('db_tbs')->table('entry_stock_out_log_tbl')->insert([
            'out_no' => $out_no, 
            'date' => $date,
            'time' => $time,
            'status_change' => $status,
            'user' => $userstaff,
            'note' => $note 
        ]);
        
    
        return response()->json([
           'status' => 'Successfully add new stock out'
       ]);
        

    }





}

public function showViewStout($id)
{
    $StoutHeader   = StockOutEntry::where('id_stout', $id)->first();
    $StoutHeaderNo = $StoutHeader->out_no;
    $StoutDetail   = StockOutEntry::select('id_stout',
        'out_no','refs_no','itemcode','part_no','descript','fac_unit','fac_qty','factor',
        'unit','quantity','period','staff','types','operator','written','printed','voided',
        'posted','remark_header','remark_detail','po_no')   
    ->where('out_no', '=', $StoutHeaderNo)
    ->get();
    $StoutCount   = StockOutEntry::select('id_stout',
        'out_no','refs_no','itemcode','part_no','descript','fac_unit','fac_qty','factor',
        'unit','quantity','period','staff','types','operator','written','printed','voided',
        'posted','remark_header','remark_detail','po_no')   
    ->where('out_no', '=', $StoutHeaderNo)
    ->count();            
    $output = [
        'header' => $StoutHeader,
        'detail' => $StoutDetail,
        'count'=> $StoutCount
    ];

    return response()->json($output);
}

public function editStoutEntry(Request $request, $id)
{
    $StoutHeader   = StockOutEntry::where('id_stout', $id)->first();
    // if (!empty($StoutHeader->out_no)) {
        $StoutHeaderNo = $StoutHeader->out_no;
        $get_first_data   = StockOutEntry::select('id_stout',
        'out_no','refs_no','itemcode','part_no','descript','fac_unit','fac_qty','factor',
        'unit','quantity','period','staff','types','operator','written','printed','voided',
        'posted','remark_detail','po_no')   
    ->where('out_no', '=', $StoutHeaderNo)
    ->first();
    $StoutCount   = StockOutEntry::select('id_stout',
        'out_no','refs_no','itemcode','part_no','descript','fac_unit','fac_qty','factor',
        'unit','quantity','period','staff','types','operator','written','printed','voided',
        'posted','remark_header','remark_detail','po_no')   
    ->where('out_no', '=', $StoutHeaderNo)
    ->count();    
    

    $output = [
        // 'out_no'=> $StoutHeaderNo,
        'header' => $StoutHeader,
        'detail' => $get_first_data,
        'count'=> $StoutCount
    ];


        echo json_encode($output);
        exit;
    // } else {
    //     $StoutHeader2   = StockOutEntry::where('id_stout', $id)->first();
    //     $out_no = $StoutHeader2->out_no;
    //     return response()->json([
    //         'msg'=> 'data sudah terhapus',
    //         'out_no'=> $out_no
    //     ]);
    // }

   

}

public function dashboardEditDetail($out_no)
{
     $data   = StockOutEntry::select('id_stout',
        'out_no','refs_no','itemcode','part_no','descript','fac_unit','fac_qty','factor',
        'unit','quantity','period','staff','types','operator','written','printed','voided',
        'posted','remark_detail','po_no')   
    ->where('out_no','=', $out_no)
    // ->where('deleted_at', NULL)
    ->get();
    return Datatables::of($data)
    ->toJson(true);
}


public function updateStout(Request $request, $id)
{
  
    $get_modelstout = StockOutEntry::find($id);
    $get_itemcode = $get_modelstout->itemcode;
    $get_outno = $get_modelstout->out_no;
    $get_refs_no = $get_modelstout->refs_no;
    $get_types = $get_modelstout->types;
    $get_branch = $get_modelstout->branch;
    $get_quantity = $get_modelstout->quantity;
    $getSession = Auth::user()->FullName;
    $refs_no = $request->refs_no;
    $itemcode = $request->itemcode;
    $part_no = $request->part_no;
    $descript = $request->descript;
    $fac_unit = $request->fac_unit;
    $fac_qty = $request->fac_qty;
    $factor = $request->factor;
    $unit = $request->unit;
    $quantity = $request->quantity;
    $period = Carbon::now()->format('Y-m');
    $types = $request->types;
    $written = Carbon::now();
    $remark_header = $request->remark_header;
    $remark_detail = $request->remark_detail;
    $po_no = $request->po_no;
    $branch = $request->branch; 
    $get_data = $request->all();
    // $get_item  = DB::connection('db_tbs')->table('entry_stock_out_tbl')->where('out_no', $get_outno)->count();
    if (isset($itemcode)) {
        if (count($itemcode) > 0) {
            foreach ($itemcode as $i => $v) {
                    $data = new StockOutEntry;
                    $data->out_no = $get_outno;
                    $data->refs_no = Str::upper($get_refs_no);
                    $data->itemcode = $itemcode[$i];
                    $data->part_no = $part_no[$i];
                    $data->descript = $descript[$i];
                    $data->fac_unit = $fac_unit[$i];
                    $data->fac_qty = $fac_qty[$i];
                    $data->factor = $factor[$i];
                    $data->unit = $unit[$i];
                    $data->quantity = $quantity[$i];
                    $data->period = $period;
                    $data->staff = $getSession;
                    $data->operator = $getSession;
                    $data->types = $get_types;
                    $data->written = $written;
                    $data->remark_header = Str::upper($remark_header);
                    $data->remark_detail = $remark_detail[$i];
                    $data->po_no = $po_no;
                    $data->branch = $get_branch;
                    $data->save();
                } 
                
        }
       
        $counter = count($get_data['itemcode']);
        date_default_timezone_set("Asia/Jakarta");
        $get_quantity2 = $get_data['quantity'];
        $get_qty_before = DB::connection('db_tbs')
                            ->table('entry_stock_out_tbl')
                            ->select(DB::raw("SUM(quantity) as total"))
                            ->where('out_no', $get_outno)
                            ->get();
         $get_item = DB::connection('db_tbs')
                            ->table('entry_stock_out_tbl')
                            ->select('itemcode')
                            ->where('out_no', $get_outno)
                            ->count();                    
        $total = $get_qty_before[0]->total;                    
        $get_itemtotal = count($get_data['itemcode']);
        $itemtotal =  $get_itemtotal+= $get_item;
        $date = Carbon::now();
        $time = Carbon::now()->format('H:i:s');
        $status = "EDIT";
        $out_no =  $get_outno;
        $userstaff = Auth::user()->FullName;
        $note = 'Wh :' . $get_types  . '/' . 'Item:'. $get_item .'/'. 'Qty:'. ' ' . $total;
        DB::connection('db_tbs')->table('entry_stock_out_log_tbl')->insert([
            'out_no' => $out_no, 
            'date' => $date,
            'time' => $time,
            'status_change' => $status,
            'user' => $userstaff,
            'note' => $note 
        ]);

       
    } else {               
        $get_item2 = DB::connection('db_tbs')
        ->table('entry_stock_out_tbl')
        ->select('itemcode')
        ->where('out_no', $get_outno)
        ->count(); 
        $get_qty_before2 = DB::connection('db_tbs')
        ->table('entry_stock_out_tbl')
        ->select(DB::raw("SUM(quantity) as total"))
        ->where('out_no', $get_outno)
        ->get();
        $totalitem = $get_qty_before2[0]->total;
        date_default_timezone_set("Asia/Jakarta");
        $date2 = Carbon::now();
        $time2 = Carbon::now()->format('H:i:s');
        $status2 = "EDIT";
        $out_no2 =  $get_outno;
        $userstaff2 = Auth::user()->FullName;
        $note2 = 'Wh:' . $get_types .'/'. 'Item:' . $get_item2 . '/'. 'Qty:' . $totalitem;
        DB::connection('db_tbs')->table('entry_stock_out_log_tbl')->insert([
            'out_no' => $out_no2, 
            'date' => $date2,
            'time' => $time2,
            'status_change' => $status2,
            'user' => $userstaff2,
            'note' => $note2 
        ]);
        return response()->json(['success'=>true]);
    }
   
  
     return response()->json([
         'success' => true
     ]);
}


public function voidStoutEntry(Request $request, $out_no)
{
    DB::beginTransaction();
    try {
        
        $data = \DB::connection('db_tbs')->table('entry_stock_out_tbl')
        ->where('out_no','=', $out_no)
        ->update(['voided' => Carbon::now()]);
        $get_count = \DB::connection('db_tbs')->table('entry_stock_out_tbl')
        // ->select('out_no')
        ->where('out_no','=', $out_no)
        ->count();
        $select = DB::connection('db_tbs')
                    ->table('entry_stock_out_tbl')
                    // ->select('quantity','types')
                    ->where('out_no', '=', $out_no)
                    ->first();
        
        //INSERT LOG ACTIVITY
        // $get_data = $request->all();
        // $get_count_data = count($get_data['itemcode']);
        date_default_timezone_set("Asia/Jakarta");
        $date = Carbon::now();
        $time = Carbon::now()->format('H:i:s');
        $status = "VOID";
        $out_no =  $out_no;
        $userstaff = Auth::user()->FullName;
        // $note = 'Wh :' . $select->types . '/' . 'Item:'. $get_count .'/'. 'Qty:'. ' ' . $select->quantity;
        DB::connection('db_tbs')->table('entry_stock_out_log_tbl')->insert([
            'out_no' => $out_no, 
            'date' => $date,
            'time' => $time,
            'status_change' => $status,
            'user' => $userstaff,
            'note' => $request->note !== '' ? $request->note : '' 
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



public function postStoutEntry(Request $request, $out_no)
{
   
    DB::beginTransaction();
    try {
        $get_data = \DB::connection('db_tbs')
                ->table('entry_stock_out_tbl')
                ->where('out_no', $out_no)
                ->first();
        $get_posted = $get_data->posted;
        if ($get_posted != null) {
            $unpost = \DB::connection('db_tbs')
                        ->table('entry_stock_out_tbl')
                        ->where('out_no', $out_no)
                        ->update(['posted' => NULL]);
                    //INSERT LOG ACTIVITY
            date_default_timezone_set("Asia/Jakarta");
            $date = Carbon::now();
            $time = Carbon::now()->format('H:i:s');
            $status = "UN-POST";
            $out_no =  $out_no;
            $userstaff = Auth::user()->FullName;
            $note = $request->note;
            DB::connection('db_tbs')->table('entry_stock_out_log_tbl')->insert([
                'out_no' => $out_no, 
                'date' => $date,
                'time' => $time,
                'status_change' => $status,
                'user' => $userstaff,
                'note' => $note 
            ]);            
        } else {
            $post = \DB::connection('db_tbs')
            ->table('entry_stock_out_tbl')
            ->where('out_no', $out_no)
            ->update(['posted' => Carbon::now()]);

            date_default_timezone_set("Asia/Jakarta");
            $date = Carbon::now();
            $time = Carbon::now()->format('H:i:s');
            $status = "POST";
            $out_no =  $out_no;
            $userstaff = Auth::user()->FullName;
            $note = $request->note;
            DB::connection('db_tbs')->table('entry_stock_out_log_tbl')->insert([
                'out_no' => $out_no, 
                'date' => $date,
                'time' => $time,
                'status_change' => $status,
                'user' => $userstaff,
                'note' => $note 
            ]);            
        }
 
        
        DB::commit();
        return response()->json([
            'success' => true,
        ]);
    } catch (Exception $ex) {
        DB::rollback();
        return redirect()->back();
    }
}

public function reportStoutEntry($out_no)
{
     $get_outno = base64_decode($out_no);
     $data = DB::connection('db_tbs')
             ->table('entry_stock_out_tbl')
             ->where('out_no', $get_outno)
             ->update(['printed' => Carbon::now()]);

     $data1 = DB::connection('db_tbs')
             ->table('entry_stock_out_tbl')
             ->where('out_no', $get_outno)
             ->get();

     $data2 = DB::connection('db_tbs')
             ->table('entry_stock_out_tbl')
             ->where('out_no', $get_outno)
             ->first();
     $count = DB::connection('db_tbs')
            ->table('entry_stock_out_tbl')
            ->where('out_no', $get_outno)
            ->count();
    date_default_timezone_set("Asia/Jakarta");
    $date = Carbon::now();
    $time = Carbon::now()->format('H:i:s');
    $status = "PRINTED";
    $out_no =  $get_outno;
    $userstaff = Auth::user()->FullName;
    // $note = 'Wh :' . $data2->types . '/' . 'Item:'. $count .'/'. 'Qty:'. ' ' . $data2->quantity;
    DB::connection('db_tbs')->table('entry_stock_out_log_tbl')->insert([
        'out_no' => $out_no, 
        'date' => $date,
        'time' => $time,
        'status_change' => $status,
        'user' => $userstaff,
        'note' => '' 
    ]);
    $pdf = PDF::loadView('tms.warehouse.stock-out-entry.report.report', ['data1' => $data1, 'data2' => $data2]);
    return $pdf->stream();
}

public function logStoutEntry($out_no)
{
    $viewLog =  DB::connection('db_tbs')
    ->table('entry_stock_out_log_tbl')
    ->where('out_no','=', $out_no)
    ->get();
    echo json_encode($viewLog);
    exit;
}

public function editDetail($id)
{
    $get_editdetail = StockOutEntry::select('id_stout','itemcode','part_no',
        'descript','fac_unit','fac_qty','unit','quantity','remark_detail','factor')   
    ->where('id_stout','=', $id)
    ->first();
    return response()->json($get_editdetail);
}

public function updateDetailStout(Request $request, $id)
{
    // $check = StockOutEntry::where('itemcode', $request->input('itemcode'))->get();
    // $msg = "Data Sudah Pernah Diinput Bro...";
    // if ($check->isEmpty()) {
        $get_ = DB::connection('db_tbs')
        ->table('entry_stock_out_tbl')
        ->where('id_stout', '=', $id)
        ->first();
        $types = $get_->types;
        $out_no = $get_->out_no;

        $get_data = DB::connection('db_tbs')
        ->table('entry_stock_out_tbl')
        ->where('id_stout','=', $id)
        ->update([
            'itemcode' => $request->itemcode,
            'part_no'=> $request->part_no,
            'descript'=> $request->descript,
            'fac_unit'=> $request->fac_unit,
            'fac_qty'=> $request->fac_qty,
            'unit'=> $request->unit,
            'quantity'=> $request->quantity,
            'remark_detail'=> $request->remark_detail
        ]);  
        $get_qty_before = DB::connection('db_tbs')
                        ->table('entry_stock_out_tbl')
                        ->select(DB::raw("SUM(quantity) as total"))
                        ->where('out_no', $out_no)
                        ->get();
        $get_item = DB::connection('db_tbs')
                    ->table('entry_stock_out_tbl')
                    ->select('itemcode')
                    ->where('out_no', $out_no)
                    ->count();                    
        $total = $get_qty_before[0]->total;                    
        // $itemtotal =  $get_itemtotal+= $get_item;
        date_default_timezone_set("Asia/Jakarta");
        $date = Carbon::now();
        $time = Carbon::now()->format('H:i:s');
        $status = "EDIT";
        $out_no =  $out_no;
        $userstaff = Auth::user()->FullName;
        $note = 'Wh :' . $types  . '/' . 'Item:'. $get_item .'/'. 'Qty:'. ' ' . $total;
        DB::connection('db_tbs')->table('entry_stock_out_log_tbl')->insert([
            'out_no' => $out_no, 
            'date' => $date,
            'time' => $time,
            'status_change' => $status,
            'user' => $userstaff,
            'note' => $note 
        ]);          
        return response()->json([
            'success'=> true
        ]);  
    // } else if() {
    //     return response()->json([
    //         'error'=>$msg,
    //         'check'=> $check
    //     ]);
    // }


}

public function deleteDetailPageStout($id)
{
    $get_ = DB::connection('db_tbs')
    ->table('entry_stock_out_tbl')
    ->where('id_stout', '=', $id)
    ->first();
    $types = $get_->types;
    $out_no = $get_->out_no;
    // $get_data = DB::connection('db_tbs')
    //             ->table('entry_stock_out_tbl')
    //             ->where('id_stout', '=', $id)
    //             ->delete();
    $data = StockOutEntry::find($id);
    $data->delete();
    $get_qty_before = DB::connection('db_tbs')
                        ->table('entry_stock_out_tbl')
                        ->select(DB::raw("SUM(quantity) as total"))
                        ->where('out_no', $out_no)
                        ->get();
    $get_item = DB::connection('db_tbs')
                ->table('entry_stock_out_tbl')
                ->select('itemcode')
                ->where('out_no', $out_no)
                ->count();                    
    $total = $get_qty_before[0]->total;                    
    date_default_timezone_set("Asia/Jakarta");
    $date = Carbon::now();
    $time = Carbon::now()->format('H:i:s');
    $status = "EDIT";
    $out_no =  $out_no;
    $userstaff = Auth::user()->FullName;
    $note = 'Wh :' . $types  . '/' . 'Item:'. $get_item .'/'. 'Qty:'. ' ' . $total;
    DB::connection('db_tbs')->table('entry_stock_out_log_tbl')->insert([
        'out_no' => $out_no, 
        'date' => $date,
        'time' => $time,
        'status_change' => $status,
        'user' => $userstaff,
        'note' => $note 
    ]);          
                
    return response()->json([
        'success'=> true
    ]);
}

public function updateHeaderEditPageStout(Request $request, $out_no)
{
  
    $get_data_stout = DB::connection('db_tbs')
    ->table('entry_stock_out_tbl')
    ->where('out_no', $out_no)
    ->first();
    $count = DB::connection('db_tbs')
    ->table('entry_stock_out_tbl')
    ->where('out_no', $out_no)
    ->count();
    $get_types = $get_data_stout->types;
    $get_refs_no = $get_data_stout->refs_no;
    
    if(isset($get_types) == true && isset($get_refs_no) == true){
        $datastout = DB::connection('db_tbs')
        ->table('entry_stock_out_tbl')
        ->where('out_no', $out_no)
        ->update([
            'types' => $request->types !== '' ? $request->types : $get_modelstout->types,
            'refs_no' => $request->refs_no !== '' ? Str::upper($request->refs_no) : Str::upper($get_modelstout->refs_no),
            'remark_header'=> $request->remark_header !== '' ? Str::upper($request->remark_header) : Str::upper($get_modelstout->remark_header)
        ]);

    } 
    
    return response()->json([
        'success'=>true
    ]);

}
public function viewRestoreDelete($out_no){
    $data = StockOutEntry::onlyTrashed()->where('out_no', $out_no)->get();
    return Datatables::of($data)->make(true);
}


public function StoutRestore($id){
    $data = StockOutEntry::onlyTrashed()->where('id_stout', $id);
    $data->restore();
}

// public function validateEditDetail($itemcode)
// {

//     $check = StockOutEntry::where('itemcode', $itemcode)->get();
//     if (!$check->isEmpty()) {
//         $msg = "Data Sudah pernah di input";
//         return response()->json([
//             'check'=> $check,
//             'error'=>$msg
//         ]);
//     } else {
//         echo "Not Found";
//     }
    
// }



}
