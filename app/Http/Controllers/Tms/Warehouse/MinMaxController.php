<?php

namespace App\Http\Controllers\Tms\Warehouse;

use App\Exports\ReportFilterchuter;
use App\Exports\ReportMinmax;
use App\Http\Controllers\Controller;
use App\Imports\ImportCreateMinMax;
use App\Imports\ImportUpdateMinMax;
use App\Models\Ekanban\Chuter_in_out_log;
use App\Models\Ekanban\Ekanban_addressmaster;
// use App\Models\Ekanban\ekanban_fg_chuter_tbl;
use App\Models\Ekanban\Ekanban_fg_chuter_tbl;
use App\Models\Ekanban\Ekanban_fgin_tbl;
use App\Models\Ekanban\Ekanban_Fgprinted_Log;
use App\Models\Ekanban\Ekanban_param_tbl;
use App\Models\Ekanban\Ekanban_stock_limit;
use App\Models\Ekanban\Ekanban_stock_limit_log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Svg\Tag\Rect;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Facades\Excel;

class MinMaxController extends Controller
{
    //
    public function indexMaster()
    {
        $get_chuter = Ekanban_stock_limit::select('chutter_address')->get();
        // dd($get_chuter);
        return view('tms.warehouse.min-max.index', compact('get_chuter'));
    }

    public function get_masterminmax_datatables(Request $request)
    {
        if ($request->ajax()) {
            // ambil data input value nya di sini untuk ini
            $plant = $request->plant;
            // dd($plant);
            $stock_type = "F/G";
            $mpname = Carbon::now()->format('m-Y');
            // left joint 3 tbl ekanban_stock_limit,ekanban_fg_chuter_tbl,ekanban_param_tbl
            $data = Ekanban_stock_limit::select(
                'ekanban_stock_limit.itemcode',
                'ekanban_stock_limit.chutter_address',
                DB::raw('MAX(ekanban_stock_limit.id) as id'), // Mengambil id yang maksimal
                DB::raw('MAX(ekanban_stock_limit.part_number) as part_number'), // Mengambil part_number yang maksimal
                DB::raw('MAX(ekanban_stock_limit.part_name) as part_name'), // Mengambil part_name yang maksimal
                DB::raw('MAX(ekanban_stock_limit.part_type) as part_type'), // Mengambil part_type yang maksimal
                DB::raw('MAX(ekanban_stock_limit.min) as min'), // Mengambil nilai minimum dari min
                DB::raw('MAX(ekanban_stock_limit.max) as max'), // Mengambil nilai maksimum dari max
                DB::raw('MAX(ekanban_stock_limit.stock_type) as stock_type'), // Mengambil stock_type yang maksimal
                DB::raw('MAX(ekanban_fg_chuter_tbl.balance) as stock'), // Mengambil saldo maksimum
                DB::raw('MAX(ekanban_param_tbl.qty_kanban) as lot'), // Mengambil qty_kanban yang maksimal
                DB::raw('MAX(ekanban_stock_limit.action_date) as action_date') // Mengambil action_date yang maksimal
            )
            ->leftJoin('ekanban_fg_chuter_tbl', function ($join) {
                $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_fg_chuter_tbl.item_code');
            })
            ->leftJoin('ekanban_param_tbl', 'ekanban_stock_limit.itemcode', '=', 'ekanban_param_tbl.item_code')
            ->where('ekanban_fg_chuter_tbl.mpname', '=', $mpname)
            ->where('ekanban_stock_limit.stock_type', $stock_type)
            ->where('ekanban_stock_limit.is_active', '1')
            ->groupBy('ekanban_stock_limit.itemcode', 'ekanban_stock_limit.chutter_address') // Grouping berdasarkan itemcode dan chutter_address
            ->orderBy('ekanban_stock_limit.action_date', 'desc');
            // If plant filter is provided, apply it
            if ($plant) {
                $data->where('ekanban_stock_limit.plant', $plant);
            }

            // Execute the query
            $data = $data->get();
            // dd($data);
            return DataTables::of($data)
                // ->addColumn('custom', '')
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->editColumn('action', function ($data) {
                    return view('tms.warehouse.min-max.action_datatables.action_datatables', [
                        'model' => $data,
                        // 'url_print' => route('tms.warehouse.stock_out_entry_report', base64_encode($data->jmesin))
                    ]);
                })
                ->make(true);
        }
    }

    public function getItemcodeekanbanparam(Request $request)
    {
        // dd($request);
        if ($request->ajax()) {
            $data = Ekanban_param_tbl::select('item_code', 'part_no', 'part_name', 'model', 'qty_kanban', 'customer')
                ->where('item_code', 'like', '1.%') // Menambahkan klausa WHERE
                ->get();

            // dd($data);
            return Datatables::of($data)->make(true);
        }
    }
    public function validasi_chutter(Request $request)
    {
        // dd($request);
        $chutterValue = $request->chutterValue;
        $validasiChutter = Ekanban_stock_limit::where('chutter_address', $chutterValue)
            ->where('is_active', '1')
            ->select('chutter_address')
            ->first();

        $chutterAddress = $validasiChutter->chutter_address ?? "";

        // dd($chutterAddress);
        return response()->json($validasiChutter);
    }
    public function validasi_period(Request $request)
    {

        // dd($request);
        $period = Carbon::now()->format('Y-m');

        // dd($period);
        $itemcode_create = $request->itemcode_create;

        $validasiPeriod = Ekanban_stock_limit::where('itemcode', $itemcode_create)
            ->where('period', $period)
            ->select('itemcode', 'period')
            ->first();
        $result = $validasiPeriod->itemcode ?? "";
        // dd($result);
        return response()->json($result);
    }
    public function validasi_multiple(Request $request)
    {
        // dd($request);
        $itemcode = $request->itemCode;
        $getStok = Ekanban_param_tbl::where('item_code', $itemcode)
            ->select('qty_kanban')
            ->first();
        // dd($getStok);
        return response()->json($getStok);
    }

    public function store_masterminmax(Request $request)
    {
        // dd($request);
        date_default_timezone_set("Asia/Jakarta");
        $chutter_create = strtoupper($request->chutter_create);
        $itemcode_create = $request->itemcode_create;
        $partno_create = $request->partno_create;
        $partname_create = $request->partname_create;
        $part_type_create = $request->part_type_create;
        $min_create = $request->min_create;
        $max_create = $request->max_create;
        $plant_create = $request->plant_create;
        $stock_type_create = $request->stock_type_create;

        $period = Carbon::now()->format('Y-m');
        // dd($plant_create);
        $cust_code = $request->cust_code;
        $is_active = '1';
        $action_name = 'INSERT';
        $action_user = Auth::user()->UserID;
        // dd($action_user);
        $action_date = Carbon::now();
        $data = Ekanban_stock_limit::create([
            'chutter_address' => $chutter_create,
            'period' => $period,
            'itemcode' => $itemcode_create,
            'part_number' => $partno_create,
            'part_name' => $partname_create,
            'part_type' => $part_type_create,
            'cust_code' => $cust_code,
            'min' => $min_create,
            'max' => $max_create,
            'plant' => $plant_create,
            'is_active' => $is_active,
            'action_name' => $action_name,
            'stock_type'=> $stock_type_create,
            'action_user' => $action_user,
            'action_date' => $action_date

        ]);

        date_default_timezone_set("Asia/Jakarta");
        $data_id = Ekanban_stock_limit::select('id')
        ->where('is_active',$is_active)
        ->where('itemcode',$itemcode_create)
        ->first();
        $data_id = $data_id->id;
        $action_name = "INSERT";
        $time = Carbon::now();
        $column_name = "";
        $old_value =  "";
        $new_value = "";
        $userstaff = Auth::user()->UserID;
        // $note = 'Code :' . $code . '/' . 'FOH cost:' . $foh;

        $addLog = Ekanban_stock_limit_log::create([
            'table_id' => $data_id,
            'action_name' => $action_name,
            'column_name' => $column_name,
            'old_value' => $old_value,
            'new_value' =>  $new_value,
            'action_date' =>  $time,
            'action_user' => $userstaff
        ]);
        // dd($addLog);
        return response()->json(['message' => 'Record created successfully.']);
    }

    public function import_excel_create(Request $request)
    {
        // dd($request);

        // Ambil file Excel dari request
        $file = $request->file('importFile');
        // dd($file);
        try {

            Excel::import(new ImportCreateMinMax, $file);

            // Proses berhasil
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            // Proses gagal, tangani kesalahan di sini
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
    public function show_view_masterminmax($id)
    {
        // dd($request);
        // get ekanbanparam -> qty_kanban = LOT
        $getView = Ekanban_stock_limit::select(
            'ekanban_stock_limit.chutter_address',
            'ekanban_stock_limit.itemcode',
            'ekanban_stock_limit.part_number',
            'ekanban_stock_limit.part_name',
            'ekanban_stock_limit.part_type',
            'ekanban_stock_limit.cust_code',
            'ekanban_stock_limit.stock_type',
            'ekanban_stock_limit.min',
            'ekanban_stock_limit.max',
            'ekanban_stock_limit.plant',
            'ekanban_param_tbl.qty_kanban'
        )
            ->leftJoin('ekanban_param_tbl', 'ekanban_stock_limit.itemcode', '=', 'ekanban_param_tbl.item_code')
            ->where('ekanban_stock_limit.id', '=', $id)
            ->where('ekanban_stock_limit.is_active', '1')
            ->first();
        // dd($getView);
        $getlot1 = $getView->qty_kanban;
        $getmin = $getView->min;
        $getmax = $getView->max;
        $min_boxes = ceil($getmin / $getlot1);
        $max_boxes = floor($getmax / $getlot1);
        $getItemcode = Ekanban_stock_limit::select('itemcode')
            ->where('id', $id)
            ->first();
        $getItemcode = $getItemcode->itemcode;
        date_default_timezone_set("Asia/Jakarta");
        $date =  Carbon::now()->format('m-Y');
        // dd($date);

        $getQt = ekanban_fg_chuter_tbl::where('item_code', $getItemcode)
            ->where('mpname', $date)
            ->select('balance')
            ->first();
        // dd($getQt);
        $balance = $getQt ? $getQt->balance : 0;
        // dd($balance);

        $result = [
            'getView' => $getView,
            'totalQty' => $balance,
            'min_boxes' => $min_boxes,
            'max_boxes' => $max_boxes,
        ];

        // dd($totalQty);
        return response()->json($result);
    }

    public function master_minmax_edit($id)
    {
        // dd($id);
        $getEdit = Ekanban_stock_limit::select(
            'ekanban_stock_limit.chutter_address',
            'ekanban_stock_limit.itemcode',
            'ekanban_stock_limit.part_number',
            'ekanban_stock_limit.part_name',
            'ekanban_stock_limit.part_type',
            'ekanban_stock_limit.cust_code',
            'ekanban_stock_limit.stock_type',
            'ekanban_stock_limit.min',
            'ekanban_stock_limit.max',
            'ekanban_stock_limit.plant',
            'ekanban_param_tbl.qty_kanban'
        )
            ->leftJoin('ekanban_param_tbl', 'ekanban_stock_limit.itemcode', '=', 'ekanban_param_tbl.item_code')
            ->where('ekanban_stock_limit.id', '=', $id)
            ->where('ekanban_stock_limit.is_active', '1')
            ->get();
        // dd($getEdit);
        $getItemcode = Ekanban_stock_limit::select('itemcode')
            ->where('id', $id)
            ->first();
        $getItemcode = $getItemcode->itemcode;
        date_default_timezone_set("Asia/Jakarta");
        $date =  Carbon::now()->format('m-y');
        // dd($date);
        // dd($getQt);
        $getQt = ekanban_fg_chuter_tbl::where('item_code', $getItemcode)
            ->where('mpname', $date)
            ->select('balance')
            ->first();

        $balance = $getQt ? $getQt->balance : 0;
        // dd($balance);

        $result = [
            'getEdit' => $getEdit,
            'totalQty' => $balance,
        ];

        // dd($totalQty);
        return response()->json($result);
    }

    public function validasi_multiple_edit(Request $request)
    {
        // dd($request);
        $itemcode = $request->itemCode;
        $getStok = Ekanban_param_tbl::where('item_code', $itemcode)

            ->select('qty_kanban')
            ->first();
        // dd($getStok);
        return response()->json($getStok);
    }

    public function validasi_itemcode(Request $request)
    {

        // dd($request);

        // dd($period);
        $itemCodecheck = $request->itemCodecheck;
        $validasiItemcode = Ekanban_stock_limit::where('itemcode', $itemCodecheck)

            ->where('is_active', '1')
            ->select('itemcode')
            ->first();
        // $itemcode = $validasiItemcode->itemcode ?? "";
        // dd($validasiItemcode);
        return response()->json($validasiItemcode);
    }

    public function validasi_chutter_edit1(Request $request)
    {
        // dd($request);
        // $id_minmax = $request->id_minmax;
        $chutter_edit = $request->chutter_edit;
        $validasiChutter = Ekanban_stock_limit::where('chutter_address', $chutter_edit)
            ->where('is_active', '1')
            ->select('chutter_address', 'id')
            ->first();
        // dd($validasiChutter);

        // dd($validasiChutter);
        // Mengembalikan JSON response
        if (!$validasiChutter) {
            return response()->json("-");
        } else {
            return response()->json([
                'success' => true,
                'data' => $validasiChutter,
                'message' => 'Chutter found successfully.'
            ]);
        }
    }

    public function masterminmax_update(Request $request)
    {
        // dd($request);

        date_default_timezone_set("Asia/Jakarta");
        $max_edit = $request->max_edit;
        $period = Carbon::now()->format('Y-m');
        // dd($period);
        $action_date = Carbon::now();
        $is_active = '1';
        $action_name = 'UPDATE';
        $action_user = Auth::user()->UserID;

        // UPDATE MASTER MIN MAX LOG
        $column_name_min = "min";
        $column_name_max = "max";

        // log min
        $id_minmax_edit = $request->id_minmax_edit;
        $old_value_min = Ekanban_stock_limit::where('id', $request->id_minmax_edit)
            ->select('min')->first();
        $old_value_min = $old_value_min->min;
        // dd($old_value_min);
        $addLog_min = Ekanban_stock_limit_log::create([
            'table_id' => $id_minmax_edit,
            'action_name' => $action_name,
            'column_name' => $column_name_min,
            'old_value' => $old_value_min,
            'new_value' =>  $request->min_edit,
            'action_date' =>  $action_date,
            'action_user' => $action_user
        ]);

        // log max
        $id_minmax_edit = $request->id_minmax_edit;
        $old_value_max = Ekanban_stock_limit::where('id', $request->id_minmax_edit)
            ->select('max')->first();
        $old_value_max = $old_value_max->max;
        $addLog_max = Ekanban_stock_limit_log::create([
            'table_id' => $id_minmax_edit,
            'action_name' => $action_name,
            'column_name' => $column_name_max,
            'old_value' => $old_value_max,
            'new_value' =>  $request->max_edit,
            'action_date' =>  $action_date,
            'action_user' => $action_user
        ]);

        // UPDATE MASTER MIN MAX
        $updateMaster = Ekanban_stock_limit::where('id', $request->id_minmax_edit)->update([
            'chutter_address' =>  strtoupper($request->chutter_edit),
            'period'       => $period,
            'itemcode'       => $request->itemcode_edit,
            'part_number'       => $request->partno_edit,
            'part_name'       => $request->partname_edit,
            'part_type'       => $request->part_type_edit,
            'min'       => $request->min_edit,
            'max'       => $max_edit,
            'is_active'       => $is_active,
            'action_name'       => $action_name,
            'action_user'       => $action_user,
            'action_date'       => $action_date,


        ]);
        // dd($updateMaster);
        return response()->json([
            'success' => true
        ]);
    }

    public function masterminmax_log($id)
    {
        $getLog = DB::connection('ekanban')
            ->table('ekanban_stock_limit_log')
            ->leftJoin('ekanban_stock_limit', 'ekanban_stock_limit.id', '=', 'ekanban_stock_limit_log.table_id')
            ->where('ekanban_stock_limit_log.table_id', '=', $id)
            ->select('ekanban_stock_limit_log.*', 'ekanban_stock_limit.chutter_address', 'ekanban_stock_limit.itemcode')
            ->get();

        // dd($getLog);
        // return Datatables::of($viewLog);
        echo json_encode($getLog);
        // exit;
    }

    public function master_minmax_void($id)
    {
        // dd($id);
        date_default_timezone_set("Asia/Jakarta");
        DB::beginTransaction();
        try {

            $data = DB::connection('ekanban')->table('ekanban_stock_limit')
                ->where('id', '=', $id)
                ->update([
                    // 'action_date' => Carbon::now(),
                    'period' => null,
                    'is_active' => '0',
                    'action_name' => "VOID"
                ]);
            // dd($data);
            $date = Carbon::now();
            $tabel_id = $id;
            $action_name = "VOID";
            $userstaff = Auth::user()->UserID;
            // $note = 'Wh :' . $select->types . '/' . 'Item:'. $get_count .'/'. 'Qty:'. ' ' . $select->quantity;
            $log_void = DB::connection('ekanban')->table('ekanban_stock_limit_log')->insert([
                'table_id' => $tabel_id,
                'action_name' => $action_name,
                'action_user' => $userstaff,
                'action_date' => $date,

            ]);
            // dd($log_void);
            DB::commit();
            return response()->json([
                'success' => true,
            ]);
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back();
        }
    }
    public function kanbanExcel(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");
        $mpname = Carbon::now()->format('m-Y');
        $date = Carbon::now()->format('Y-m-d H:i:s');
        $date_format = Carbon::now()->format('d_m_Y');
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        return Excel::download(new ReportMinmax($mpname, $date), 'Report_master_min_max_' . $date_format . '.xlsx');
    }
    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls', // Sesuaikan dengan ekstensi file yang diizinkan
            // Sesuaikan validasi dengan kebutuhan Anda
        ]);

        // Ambil file Excel dari request
        $file = $request->file('excel_file');
        // dd($request);

        // Proses impor menggunakan Laravel Excel
        try {

            Excel::import(new ImportUpdateMinMax, $file);

            // Proses berhasil
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            // Proses gagal, tangani kesalahan di sini
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
    public function changeKanban(Request $request)
    {

        $noKanban_change = $request->noKanban_change;
        $seq_change = $request->seq_change;
        $noKanban_new = $request->noKanban_new;
        $seq_new = $request->seq_new;

        // Query untuk mendapatkan data dari database
        $getChuter = ekanban_fgin_tbl::where('kanban_no', $noKanban_change)
            ->where('seq', $seq_change)
            ->select('chutter_address')
            ->first();

        if (!$getChuter) {
            // Jika data tidak ditemukan, kirim respons JSON dengan pesan error
            return response()->json(['status' => 'error', 'message' => 'Data not found'], 404);
        } else {

            // Update chutter_address untuk kanban baru
            $newKanban = ekanban_fgin_tbl::where('kanban_no', $noKanban_new)
                ->where('seq', $seq_new)
                ->update([
                    'chutter_address' => $getChuter->chutter_address,
                ]);

            $update_log = Chuter_in_out_log::where('kanban_no', $noKanban_change)
                ->where('seq', $seq_change)
                ->update([
                    'seq' => $seq_new,
                ]);
            // dd($update_log);
            if ($newKanban > 0) {
                // Update chutter_address untuk kanban lama
                $changeKanban = ekanban_fgin_tbl::where('kanban_no', $noKanban_change)
                    ->where('seq', $seq_change)
                    ->update([
                        'chutter_address' => null,
                    ]);
                // Jika kedua update berhasil
                return response()->json(['status' => 'success', 'message' => 'Kanban successfully changed']);
            } else {
                // Jika salah satu atau kedua update gagal
                return response()->json(['status' => 'error', 'message' => 'Failed to change Kanban']);
            }
        }
    }

    public function getFilter(Request $request)
    {
        if ($request->ajax()) {
            // Mendapatkan nilai filter dari permintaan
            $chuter = $request->input('chuter');
            $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');

            // Inisialisasi query utama
            $query = ekanban_fgin_tbl::query()
                ->leftJoin('chuter_in_out_log', function ($join) {
                    $join->on('ekanban_fgin_tbl.kanban_no', '=', 'chuter_in_out_log.kanban_no')
                        ->on('ekanban_fgin_tbl.seq', '=', 'chuter_in_out_log.seq');
                })
                ->select(
                    'ekanban_fgin_tbl.part_no',
                    'ekanban_fgin_tbl.kanban_no',
                    'ekanban_fgin_tbl.qty',
                    'ekanban_fgin_tbl.seq',
                    'ekanban_fgin_tbl.kanban_print as creation_date',
                    DB::raw("REPLACE(ekanban_fgin_tbl.chutter_address, '_out', '') AS chutter_address"),
                    'chuter_in_out_log.in_datetime',
                    'chuter_in_out_log.out_datetime'
                )
                ->where('ekanban_fgin_tbl.chutter_address', '!=', ''); // Hanya jika masih diperlukan

            // Filter berdasarkan chuter_address
            if ($chuter) {
                $query->where('ekanban_fgin_tbl.chutter_address', 'like', "%$chuter%");
            }
            // Filter berdasarkan tanggal in_chuter
            if ($fromDate && $toDate) {
                $toDatePlusOne = date('Y-m-d', strtotime($toDate . ' +1 day'));
                $query->whereBetween('chuter_in_out_log.in_datetime', [$fromDate, $toDatePlusOne]);
            } elseif ($fromDate) {
                $query->where('chuter_in_out_log.in_datetime', '>=', $fromDate);
            }

            // Eksekusi query dan kirimkan hasilnya ke DataTables
            $data = $query->get();

            // Kembalikan hasilnya ke DataTables
            return DataTables::of($data)->make(true);
        }
    }
    public function filterChuterexcel(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");
        $chuter = $request->chuter;
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;

        // Query untuk mendapatkan data sesuai filter
        $query = ekanban_fgin_tbl::query()
            ->leftJoin('chuter_in_out_log', function ($join) {
                $join->on('ekanban_fgin_tbl.kanban_no', '=', 'chuter_in_out_log.kanban_no')
                    ->on('ekanban_fgin_tbl.seq', '=', 'chuter_in_out_log.seq');
            })
            ->select(
                'ekanban_fgin_tbl.part_no',
                'ekanban_fgin_tbl.kanban_no',
                'ekanban_fgin_tbl.qty',
                'ekanban_fgin_tbl.seq',
                'ekanban_fgin_tbl.kanban_print as creation_date',
                DB::raw("REPLACE(ekanban_fgin_tbl.chutter_address, '_out', '') AS chutter_address"),
                'chuter_in_out_log.in_datetime',
                'chuter_in_out_log.out_datetime'
            )
            ->where('ekanban_fgin_tbl.chutter_address', '!=', ''); // Hanya jika masih diperlukan

        // Filter berdasarkan chuter_address
        if ($chuter) {
            $query->where('ekanban_fgin_tbl.chutter_address', 'like', "%$chuter%");
        }
        // Filter berdasarkan tanggal in_chuter
        if ($fromDate && $toDate) {
            $toDatePlusOne = date('Y-m-d', strtotime($toDate . ' +1 day'));
            $query->whereBetween('chuter_in_out_log.in_datetime', [$fromDate, $toDatePlusOne]);
        } elseif ($fromDate) {
            $query->where('chuter_in_out_log.in_datetime', '>=', $fromDate);
        }

        // Eksekusi query dan konversi hasilnya menjadi array
        $data = $query->get()->toArray();

        if (empty($data)) {
            // Jika data tidak ditemukan, kirimkan JSON response ke Ajax
            return response()->json(["message" => "No data found"]);
        } else {
            $date_format = date('Y-m-d H:i:s');
            $export = new ReportFilterchuter($data, $fromDate, $toDate, $date_format);
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            return Excel::download($export, 'Report_Chuter_' . $fromDate . '_' . $toDate . '.xlsx');
        }
    }
}
//
