<?php

namespace App\Http\Controllers\TMS\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dbtbs\MtoEntry;
use DataTables;
use App\Models\Dbtbs\Formula;
use App\Models\Dbtbs\Item;
use App\Classes\ButtonBuilder As ButtonBuilder;
use Carbon\Carbon;
use DB;
use Auth;
use PDF;
use Illuminate\Support\Str;
class MtoEntryController extends Controller
{
    public function index(Request $request)
    {
        $getDate = Carbon::now()->format('d/m/Y');
        $getDate1 =  Carbon::now()->format('Y-m');
        $data_mto = new MtoEntry();
        $get_no_mto = $data_mto->getMtoNo();

        return view('tms.warehouse.mto-entry.index', compact('getDate','getDate1','get_no_mto'));
    }

    public function getMtoDatatables(Request $request)
    {
        if ($request->ajax()) {
            $data =  MtoEntry::select('id_mto','mto_no','written','posted','voided','fin_code','ref_no','remark','branch','period')
                        // ->where('voided','=', NULL)
                        ->where('voided','=', NULL)
                        ->groupByRaw('mto_no')
                        ->get();
            return Datatables::of($data)    
            ->editColumn('written', function($data){
                $data = Carbon::parse($data->written)->format('d/m/Y');
                return $data;
            })->editColumn('posted', function($data){
                if ($data->posted != null) {
                    return $data->posted;
                } else {
                    return '//';
                }
            })->editColumn('voided', function($data){
                if ($data->voided != null) {
                    $get_data = Carbon::parse($data->voided)->format('d/m/Y');
                    return $get_data;
                } else {
                    return '//';
                }
            })->editColumn('posted', function($data){
                if ($data->posted != null) {
                    $get_data = Carbon::parse($data->posted)->format('d/m/Y');
                    return $get_data;
                } else {
                    return "//";
                }
            })
            ->addColumn('action', function($data){
                return view('tms.warehouse.mto-entry._action_datatables._actionmto', [
                    'model' => $data,
                    'url_print' => route('tms.warehouse.mto-entry_report_pdf_mtodata', base64_encode($data->id_mto))
                ]);
            })->rawColumns(['action'])
            ->make(true);  
        }
    }

    public function getPopUpChoiceDataDatatables(Request $request)
    {
        if ($request->ajax()) {
            $getItem = Item::query()
                      ->where('ITEMCODE', 'like', '5%')
                      ->orWhere('ITEMCODE','like','1%');
            return Datatables::of($getItem)->make(true);  
        }
    }

    public function StoreMtoData(Request $request)
    {
      
        $rules = [
            'ref_no'=> 'required',
             'types'=> 'required',
             'frm_code.*'=> 'required',
             'fin_code'=>'required',
             'quantity'=>'required',
             'remark'=> 'required'
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'error'=> $validator->errors()->all()
            ]);
        }
        $getFromModel = new MtoEntry();
        $getFuncMtoNo = $getFromModel->getMtoNo();
        $finCode      = $request->fin_code;
        $frmCode      = $request->frm_code;
        $partNo       = $request->part_no;
        $partNo1      = $request->part_no_detail;
        $descript     = $request->descript;
        $descript1    = $request->descript_detail;
        $quantity     = $request->quantity;
        $frm_quantity = $request->frm_quantity;
        $quantityNG   = $request->qty_ng;
        $remark       = $request->remark;
        $types        = $request->types;
        $period       = Carbon::now()->format('Y/m');
        $branch       = 'HO';
        $written      = $request->written;
        $posted       = $request->posted !== '' ? $request->posted : null;
        $printed      = $request->printed !== '' ? $request->posted : null;
        $voided       = $request->voided !== '' ? $request->voided : null;
        $warehouse    = '90';
        $get_user_staff = Auth::user()->FullName;
        $remark       = $request->remark;
        $cost         = $request->cost !== '' ? $request->cost : null;
        $RefNo        = $request->ref_no;
        $LBom         = $request->lbom !== '' ? $request->lbom : null;
        $UIDExport    = $request->uid_export !== '' ? $request->uid_export : null;
        $vPeriod      = $request->vperiod !== '' ? $request->vperiod : null;
        $XPrinted     = $request->xprinted !== '' ? $request->xprinted : null;
        $operator     = $request->operator !== '' ? $request->operator : null;
        $glinv        = $request->glinv !== '' ? $request->glinv : null;
        $fac_unit     = $request->fac_unit;
        $fac_qty      = $request->fac_qty;
        $qty_frm_qty_ng = $request->frm_qty_ng;
        $factor       = $request->factor !== '' ? $request->factor : null;
        $unit         = $request->unit;
        $ip_type      = '-';
        $get_all_data = $request->all();
        $period       = $get_all_data['period'];
        $convert = Carbon::createFromFormat('Y-m',  $period)->format('Y');
        $convert2 = Carbon::createFromFormat('Y-m',  $period)->format('m');
        // dd($convert);
        if (isset($frmCode)) {
            if (count($frmCode) > 0) {
                $check =  DB::connection('db_tbs')
                ->table('stclose')
                ->whereYear('DATE','=',  $convert)
                ->whereMonth('DATE','=', $convert2)
                ->get();
                $msg = "Sudah di closing tidak bisa entry";
                if ($check->isEmpty()) {
                    foreach ($frmCode as $item => $value) {
                        $data = array(
                            'mto_no'=> $getFuncMtoNo,
                            'fin_code'=>  $finCode,
                            'frm_code'=> $frmCode[$item],
                            'descript'=>$descript,
                            'descript_detail'=>$descript1[$item],
                            'part_no'=> $partNo,
                            'part_no_detail'=> $partNo1[$item],
                            'fac_unit'=>$fac_unit,
                            'fac_qty'=>$fac_qty,
                            'factor'=>$factor[$item],
                            'unit'=>$unit[$item],
                            'frm_quantity'=>$frm_quantity[$item],
                            'quantity'=>$quantity,
                            'qty_ng'=>$quantityNG,
                            'frm_qty_ng'=> $qty_frm_qty_ng[$item],
                            'period'=> $period,
                            'staff'=>  $get_user_staff,
                            'types'=>$types,
                            'operator'=> $operator,
                            'written'=> $written,
                            'branch'=> $branch,
                            'warehouse'=> $warehouse,
                            'ref_no'=> Str::upper($RefNo),
                            'remark'=> Str::upper($remark)
                        );
        
                        $get_data = MtoEntry::create($data);  
        
                      
                    }
                } else {
                    return response()->json([
                        'errors'=> $msg,
                        'check'=> $check
                    ]);
                }
              
            }
             // INSERT LOG MTO
            date_default_timezone_set("Asia/Jakarta");
            $date = Carbon::now();
            $time = Carbon::now()->format('H:i:s');
            $status = "ADD";
            $mto_no = $getFuncMtoNo;
            $userstaff = Auth::user()->FullName;
            $note = $get_data['warehouse'] . '/' . $get_data['fin_code'] .'/'. 'Qty:'. ' ' . $get_data['quantity'];
            DB::connection('db_tbs')->table('entry_mto_log')->insert([
                'mto_no' => $mto_no, 
                'date' => $date,
                'time' => $time,
                'status_change' => $status,
                // 'status_aktif'=> 'AKTIF',
                'user' => $userstaff,
                'note' => $note 
            ]);
        }
      

       
       

        return response()->json(['success'=>true]);
        

        
    }

    public function show_view_detail($id)
    {
        $MTOHeader   = MtoEntry::where('id_mto', $id)->first();
        $MTOHeaderNo = $MTOHeader->mto_no;
        $MTODetail   = MtoEntry::select(
            'id_mto', 'mto_no', 'fin_code', 'frm_code', 'descript',
            'unit', 'quantity','frm_quantity', 'qty_ng','types','written','posted','voided','posted','staff','period',
            'warehouse','branch','ref_no','remark','printed','descript_detail','part_no','frm_qty_ng','part_no_detail')   
        ->where('mto_no', '=', $MTOHeaderNo)
        ->get();           
        $output = [
            'header' => $MTOHeader,
            'detail' => $MTODetail
        ];

        return response()->json($output);
    }

    public function editMtoData($id)
    {
        $data        = MtoEntry::where('id_mto', $id)->first();
        $mto_no     = $data->mto_no;
        $MTODetail   = MtoEntry::select(
            'id_mto', 'mto_no', 'fin_code', 'frm_code', 'descript','part_no_detail','descript_detail',
            'unit', 'quantity','frm_quantity', 'qty_ng','types','written','posted','voided','posted','staff','period',
            'warehouse','branch','ref_no','remark','printed','factor','frm_qty_ng'
        )
        ->where('mto_no', '=', $mto_no)
        ->get();
        $output = [
            'header' => $data,
            'detail' => $MTODetail
            
        ];
        return response()->json($output);
    }

    public function updateMtoEntry(Request $request, $mto_no)
    {
      
            $datamto1 = DB::connection('db_tbs')
            ->table('entry_mto_tbl')
            ->where('mto_no', $mto_no)
            ->first();
            $id = $datamto1->id_mto;
            $ref_no = $datamto1->ref_no;
            $qty_ = $datamto1->quantity;
            $qty_ng = $datamto1->qty_ng;
            $remark = $datamto1->remark;
            $factor = $request->factor;
            $quantityNG   = $request->qty_ng;
            $frmQuantity = $request->frm_quantity;
            $dd = $request->all();
            $period = $dd['period'];
            $convert = Carbon::createFromFormat('Y-m',  $period)->format('Y');
            $convert2 = Carbon::createFromFormat('Y-m',  $period)->format('m');
            if (isset($request->ref_no) == true && isset($request->quantity) == true && isset($request->qty_ng) == true && isset($request->remark) == true) {
                $check =  DB::connection('db_tbs')
                ->table('stclose')
                ->whereYear('DATE','=',  $convert)
                ->whereMonth('DATE','=', $convert2)
                ->get();
                $msg = "Sudah di closing tidak bisa diedit";

                if ($check->isEmpty()) {
                    $datadelete = DB::connection('db_tbs')->table('entry_mto_tbl')->where('mto_no', $mto_no)->delete();
                    $count = count($dd['frm_code']);
                    for ($i=0; $i < $count; $i++) { 
                        $data = DB::connection('db_tbs')
                            ->table('entry_mto_tbl')
                            ->where('mto_no', $mto_no)
                            ->insert([
                                'id_mto'=> $dd['id_mto'][$i],
                                'ref_no'=> Str::upper($dd['ref_no']),
                                'mto_no'=> $mto_no,
                                'fin_code'=> $dd['fin_code'],
                                'frm_code'=> $dd['frm_code'][$i],
                                'part_no'=> $dd['part_no'],
                                'part_no_detail'=> $dd['part_no_detail'][$i],
                                'descript'=> $dd['descript'],
                                'descript_detail'=> $dd['descript_detail'][$i],
                                'remark'=> Str::upper($dd['remark']),
                                'quantity'=> $dd['quantity'],
                                'frm_quantity'=> $dd['frm_quantity'][$i] ,
                                'qty_ng'=> $dd['qty_ng'],
                                'frm_qty_ng'=> $dd['frm_qty_ng'][$i],
                                'types'=> $dd['types'],
                                'ref_no'=> Str::upper($dd['ref_no']),
                                'unit'=> $dd['unit'][$i],
                                'warehouse'=> $dd['warehouse'],
                                'branch'=> 'HO',
                                'period'=> $dd['period'],
                                'staff'=> $dd['staff'],
                                'written'=> $dd['written'],
                                'printed'=> $dd['printed'][$i] === '0000-00-00' ? NULL : $dd['printed'][$i],
                                'posted'=> $dd['posted'] !== '' ? $dd['posted'] : NULL,
                                'voided'=> $dd['voided'] !== '' ? $dd['voided'] : NULL,
                                'factor'=> $dd['factor'][$i]
                            ]);
    
                    }
                 
                    $datamto2 = DB::connection('db_tbs')
                    ->table('entry_mto_tbl')
                    ->select('quantity')
                    ->where('mto_no', $mto_no)
                    ->get();
                    // INSERT LOG MTO
                    date_default_timezone_set("Asia/Jakarta");
                    $date = Carbon::now();
                    $time = Carbon::now()->format('H:i:s');
                    $status = "EDIT";
                    $mto_no = $mto_no;
                    $userstaff = Auth::user()->FullName;
                    $note = $dd['warehouse'] . '/' . $dd['fin_code'] .'/'. 'Qty:'. ' ' . $dd['quantity'];
                    DB::connection('db_tbs')->table('entry_mto_log')->insert([
                        'mto_no' => $mto_no, 
                        'date' => $date,
                        'time' => $time,
                        'status_change' => $status,
                        // 'status_aktif'=> 'AKTIF',
                        'user' => $userstaff,
                        'note' => $note 
                    ]);
                    return response()->json([
                        'success'=>true
                    ]);
                } else {
                    return response()->json([
                        'errors'=>$msg,
                        'check'=> $check
                    ]);
                }
                
            }
          

           
      
        
    }

    public function voidedMtoData($id)
    {
        $data = MtoEntry::find($id);
        $mto_no = $data->mto_no;
        try {
            DB::beginTransaction();
            $data1 = \DB::connection('db_tbs')->table('entry_mto_tbl')
            ->where('mto_no','=', $mto_no)
            ->update(['voided' => Carbon::now()]);
            // INSERT LOG ACTIVITY
            date_default_timezone_set("Asia/Jakarta");
            $date = Carbon::now();
            $time = Carbon::now()->format('H:i:s');
            $status = "VOID";
            $mto_no = $mto_no;
            $userstaff = Auth::user()->FullName;
            $note = $data['warehouse'] . '/' . $data['fin_code'] .'/'. 'Qty:'. ' ' . $data['quantity'];
            DB::connection('db_tbs')->table('entry_mto_log')->insert([
                'mto_no' => $mto_no, 
                'date' => $date,
                'time' => $time,
                'status_change' => $status,
                // 'status_aktif'=> 'NON AKTIF',
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

    public function reportPdfMto($id)
    {
        $get_id = base64_decode($id);
        $data = MtoEntry::find($get_id);  
        $mto_no = $data->mto_no;
        $data1   = MtoEntry::select(
            'id_mto', 'mto_no', 'fin_code', 'frm_code', 'descript','part_no_detail','descript_detail',
            'unit', 'quantity','frm_quantity', 'qty_ng','types','written','posted','voided','posted','staff','period',
            'warehouse','branch','ref_no','remark','printed'
        )
        ->where('mto_no', '=', $mto_no)
        ->get();
        $data2   = MtoEntry::select(
            'id_mto', 'mto_no', 'fin_code', 'frm_code', 'descript','part_no_detail','descript_detail',
            'unit', 'quantity','frm_quantity', 'qty_ng','types','written','posted','voided','posted','staff','period',
            'warehouse','branch','ref_no','remark','printed'
        )->where('mto_no', $mto_no)->update([
            'printed'=> Carbon::now()
        ]);
        $data->save();
        $pdf = PDF::loadView('tms.warehouse.mto-entry.report.report', ['data' => $data, 'data1' => $data1]);
        return $pdf->stream();
    }

    public function postedMtoData(Request $request, $id)
    {
       
        $data = MtoEntry::find($id);
        $mto_no = $data->mto_no;
        $period = $data->period;
        // $convert = Carbon::createFromFormat('Y-m',  $period)->format('Y-m-d');
        try {
            DB::beginTransaction();
            $get_posted =  $data['posted'];
            $convert1 = Carbon::createFromFormat('Y-m',  $period)->format('Y');
            $convert2 = Carbon::createFromFormat('Y-m',  $period)->format('m');
            $check =  DB::connection('db_tbs')
            ->table('stclose')
            ->whereYear('DATE','=',  $convert1)
            ->whereMonth('DATE','=', $convert2)
            ->get();
            $msg = "Sudah di closing tidak bisa di UNPOST";
            if ($get_posted != null) {
                if ($check->isEmpty()) {
                    $data1 = \DB::connection('db_tbs')->table('entry_mto_tbl')
                    ->where('mto_no','=', $mto_no)
                    ->update(['posted' => NULL]);
    
                    // INSERT LOG UN-POSTED
                    date_default_timezone_set("Asia/Jakarta");
                    $date = Carbon::now();
                    $time = Carbon::now()->format('H:i:s');
                    $status = "UN-POST";
                    $mto_no = $data['mto_no'];
                    $userstaff = Auth::user()->FullName;
                    $note = $data['warehouse'] . '/' . $data['fin_code'] .'/'. 'Qty:'. ' ' . $data['quantity'];
                    DB::connection('db_tbs')->table('entry_mto_log')->insert([
                        'mto_no' => $mto_no, 
                        'date' => $date,
                        'time' => $time,
                        'status_change' => $status,
                        // 'status_aktif'=> 'AKTIF',
                        'user' => $userstaff,
                        'note' => $request->note !== '' ? $request->note : $note 
                    ]);
                } else {
                    return response()->json([
                        'check'=> $check,
                        'errors'=> $msg
                    ]);
                }
                //un-posted
                // $data['posted'] = NULL;
                // $data->update();
               
            } else {
                // posted-mto
                $convert1 = Carbon::createFromFormat('Y-m',  $period)->format('Y');
                $convert2 = Carbon::createFromFormat('Y-m',  $period)->format('m');
                $check2 =  DB::connection('db_tbs')
                ->table('stclose')
                ->whereYear('DATE','=',  $convert1)
                ->whereMonth('DATE','=', $convert2)
                ->get();
                if ($check2->isEmpty()) {
                    $data1 = \DB::connection('db_tbs')->table('entry_mto_tbl')
                        ->where('mto_no','=', $mto_no)
                        ->update(['posted' => Carbon::now()]);

                    // INSERT LOG POSTED MTO
                    date_default_timezone_set("Asia/Jakarta");
                    $date = Carbon::now();
                    $time = Carbon::now()->format('H:i:s');
                    $status = "POST";
                    $mto_no = $data['mto_no'];
                    $userstaff = Auth::user()->FullName;
                    $note = $data['warehouse'] . '/' . $data['fin_code'] .'/'. 'Qty:'. ' ' . $data['quantity'];
                    DB::connection('db_tbs')->table('entry_mto_log')->insert([
                        'mto_no' => $mto_no, 
                        'date' => $date,
                        'time' => $time,
                        'status_change' => $status,
                        'user' => $userstaff,
                        'note' => $note 
                    ]);
                } else {
                    $msg2 = "Sudah diclosing data tidak bisa post";
                    return response()->json([
                        'check'=> $check,
                        'errors'=> $msg2
                    ]);
                }
               
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

public function viewLogMtoEntry($mto_no)
{   
    $data =  DB::connection('db_tbs')
    ->table('entry_mto_log')
    ->where('mto_no','=', $mto_no)
    // ->where('status_aktif','!=', NULL)
    ->get();    

    return response()->json($data);
}

public function checkBom($item){
    $bom = DB::connection('db_tbs')
    ->table('formula')
    ->join('item','formula.FIN_CODE','=','item.ITEMCODE')
    ->select('FIN_CODE','FRM_CODE','FRM_DESC','FRM_UNIT','PART_NO','FRM_FAC')
    ->where('FIN_CODE','=',  $item)
    ->get();
    if ($bom->isEmpty()) {
       $msg = "BOM tidak ada";
       return response()->json([
           'msg'=>$msg
       ]);
    } else {
        echo json_encode($bom);
        exit;
    }
   
   
    // return Datatables::of($bom)->toJson();
}

public function checkStClose($period){
    $get_period = $period;
    $convert = Carbon::createFromFormat('Y-m',  $get_period)->format('Y');
    $convert2 = Carbon::createFromFormat('Y-m',  $get_period)->format('m');
    $checkStClose = DB::connection('db_tbs')
    ->table('stclose')
    ->whereYear('DATE','=',  $convert)
    ->whereMonth('DATE','=', $convert2)
    ->get();
    if ($checkStClose->isEmpty()) {
      echo 'Silahkan Input';
    } else {
        $msg = "Sudah diclosing tidak bisa input/update";
        return response()->json([
            'msg'=>$msg
        ]);
    }
}


// public function DestroyDeleteEditDetail($id)
// {
//     $data = DB::connection('db_tbs')
//             ->table('entry_mto_tbl')
//             ->where('id_mto', $id)
//             ->delete();
// }


// public function validateItemSameMtoEntry($item){
//     $check = MtoEntry::where('fin_code', $item)->get();
//     if (!$check->isEmpty()) {
//         $msg = "Data Sudah pernah di input";
//         return response()->json([
//             'check'=> $check,
//             'error'=>$msg
//         ]);
//     } else {
//         echo "Item belum ada di MTO";
//     }
// }

// public function addRowEditPage(Request $request, $id)
// {
//     $data         = MtoEntry::find($id);
//     $mto_no       = $data->mto_no;
//     $branch       = $data->branch;
//     $types        = $data->types;
//     $ref_no       = $data->ref_no;
//     $period       = $data->period;
//     $written      = $data->written;
//     $fin_code     = $data->fin_code;
//     $part_no      = $data->part_no;
//     $descript     = $data->descript;
//     $quantity     = $data->quantity;
//     $frm_quantity = $data->frm_quantity;
//     $qty_ng       = $data->qty_ng;
//     $unit         = $data->unit;
//     $remark       = $data->remark;
//     $warehouse    = $data->warehouse;

//     // $finCode      = $request->fin_code;
//     $frmCode      = $request->frm_code;
//     $partNo       = $request->part_no;
//     $partNo1      = $request->part_no_detail;
//     $descript     = $request->descript;
//     $descript1    = $request->descript_detail;
//     $quantity     = $request->quantity;
//     $frm_quantity = $request->frm_quantity;
//     $quantityNG   = $request->qty_ng;
//     $remark       = $request->remark;
//     $types        = $request->types;
//     $period       = Carbon::now()->format('Y/m');
//     $branch       = 'HO';
//     $written      = Carbon::now();
//     $posted       = $request->posted !== '' ? $request->posted : null;
//     $printed      = $request->printed !== '' ? $request->posted : null;
//     $voided       = $request->voided !== '' ? $request->voided : null;
//     $warehouse    = '90';
//     $get_user_staff = Auth::user()->FullName;
//     $remark       = $request->remark;
//     $cost         = $request->cost !== '' ? $request->cost : null;
//     $RefNo        = $request->ref_no;
//     $LBom         = $request->lbom !== '' ? $request->lbom : null;
//     $UIDExport    = $request->uid_export !== '' ? $request->uid_export : null;
//     $vPeriod      = $request->vperiod !== '' ? $request->vperiod : null;
//     $XPrinted     = $request->xprinted !== '' ? $request->xprinted : null;
//     $operator     = $request->operator !== '' ? $request->operator : null;
//     $glinv        = $request->glinv !== '' ? $request->glinv : null;
//     $fac_unit     = $request->fac_unit;
//     $fac_qty      = $request->fac_qty;
//     $factor       = $request->factor !== '' ? $request->factor : null;
//     // $unit         = $request->unit;
//     $ip_type      = '-';
//     $get_all_data = $request->all();
//     if (isset($frmCode)) {
//         if (count($frmCode) > 0) {
//             foreach ($frmCode as $item => $value) {
//                 $data1 = new MtoEntry;
//                 $data1->mto_no = $mto_no;
//                 $data1->ref_no = Str::upper($ref_no);
//                 $data1->fin_code = $fin_code;
//                 $data1->frm_code = $frmCode[$item];
//                 $data1->descript = $descript;
//                 $data1->descript_detail = $descript1[$item];
//                 $data1->part_no = $part_no;
//                 $data1->part_no_detail = $partNo1[$item];
//                 $data1->fac_unit = $fac_unit;
//                 $data1->fac_qty = $fac_qty;
//                 $data1->factor = $factor;
//                 $data1->unit = $unit;
//                 $data1->frm_quantity = $frm_quantity[$item];
//                 $data1->quantity = $quantity;
//                 $data1->qty_ng = $qty_ng[$item];
//                 $data1->period = $period;
//                 $data1->staff = $get_user_staff;
//                 $data1->types = $types;
//                 $data1->operator = $operator;
//                 $data1->written = $written;
//                 $data1->branch = $branch;
//                 $data1->warehouse = $warehouse;
//                 $data1->remark = Str::upper($remark);
//                 $data1->save();

              
//             }
//             return response()->json([
//                 'success'=>true
//             ]);
//         }
//     }
// }

    // public function mtoEntryEditDetailDatatables($mto_no)
    // {
    //     $MTODetail   = MtoEntry::select(
    //         'id_mto', 'mto_no', 'fin_code', 'frm_code', 'descript','part_no_detail','descript_detail',
    //         'unit', 'quantity','frm_quantity', 'qty_ng','types','written','posted','voided','posted','staff','period',
    //         'warehouse','branch','ref_no','remark','printed','factor'
    //     )
    //     ->where('mto_no', '=', $mto_no)
    //     ->get();

    //     return Datatables::of($MTODetail)->toJson();
    //     // echo json_encode($MTODetail)
    //     // exit;
    // }


}
