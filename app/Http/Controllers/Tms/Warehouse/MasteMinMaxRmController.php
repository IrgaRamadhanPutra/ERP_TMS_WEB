<?php

namespace App\Http\Controllers\Tms\Warehouse;

use App\Exports\ReportMinMaxRm;
use App\Http\Controllers\Controller;
use App\Imports\ImportCreateMinMaxRm;
use App\Models\Ekanban\Ekanban_param_tbl;
use App\Models\Ekanban\Ekanban_stock_limit_rm;
use App\Models\Ekanban\Ekanban_stock_limit_rm_log;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class MasteMinMaxRmController extends Controller
{
    //
    public function indexMasterRm()
    {

        return view('tms.warehouse.min-max-rm.index');
    }
    public function get_master_min_max_rm_datatables(Request $request)
    {
        $getUrl = config('app.sap_get_stock_all');
        $username = config('app.user_sap');
        $password = config('app.pass_sap');
        $time_limit = config('app.timeout_ex');
        $apiUrl = $getUrl . '&PLANT=1701&SLOC=1010';

        $apiResponse = Http::withBasicAuth($username, $password)->timeout($time_limit)->get($apiUrl);
        $apiData = $apiResponse->json();

        if (is_object($apiData)) {
            $apiData = json_decode(json_encode($apiData), true);
        }

        $apiOutput = $apiData['it_output'] ?? [];
        $plant = $request->plant;

        $stock_type = "R/M";
        $get_stock_limit_rm = Ekanban_stock_limit_rm::select(
            'itemcode',
            DB::raw('MAX(id) as id'),
            DB::raw('MAX(chutter_address) as chutter_address'),
            DB::raw('MAX(part_number) as part_number'),
            DB::raw('MAX(part_name) as part_name'),
            DB::raw('MAX(part_type) as part_type'),
            DB::raw('MAX(cust_code) as cust_code'),
            DB::raw('MAX(min) as min'),
            DB::raw('MAX(max) as max'),
            DB::raw('MAX(action_date) as action_date')
        )
            ->where('is_active', '=', '1')
            ->where('stock_type', $stock_type);

        if ($plant) {
            $get_stock_limit_rm->where('plant', $plant);
        }

        $get_stock_limit_rm = $get_stock_limit_rm
            ->groupBy('itemcode')
            ->orderBy('action_date', 'desc')
            ->get();

        $getdata = collect();
        foreach ($get_stock_limit_rm as $eloquentItem) {
            $materialFromApi = collect($apiOutput)->firstWhere('material_no', $eloquentItem['itemcode']);

            if ($materialFromApi) {
                $getdata->push([
                    'id' => $eloquentItem['id'],
                    'chutter_address' => $eloquentItem['chutter_address'],
                    'part_number' => $eloquentItem['part_number'],
                    'part_name' => $eloquentItem['part_name'],
                    'itemcode' => $eloquentItem['itemcode'],
                    'cust_code' => $eloquentItem['cust_code'],
                    'min' => $eloquentItem['min'],
                    'max' => $eloquentItem['max'],
                    'material_desc' => $materialFromApi['material_desc'],
                    'balance' => $materialFromApi['quantity'],
                    'satuan' => $materialFromApi['satuan'],
                    'quantity_plant' => $materialFromApi['quantity_plant'],
                    'action_date' => $eloquentItem['action_date']
                ]);
            } else {
                $getdata->push([
                    'id' => $eloquentItem['id'],
                    'chutter_address' => $eloquentItem['chutter_address'],
                    'part_number' => $eloquentItem['part_number'],
                    'part_name' => $eloquentItem['part_name'],
                    'itemcode' => $eloquentItem['itemcode'],
                    'cust_code' => $eloquentItem['cust_code'],
                    'min' => $eloquentItem['min'],
                    'max' => $eloquentItem['max'],
                    'material_desc' => null,
                    'balance' => 0,
                    'satuan' => null,
                    'quantity_plant' => 0,
                    'action_date' => $eloquentItem['action_date']
                ]);
            }
        }
        // dd($getdata);
        return DataTables::of($getdata->map(function ($item) {
            return (object) $item;
        }))
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return view('tms.warehouse.min-max-rm.action_datatables.action_datatables', [
                    'model' => $data,
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function validasi_itemcode(Request $request)
    {
        // \Log::info('Request received:', $request->all());

        $validasi_itemcode = Ekanban_stock_limit_rm::select('itemcode')
            ->where('itemcode', $request->itemcode)
            ->first();

        $response = $validasi_itemcode === null ? "null" : "not_null";

        // \Log::info('Response:', ['status' => $response]);
        return response()->json(['status' => $response]);
    }


    public function store_master_min_max_rm(Request $request)
    {
        // Validasi input (opsional, tambahkan jika diperlukan)
        $request->validate([
            'chutter_create' => 'required|string',
            'itemcode_create' => 'required|string',
            'partno_create' => 'required|string',
            'partname_create' => 'required|string',
            'part_type_create' => 'required|string',
            'stock_type_create' => 'required|string',
            'unit_create' => 'required|string',
            'plant_create' => 'required|string',
            'cust_code_create' => 'required|string',
            'min_create' => 'required|numeric',
            'max_create' => 'required|numeric',
        ]);

        // Persiapkan data untuk disimpan ke database
        date_default_timezone_set("Asia/Jakarta");

        $data = [
            'chutter_address' => strtoupper($request->chutter_create),
            'period' => Carbon::now()->format('Y-m'), // Contoh: 2024-11
            'itemcode' => strtoupper($request->itemcode_create),
            'part_number' => $request->partno_create,
            'part_name' => $request->partname_create,
            'part_type' => $request->part_type_create,
            'cust_code' => $request->cust_code_create,
            'min' => $request->min_create,
            'max' => $request->max_create,
            'unit' => $request->unit_create,
            'plant' => $request->plant_create,
            'is_active' => 1, // Default 1
            'action_name' => 'INSERT', // Default "INSERT"
            'stock_type' => $request->stock_type_create,
            'action_user' => Auth::user()->UserID, // User login saat ini
            'action_date' => Carbon::now(), // Timestamp saat ini
        ];
        // dd($data);

        DB::beginTransaction();

        try {
            date_default_timezone_set("Asia/Jakarta");
            $peroid = Carbon::now()->format('Y-m');
            // Simpan data ke database
            $record = Ekanban_stock_limit_rm::create($data)
                ->where('period', $peroid);
            $data_id = Ekanban_stock_limit_rm::select('id')
                ->where('is_active', 1)
                ->where('itemcode', $request->itemcode_create)
                ->first();
            $data_id = $data_id->id;
            $action_name = "INSERT";
            $time = Carbon::now();
            $column_name = "";
            $old_value =  "";
            $new_value = "";
            $userstaff = Auth::user()->UserID;
            // $note = 'Code :' . $code . '/' . 'FOH cost:' . $foh;

            $addLog = Ekanban_stock_limit_rm_log::create([
                'table_id' => $data_id,
                'action_name' => $action_name,
                'column_name' => $column_name,
                'old_value' => $old_value,
                'new_value' =>  $new_value,
                'action_date' =>  $time,
                'action_user' => $userstaff
            ]);
            // Commit transaksi jika berhasil
            DB::connection('ekanban')->commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan.',
                'data' => $record,
            ], 201);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::connection('ekanban')->rollback();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function view_min_max_rm($id)
    {
        // 1. Ambil data dari konfigurasi dan API
        $getUrl = config('app.sap_get_stock_all');
        $username = config('app.user_sap');
        $password = config('app.pass_sap');
        $time_limit = config('app.timeout_ex');
        $apiUrl = $getUrl . '&PLANT=1701&SLOC=1010';

        $apiResponse = Http::withBasicAuth($username, $password)
            ->timeout($time_limit)
            ->get($apiUrl);
        $apiData = $apiResponse->json();

        if (is_object($apiData)) {
            $apiData = json_decode(json_encode($apiData), true);
        }

        $apiOutput = $apiData['it_output'] ?? [];

        // 2. Ambil data dari database menggunakan Eloquent
        $get_data_view = Ekanban_stock_limit_rm::select(
            'id',
            'chutter_address',
            'itemcode',
            'part_number',
            'part_name',
            'part_type',
            'stock_type',
            'plant',
            'cust_code',
            'min',
            'max'
        )
            ->where('is_active', '=', '1')
            ->where('id', $id)
            ->first();

        if (!$get_data_view) {
            return response()->json(['status' => 'error', 'message' => 'Data not found'], 404);
        }

        // 3. Gabungkan data dari API dan database
        $getdata = collect();
        $materialFromApi = collect($apiOutput)->firstWhere('material_no', $get_data_view->itemcode);

        $getdata->push([
            'id' => $get_data_view->id,
            'chutter_address' => $get_data_view->chutter_address,
            'part_number' => $get_data_view->part_number,
            'part_name' => $get_data_view->part_name,
            'itemcode' => $get_data_view->itemcode,
            'part_type' => $get_data_view->part_type,
            'cust_code' => $get_data_view->cust_code,
            'stock_type' => $get_data_view->stock_type,
            'plant' => $get_data_view->plant,
            'min' => $get_data_view->min,
            'max' => $get_data_view->max,
            'material_desc' => $materialFromApi['material_desc'] ?? null,
            'unit' => $materialFromApi['satuan'] ?? null,
        ]);

        // dd($getdata);
        // 4. Kembalikan data gabungan
        return response()->json(['status' => 'success', 'data' => $getdata], 200);
    }
    public function edit_min_max_rm($id)
    {
        // 1. Ambil data dari konfigurasi dan API
        $getUrl = config('app.sap_get_stock_all');
        $username = config('app.user_sap');
        $password = config('app.pass_sap');
        $time_limit = config('app.timeout_ex');
        $apiUrl = $getUrl . '&PLANT=1701&SLOC=1010';

        $apiResponse = Http::withBasicAuth($username, $password)
            ->timeout($time_limit)
            ->get($apiUrl);
        $apiData = $apiResponse->json();

        if (is_object($apiData)) {
            $apiData = json_decode(json_encode($apiData), true);
        }

        $apiOutput = $apiData['it_output'] ?? [];

        // 2. Ambil data dari database menggunakan Eloquent
        $get_data_edit = Ekanban_stock_limit_rm::select(
            'id',
            'chutter_address',
            'itemcode',
            'part_number',
            'part_name',
            'part_type',
            'stock_type',
            'plant',
            'cust_code',
            'min',
            'max'
        )
            ->where('is_active', '=', '1')
            ->where('id', $id)
            ->first();

        if (!$get_data_edit) {
            return response()->json(['status' => 'error', 'message' => 'Data not found'], 404);
        }

        // 3. Gabungkan data dari API dan database
        $getdata = collect();
        $materialFromApi = collect($apiOutput)->firstWhere('material_no', $get_data_edit->itemcode);

        $getdata->push([
            'id' => $get_data_edit->id,
            'chutter_address' => $get_data_edit->chutter_address,
            'part_number' => $get_data_edit->part_number,
            'part_name' => $get_data_edit->part_name,
            'itemcode' => $get_data_edit->itemcode,
            'part_type' => $get_data_edit->part_type,
            'cust_code' => $get_data_edit->cust_code,
            'stock_type' => $get_data_edit->stock_type,
            'plant' => $get_data_edit->plant,
            'min' => $get_data_edit->min,
            'max' => $get_data_edit->max,
            'material_desc' => $materialFromApi['material_desc'] ?? null,
            'unit' => $materialFromApi['satuan'] ?? null,
        ]);

        // dd($getdata);
        // 4. Kembalikan data gabungan
        return response()->json(['status' => 'success', 'data' => $getdata], 200);
    }
    public function master_min_max_rm_update(Request $request)
    {
        try {
            // Memulai transaksi pada koneksi 'ekanban'
            DB::connection('ekanban')->beginTransaction();

            // Inisialisasi variabel
            date_default_timezone_set("Asia/Jakarta");
            $action_date = Carbon::now();
            $is_active = '1';
            $action_name = 'UPDATE';
            $action_user = Auth::user()->UserID;
            $chutter_edit = $request->chutter_edit;
            $min_old = $request->min_old;
            $min_edit = $request->min_edit;
            $max_edit = $request->max_edit;
            $max_old = $request->max_old;

            // Name column for ekanban stock limit rm log
            $column_name_min = "min";
            $column_name_max = "max";

            // Update stock limit RM using Eloquent
            $updateMaster = Ekanban_stock_limit_rm::where('id', $request->id_raw)
                ->where('is_active', $is_active)
                ->update([
                    'chutter_address' => strtoupper($chutter_edit),
                    'min' => $min_edit,
                    'max' => $max_edit,
                    'action_name' => $action_name,
                    'action_date' => $action_date
                ]);

            // Check if the update was successful
            if (!$updateMaster) {
                throw new \Exception('Failed to update stock limit RM.');
            }

            // Log changes for max value
            Ekanban_stock_limit_rm_log::create([
                'table_id' => $request->id_raw,
                'action_name' => $action_name,
                'column_name' => $column_name_max,
                'old_value' => $max_old,
                'new_value' => $max_edit,
                'action_date' => $action_date,
                'action_user' => $action_user
            ]);

            // Log changes for min value
            Ekanban_stock_limit_rm_log::create([
                'table_id' => $request->id_raw,
                'action_name' => $action_name,
                'column_name' => $column_name_min,
                'old_value' => $min_old,
                'new_value' => $min_edit,
                'action_date' => $action_date,
                'action_user' => $action_user
            ]);

            // Commit transaction if all queries succeed
            DB::connection('ekanban')->commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data successfully updated.'
            ], 200);
        } catch (\Exception $e) {
            // Rollback transaction if any error occurs
            DB::connection('ekanban')->rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function master_min_max_rm_void(Request $request)
    {
        // dd($request);

        // dd($id);
        date_default_timezone_set("Asia/Jakarta");

        DB::beginTransaction();
        try {
            date_default_timezone_set("Asia/Jakarta");
            // $get_itemcode = $request->code_void;
            $get_id = (int) $request->id; // Casting ke integer
            $is_active = 0;
            $action_name = 'DELETE';
            $action_user = Auth::user()->UserID;
            $action_date = Carbon::now();

            // Update ekanban stock limit rm
            $update_param = Ekanban_stock_limit_rm::where('id', $get_id)
                ->update(['is_active' => $is_active]);

            // INSERT DATA RAW ACTION NAME "DELET" PARAMETER ID
            Ekanban_stock_limit_rm_log::create([
                'table_id' => $request->id_raw,
                'action_name' => $action_name,
                'action_date' => $action_date,
                'action_user' => $action_user
            ]);




            // Commit the transaction
            DB::connection('ekanban')->commit();

            return response()->json([
                'success' => true,
            ]);
        } catch (Exception $ex) {
            // Rollback jika terjadi error
            DB::connection('ekanban')->rollback();

            return redirect()->back()->withErrors(['error' => $ex->getMessage()]);
        }
    }

    public function master_min_max_rm_log($id)
    {
        $getLog = DB::connection('ekanban')
            ->table('ekanban_stock_limit_rm_log')
            ->leftJoin('ekanban_stock_limit_rm', 'ekanban_stock_limit_rm.id', '=', 'ekanban_stock_limit_rm_log.table_id')
            ->where('ekanban_stock_limit_rm_log.table_id', '=', $id)
            ->select('ekanban_stock_limit_rm_log.*', 'ekanban_stock_limit_rm.chutter_address', 'ekanban_stock_limit_rm.itemcode')
            ->get();

        // dd($getLog);
        // return Datatables::of($viewLog);
        echo json_encode($getLog);
        // exit;
    }

    public function exportExcel(Request $request)
    {
        // dd($request);
        $plant = $request->plant;
        // dd($plant);
        date_default_timezone_set("Asia/Jakarta");
        $mpname = Carbon::now()->format('m-Y');
        $date = Carbon::now()->format('Y-m-d H:i:s');
        $date_format = Carbon::now()->format('d_m_Y');
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        return Excel::download(new ReportMinMaxRm($mpname, $date, $plant), 'Report_master_min_max_' . $date_format . '.xlsx');
    }

    public function import_excel_create_rm(Request $request)
    {

        // Ambil file Excel dari request
        $file = $request->file('importFile');
        // dd($file);
        // dd($file);
        try {

            Excel::import(new ImportCreateMinMaxRm, $file);

            // Proses berhasil
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            // Proses gagal, tangani kesalahan di sini
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
}
