<?php

namespace App\Http\Controllers\Tms\Warehouse;

use App\Http\Controllers\Controller;
use App\Imports\ImportCreateMasterAddressRm;
use App\Models\Ekanban\Master_address_rm;
use App\Models\Ekanban\Master_address_rm_log_tbl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class MasterAddressRmController extends Controller
{
    //
    public function index_master_address_rm()
    {
        return view('tms.warehouse.master-address-rm.index');
    }
    public function master_address_rm_datatables(Request $request)
    {
        // dd($request);
        if ($request->ajax()) {
            $data = Master_address_rm::select(
                'itemcode',
                DB::raw('MAX(id) as id'),
                DB::raw('MAX(chuter_address) as chuter_address'),
                DB::raw('MAX(part_no) as part_no'),
                DB::raw('MAX(part_name) as part_name'),
                // DB::raw('MAX(part_type) as part_type'),
                DB::raw('MAX(process_code) as process_code'), // Hapus atau ganti
                DB::raw('MAX(cust_code) as cust_code'),
                DB::raw('MAX(supplier) as supplier'),
                DB::raw('MAX(address_type) as address_type')
            )
                ->where('void_date', null)
                ->orderBy('created_date', 'desc')
                ->groupBy('itemcode', 'chuter_address');

            // dd($data);
            return DataTables::of($data)
                // ->addColumn('custom', '')
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->editColumn('action', function ($data) {
                    return view('tms.warehouse.master-address-rm.action_datatables.action_datatables', [
                        'model' => $data,
                    ]);
                })
                ->make(true);
        }
    }
    public function get_address_pdf($id_database, $chuter_address)
    {
        // dd($id_database);
        // dd($chuter_address);
        // Set timezone ke Asia/Jakarta
        date_default_timezone_set("Asia/Jakarta");
        $date = Carbon::now()->format('d-m-Y H:i:s');

        // Query untuk mendapatkan data berdasarkan itemcode dan chuter_address
        $data_qr = Master_address_rm::select(
            'itemcode',
            DB::raw('MAX(id) as id'),
            DB::raw('MAX(chuter_address) as chuter_address'),
            DB::raw('MAX(part_no) as part_no'),
            DB::raw('MAX(part_name) as part_name'),
            DB::raw('SUBSTRING_INDEX(MAX(process_code), " ", 1) as process_code'),
            DB::raw('MAX(cust_code) as cust_code'),
            DB::raw('MAX(supplier) as supplier'),
            DB::raw('MAX(address_type) as address_type')
        )
            ->whereNull('void_date') // Use `whereNull` for cleaner syntax
            ->where('id', $id_database) // Gunakan itemcode dari URL
            ->where('chuter_address', $chuter_address) // Gunakan chuter_address dari URL
            ->orderBy('created_date', 'desc')
            ->groupBy('itemcode', 'chuter_address') // Group by both itemcode and chuter_address
            ->first(); // Fetch only one record

        // Gabungkan data dengan koma
        $qrData = implode(',', [
            // $data_qr->itemcode,
            $data_qr->chuter_address,
            // $data_qr->part_no,
            $data_qr->part_name
            // $data_qr->process_code,
            // $data_qr->cust_code,
            // $data_qr->supplier,
            // $data_qr->address_type
        ]);
        // dd($qrData);/*  */
        // Generate QR code berdasarkan data yang digabung
        $qrcode = QrCode::size(100)->generate($qrData);

        // Load PDF view dengan QR code dan data
        $pdf = PDF::loadView('tms.warehouse.master-address-rm.generate_pdf.pdf', [
            'data' => $data_qr,
            'qrcode' => $qrcode,
            'date' => $date
        ])->setPaper('a4', 'landscape');

        // Return PDF untuk didownload atau ditampilkan
        return $pdf->stream('kanban_address.pdf');
    }

    public function store_master_address_rm(Request $request)
    {
        // Data to store
        $dataToStore = [
            'chuter_address' => $request->input('chutter_create'),
            'itemcode' => $request->input('itemcode_create'),
            'part_no' => $request->input('partno_create'),
            'part_name' => $request->input('partname_create'),
            'part_type' => $request->input('part_type_create'),
            'process_code' => $request->input('process_code_create'),
            'cust_code' => $request->input('cust_code_create'),
            'supplier' => $request->input('supplier_create'),
            'address_type' => $request->input('stock_type_create'),
            'plant' => $request->input('plant_create'),
            'action_name' => Auth::user()->UserID,
            'created_date' => carbon::now(),
            'status' => 'ACTIVE',
        ];

        // Start transaction
        DB::connection('ekanban')->beginTransaction();

        try {
            // Insert data
            $store_address_id = Master_address_rm::insertGetId($dataToStore);
            // dd($store_address_id);
            // Log the action
            Master_address_rm_log_tbl::create([
                'tbl_id' => $store_address_id,
                'chuter_address' => $request->input('chutter_create'),
                'status_change' => 'INSERT',
                'create_date' => Carbon::now(),
                'create_user' => Auth::user()->UserID,
            ]);

            DB::connection('ekanban')->commit();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan']);
        } catch (\Exception $e) {
            // Rollback transaction if an error occurs
            DB::connection('ekanban')->rollBack();

            // Log the error with detailed information
            logger()->error('Error occurred while saving data: ' . $e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine());

            // Return the detailed error message to the frontend
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function view_master_address_rm($id)
    {
        // dd($id);
        $get_data_view = Master_address_rm::select(
            'chuter_address',
            'itemcode',
            'part_no',
            'part_name',
            'part_type',
            'process_code',
            'cust_code',
            'supplier',
            'address_type',
            'plant'
        )
            ->where('status', '=', 'ACTIVE')
            ->whereNull('void_date')
            ->where('id', $id)
            ->first();

        // dd($get_data_view);
        return response()->json(['status' => 'success', 'data' => $get_data_view], 200);
    }
    public function edit_master_address_rm($id)
    {
        // dd($id);
        $get_data_edit = Master_address_rm::select(
            'chuter_address',
            'itemcode',
            'part_no',
            'part_name',
            'part_type',
            'process_code',
            'cust_code',
            'supplier',
            'address_type',
            'plant'
        )
            ->where('status', '=', 'ACTIVE')
            ->whereNull('void_date')
            ->where('id', $id)
            ->first();

        // dd($get_data_edit);
        return response()->json(['status' => 'success', 'data' => $get_data_edit], 200);
    }

    public function update_master_address_rm(Request $request)
    {
        // dd($request);
        try {
            // Memulai transaksi pada koneksi 'ekanban'
            DB::connection('ekanban')->beginTransaction();

            $id = $request->id_update;
            $chutter_edit = strtoupper($request->chutter_edit);
            $action_name = 'UPDATE';
            $action_date = Carbon::now();

            // Update Master_address_rm data
            $updateMaster = Master_address_rm::where('id', $id)
                ->where('status', 'ACTIVE') // Status aktif
                ->update([
                    'chuter_address' => $chutter_edit,
                    'update_by' => Auth::user()->UserID, // Nama field diperbaiki
                    'update_date' => $action_date
                ]);

            // Log perubahan ke Master_address_rm_log_tbl
            Master_address_rm_log_tbl::create([
                'tbl_id' => $id,
                'chuter_address' => $chutter_edit,
                'status_change' => $action_name,
                'create_date' => $action_date,
                'create_user' => Auth::user()->UserID,
            ]);

            // Commit transaksi
            DB::connection('ekanban')->commit();

            return response()->json([
                'success' => true,
                'message' => 'Data successfully updated.'
            ], 200);
        } catch (\Exception $e) {
            // Rollback jika terjadi error
            DB::connection('ekanban')->rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function master_address_rm_log($itemcode)
    {
        // Mengambil data dengan left join dari tabel Master_address_rm dan Master_address_rm_log_tbl
        $get_data = Master_address_rm::leftJoin('master_address_rm_log_tbl', 'master_address_rm.id', '=', 'master_address_rm_log_tbl.tbl_id')
            ->where('master_address_rm.itemcode', $itemcode) // Filter berdasarkan ID yang diberikan
            ->select(
                // 'master_address_rm.id',
                'master_address_rm.itemcode',

                'master_address_rm_log_tbl.status_change',
                'master_address_rm_log_tbl.chuter_address',
                'master_address_rm_log_tbl.create_user',
                'master_address_rm_log_tbl.create_date'
            )
            ->where('status', '=', 'ACTIVE')
            ->get(); // Mengambil satu data berdasarkan ID
        echo json_encode($get_data);
    }
    public function master_address_void($id)
    {

        DB::beginTransaction();
        try {
            date_default_timezone_set("Asia/Jakarta");
            $get_id = (int) $id; // Casting ke integer
            $status = 'NOT ACTIVE';
            $void_date = Carbon::now();
            // UpdateMaster_address_rm
            $update_master = Master_address_rm::where('id', $get_id)
                ->update([
                    'status' => $status,
                    'void_date' => $void_date
                ]);
            // dd($update_master);
            // Insert data ke Ekanban Parameter Log
            $statusChange = "DELETE";

            $date = Carbon::now();
            $userstaff = Auth::user()->UserID;

            Master_address_rm_log_tbl::create([
                'tbl_id' => $get_id,
                'status_change' => $statusChange,
                'create_date' => $date,
                'create_user' => $userstaff,
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
    public function import_excel_create_master_address(Request $request)
    {
        // dd($request);
        // Ambil file Excel dari request
        $file = $request->file('importFile');
        // dd($file);
        // dd($file);
        try {

            Excel::import(new ImportCreateMasterAddressRm, $file);

            // Proses berhasil
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            // Proses gagal, tangani kesalahan di sini
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
}
