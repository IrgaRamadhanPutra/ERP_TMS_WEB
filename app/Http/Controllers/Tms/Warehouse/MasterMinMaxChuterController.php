<?php

namespace App\Http\Controllers\Tms\Warehouse;

use App\Http\Controllers\Controller;
use App\Imports\ImportCreateMinMaxChuter;
use App\Models\Ekanban\Ekanban_chuter_limit;
use App\Models\Ekanban\Ekanban_chuter_limit_log;
use App\Models\Ekanban\Ekanban_fg_tbl;
use App\Models\Ekanban\Ekanban_param_tbl;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class MasterMinMaxChuterController extends Controller
{
    //
    public function index_master_chuter()
    {
        return view('tms.warehouse.chuter-minmax.index');
    }

    public function getmaster_min_max_chuter_datatables(Request $request)
    {
        // dd($request);
        if ($request->ajax()) {
            // $mpname = Carbon::now()->format('m-Y');
            // left joint 3 tbl ekanban_chuter_limit,ekanban_fg_tbl,ekanban_param_tbl
            $data = Ekanban_chuter_limit::select(
                'ekanban_chuter_limit.chutter_address as chutter_address',
                DB::raw('MAX(ekanban_chuter_limit.id) as id'),
                DB::raw('MAX(ekanban_chuter_limit.kanban_no) as kanban_no'),
                DB::raw('MAX(ekanban_chuter_limit.itemcode) as itemcode'),
                DB::raw('MAX(ekanban_chuter_limit.part_number) as part_number'),
                DB::raw('MAX(ekanban_chuter_limit.part_name) as part_name'),
                DB::raw('MAX(ekanban_chuter_limit.part_type) as part_type'),
                DB::raw('MAX(ekanban_chuter_limit.min) as min'),
                DB::raw('MAX(ekanban_chuter_limit.max) as max'),
                DB::raw('latest_fg_tbl.balance as stock'),
                DB::raw('MAX(ekanban_param_tbl.qty_kanban) as lot'),
                DB::raw('MAX(ekanban_chuter_limit.action_date) as action_date')
            )
                // Subquery untuk mendapatkan data itemcode terbaru berdasarkan creation_date
                ->leftJoin(
                    DB::raw('(SELECT item_code, balance
                                 FROM ekanban_fg_tbl
                                 WHERE (item_code, creation_date) IN
                                       (SELECT item_code, MAX(creation_date)
                                        FROM ekanban_fg_tbl
                                        GROUP BY item_code)
                                ) as latest_fg_tbl'),
                    function ($join) {
                        $join->on('ekanban_chuter_limit.itemcode', '=', 'latest_fg_tbl.item_code');
                    }
                )
                ->leftJoin('ekanban_param_tbl', 'ekanban_chuter_limit.itemcode', '=', 'ekanban_param_tbl.item_code')
                ->where('ekanban_chuter_limit.is_active', '1')
                ->groupBy('ekanban_chuter_limit.chutter_address')
                ->orderBy('ekanban_chuter_limit.action_date', 'desc')
                ->get();


            // dd($data);
            return DataTables::of($data)
                // ->addColumn('custom', '')
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->editColumn('action', function ($data) {
                    return view('tms.warehouse.chuter-minmax.action_datatables.action_datatables', [
                        'model' => $data,
                        // 'url_print' => route('tms.warehouse.stock_out_entry_report', base64_encode($data->jmesin))
                    ]);
                })
                ->make(true);
        }
    }
    public function validasi_chutter(Request $request)
    {
        // dd($request);
        $chutterValue = $request->chutterValue;
        $validasiChutter = Ekanban_chuter_limit::where('chutter_address', $chutterValue)
            ->where('is_active', '1')
            ->select('chutter_address')
            ->first();

        $chutterAddress = $validasiChutter->chutter_address ?? "";

        // dd($chutterAddress);
        return response()->json($validasiChutter);
    }

    public function getItemcodeekanbanparam(Request $request)
    {
        // dd($request);
        if ($request->ajax()) {
            $data = Ekanban_param_tbl::select('item_code', 'ekanban_no', 'part_no', 'part_name', 'model', 'qty_kanban', 'customer')
                ->where('item_code', 'like', '1.%') // Menambahkan klausa WHERE
                ->get()
                ->toArray();

            // dd($data);
            return Datatables::of($data)->make(true);
        }
    }

    public function validasi_multiple(Request $request)
    {
        // dd($request);
        $kanban_no = $request->kanbanNo;
        $itemcode = $request->itemCode;
        $getStok = Ekanban_param_tbl::where('item_code', $itemcode)
            ->where('ekanban_no', $kanban_no)
            ->select('qty_kanban')
            ->first();
        // dd($getStok);
        return response()->json($getStok);
    }

    public function store_masterminmax_chuter(Request $request)
    {
        // dd($request);
        // Set timezone to Asia/Jakarta
        date_default_timezone_set("Asia/Jakarta");

        // Begin a transaction using the 'ekanban' database connection
        DB::connection('ekanban')->beginTransaction();

        try {
            // Extract data from the request
            $chutter_create = strtoupper($request->chutter_create);
            $kanban_no_create = $request->kanban_no_create;
            $itemcode_create = $request->itemcode_create;
            $partno_create = $request->partno_create;
            $partname_create = $request->partname_create;
            $part_type_create = $request->part_type_create;
            $min_create = $request->min_create;
            $max_create = $request->max_create;
            $period = Carbon::now()->format('Y-m');
            $cust_code = $request->cust_code;
            $is_active = '1';
            $action_name = 'INSERT';
            $action_user = Auth::user()->UserID;
            $action_date = Carbon::now();

            // Create a new record in the 'ekanban_chuter_limit' table
            $data = Ekanban_chuter_limit::create([
                'chutter_address' => $chutter_create,
                'period' => $period,
                'kanban_no' => $kanban_no_create,
                'itemcode' => $itemcode_create,
                'part_number' => $partno_create,
                'part_name' => $partname_create,
                'part_type' => $part_type_create,
                'cust_code' => $cust_code,
                'min' => $min_create,
                'max' => $max_create,
                'is_active' => $is_active,
                'action_name' => $action_name,
                'action_user' => $action_user,
                'action_date' => $action_date
            ]);

            // Retrieve the ID of the created record
            $data_id = $data->id;

            // Log the action in the 'ekanban_chuter_limit_log' table
            $addLog = Ekanban_chuter_limit_log::create([
                'table_id' => $data_id,
                'action_name' => $action_name,
                'column_name' => '',
                'old_value' => '',
                'new_value' => '',
                'action_date' => $action_date,
                'action_user' => $action_user
            ]);

            // Commit the transaction if no errors occur
            DB::connection('ekanban')->commit();

            return response()->json(['message' => 'Record created successfully.']);
        } catch (\Exception $e) {
            // An error occurred, rollback the transaction
            DB::connection('ekanban')->rollback();

            // // Log the error message
            // Log::error('Error creating record: ' . $e->getMessage());

            // Return an error response with a 400 status code
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 400);
        }
    }

    public function import_excel_create(Request $request)
    {
        // dd($request);

        // Ambil file Excel dari request
        $file = $request->file('importFile');
        // dd($file);
        try {

            Excel::import(new ImportCreateMinMaxChuter, $file);

            // Proses berhasil
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            // Proses gagal, tangani kesalahan di sini
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
    public function show_view_masterminmax_chuter($id)
    {
        // dd($request = $request->id);
        $getView = Ekanban_chuter_limit::select(
            'ekanban_chuter_limit.chutter_address',
            'ekanban_chuter_limit.itemcode',
            'ekanban_chuter_limit.part_number',
            'ekanban_chuter_limit.part_name',
            'ekanban_chuter_limit.part_type',
            'ekanban_chuter_limit.cust_code',
            'ekanban_chuter_limit.min',
            'ekanban_chuter_limit.max',
            'ekanban_param_tbl.qty_kanban'
        )
            ->leftJoin('ekanban_param_tbl', 'ekanban_chuter_limit.itemcode', '=', 'ekanban_param_tbl.item_code')
            ->where('ekanban_chuter_limit.id', '=', $id)
            ->where('ekanban_chuter_limit.is_active', '1')
            ->first();
        // dd($getView);
        $getlot1 = $getView->qty_kanban;
        $getmin = $getView->min;
        $getmax = $getView->max;
        $min_boxes = ceil($getmin / $getlot1);
        $max_boxes = floor($getmax / $getlot1);
        $getItemcode = Ekanban_chuter_limit::select('itemcode')
            ->where('id', $id)
            ->first();
        $getItemcode = $getItemcode->itemcode;
        date_default_timezone_set("Asia/Jakarta");
        $date =  Carbon::now()->format('m-Y');
        // dd($date);

        $getQt = Ekanban_fg_tbl::where('item_code', $getItemcode)
            // ->where('mpname', $date)
            ->select('balance')
            ->orderBy('creation_date','desc')
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

        dd($result);
        return response()->json($result);
    }
}
