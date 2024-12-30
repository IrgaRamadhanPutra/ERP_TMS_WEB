<?php

namespace App\Http\Controllers\Tms\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ekanban\Ekanban_param_log_tbl;
use App\Models\Ekanban\Ekanban_param_tbl;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MasterKanbanController extends Controller
{
    //
    public function index()
    {
        return view('tms.warehouse.master-kanban.index');
    }
    public function get_masterkanban_datatables(Request $request)
    {
        if ($request->ajax()) {
            $branch = $request->branch;
            // dd($request);
            $data = Ekanban_param_tbl::select(
                'id',
                'ekanban_no',
                'item_code',
                'part_no',
                'part_name',
                'model',
                'customer',
                'sloc',
                'qty_kanban',
                'created_by',
                'creation_date'
            )
                ->where('part_status', '=', 1)
                ->orderBy('creation_date', 'desc');
            if ($branch) {
                $data->where('branch', $branch);
            }

            // Execute the query
            $data = $data->get();

            // ->toArray();
            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) { // Tambahkan kolom 'action' secara dinamis
                    return view('tms.master.master-kanban.action_datatables.action_datatables', [
                        'model' => $data,
                    ]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function validasi_itemcode(Request $request)
    {
        // Get 'itemcode' from the request data
        $itemcode = $request->input('itemcode');

        // Retrieve data based on 'item_code'
        $validasi_param = Ekanban_param_tbl::select('ekanban_no', 'item_code')
            ->where('item_code', $itemcode)
            ->first();

        // Check if 'ekanban_no' exists and modify it or return not found status
        if ($validasi_param && $validasi_param->ekanban_no !== null) {
            $ekanban_no_modified = $validasi_param->ekanban_no . '.1';
            return response()->json([
                'status' => 'success',
                'ekanban_no' => $ekanban_no_modified,
            ]);
        } else {
            return response()->json([
                'status' => 'success',  // Changed from 'error' to 'success'
                'ekanban_no' => null,   // Return null for 'ekanban_no' if not found
            ]);
        }
    }

    public function generate_kanban_no(Request $request)
    {
        // Retrieve 'lineCode' from the request
        // Retrieve 'lineCode' from the request
        $lineCode = $request->input('lineCode');

        // Determine the value of $setKanban based on $lineCode
        if ($lineCode === 'PQA') {
            $setKanban = 'SC';
        } elseif ($lineCode === 'ASSY') {
            $setKanban = 'AS';
        } elseif ($lineCode === 'PRESS') {
            $setKanban = 'PS';
        } elseif ($lineCode === 'WELDING') {
            $setKanban = 'WD';
        } elseif ($lineCode === 'SPOT') {
            $setKanban = 'SP';
        } elseif ($lineCode === 'QC') {
            $setKanban = 'QC';
        } else {
            $setKanban = ''; // Default value if no match is found
        }

        // Output the result for debugging

        // Query to get the latest ekanban_no for the specified line code
        $get_kanban_no = Ekanban_param_tbl::where('ekanban_no', 'like', $setKanban . '%')
            ->select('ekanban_no')
            ->orderBy('ekanban_no', 'desc')
            ->first();

        // dd($get_kanban_no);
        $ekanbanNo = $get_kanban_no->ekanban_no;
        $splite1 = substr($ekanbanNo, 0, 3); // AS.
        $splite2 = (int) substr($ekanbanNo, 3); // Convert "0637" to integer 637

        // Increment part2 by 1 and format it back to a 4-digit string
        $result_splite = sprintf('%04d', $splite2 + 1);

        $newEkanbanNo = $splite1 . $result_splite;
        // dd($newEkanbanNo);
        return response()->json([
            'status' => 'success',
            'ekanban_no' => $newEkanbanNo,
        ]);
    }
    public function store_masterkanban(Request $request)
    {
        // dd($request);
        try {
            // Begin transaction
            DB::beginTransaction();
            // GET SLOC
            $get_line_code = $request->line_code_create;

            // Pisahkan string berdasarkan tanda "="
            list($line_code, $sloc) = explode('=', $get_line_code);

            // Store data in ekanban_param_tbl
            $store_param = Ekanban_param_tbl::create([
                'production_line' => $request->production_line_create,
                'line_code' => $line_code,
                'item_code' => $request->itemcode_create,
                'ekanban_no' => $request->kanban_create,
                'part_no' => $request->partno_create,
                'part_name' => $request->partname_create,
                'model' => $request->part_type_create,
                'customer' => $request->cust_create,
                'qty_kanban' => $request->qty_create,
                'part_status' => '1',
                'kanban_type' => $request->kanban_type_create,
                'base_unit' => $request->base_unit_create,
                'created_by' => Auth::user()->UserID, // Authenticated user
                'creation_date' => Carbon::now('Asia/Jakarta'), // Set Jakarta timezone
                'sloc' => $sloc,
                'branch' => $request->branch_create
            ]);

            // Define additional variables
            $status_change = "ADD";
            $lot_size = "1";

            // Store data in ekanban_param_log_tbl
            $store_param_log = Ekanban_param_log_tbl::create([
                'status_change' => $status_change,
                'ekanban_no' => $request->kanban_create,
                'item_code' => $request->itemcode_create,
                'part_no' => $request->partno_create,
                'part_name' => $request->partname_create,
                'model' => $request->part_type_create,
                'customer' => $request->cust_create,
                'line_code' => $line_code,
                'lot_size' => $lot_size,
                'qty_kanban' => $request->qty_create,
                'part_status' => '1',
                'created_by' => Auth::user()->UserID, // Authenticated user
                'creation_date' => Carbon::now('Asia/Jakarta') // Set Jakarta timezone
            ]);

            // Commit the transaction
            DB::connection('ekanban')->commit();

            // Return a success JSON response
            return response()->json([
                'status' => 'success',
                'message' => 'Data added successfully',
            ]);
        } catch (\Exception $e) {
            // Rollback transaction in case of an error
            DB::connection('ekanban')->rollback();

            // Return an error JSON response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function view_kanban($id)
    {
        $getData = Ekanban_param_tbl::select(
            'production_line',
            'line_code',
            'item_code',
            'ekanban_no',
            'part_no',
            'part_name',
            'model',
            'qty_kanban',
            'customer',
            'kanban_type',
            'base_unit',
            'branch'
        )
            ->where('id', $id)
            ->where('part_status', '=', '1')
            ->first();

        return response()->json($getData);
    }
    public function edit_kanban($id)
    {
        $getData = Ekanban_param_tbl::select(
            'production_line',
            'line_code',
            'item_code',
            'ekanban_no',
            'part_no',
            'part_name',
            'model',
            'qty_kanban',
            'customer',
            'kanban_type',
            'base_unit',
            'branch'
        )
            ->where('id', $id)
            ->where('part_status', '=', '1')
            ->first();
        // dd($getData);
        return response()->json($getData);
    }

    public function update_masterkanban(Request $request)
    {
        $idUpdate = (int) $request->input('id_update');
        $productionLineEdit = $request->input('production_line_edit');
        $lineCodeEdit = $request->input('line_code_edit');
        $itemCodeEdit = $request->input('itemcode_edit');
        $kanbanEdit = $request->input('kanban_edit');
        $partNoEdit = $request->input('partno_edit');
        $partNameEdit = $request->input('partname_edit');
        $partTypeEdit = $request->input('part_type_edit');
        $qtyEdit = $request->input('qty_edit');
        $custEdit = $request->input('cust_edit');
        $branchEdit = $request->input('branch_edit');
        $kanbanTypeEdit = $request->input('kanban_type_edit');
        $baseUnitEdit = $request->input('base_unit_edit');

        // Determine SLOC based on line_code_edit
        // Determine SLOC based on line_code_edit
        $sloc = '';

        if ($branchEdit == 1701) {
            if ($lineCodeEdit === 'PQA') {
                $sloc = '1080';
            } elseif (in_array($lineCodeEdit, ['ASSY', 'WELDING', 'SPOT'])) {
                $sloc = '1320';
            } elseif ($lineCodeEdit === 'PRESS') {
                $sloc = '1310';
            } elseif ($lineCodeEdit === 'QC') {
                $sloc = '1050';
            }
        } elseif ($branchEdit == 1702) {
            if ($lineCodeEdit === 'PQA') {
                $sloc = '2080';
            } elseif (in_array($lineCodeEdit, ['ASSY', 'WELDING', 'SPOT'])) {
                $sloc = '2320';
            } elseif ($lineCodeEdit === 'PRESS') {
                $sloc = '2310';
            } elseif ($lineCodeEdit === 'QC') {
                $sloc = '2050';
            }
        }

        DB::beginTransaction();

        try {
            // Update Ekanban Parameter
            $updateParam = Ekanban_param_tbl::where('id', $idUpdate)->update([
                'line_code' => $lineCodeEdit,
                'last_updated_by' => Auth::user()->UserID,
                'last_updated_date' => Carbon::now('Asia/Jakarta'),
                'sloc' => $sloc,
                'qty_kanban' => $qtyEdit
            ]);

            // Insert into Ekanban Parameter Log
            $statusChange = "EDIT";
            $lotSize = "1";

            $upadateParamLog = Ekanban_param_log_tbl::create([
                'status_change' => $statusChange,
                'ekanban_no' => $kanbanEdit,
                'item_code' => $itemCodeEdit,
                'part_no' => $partNoEdit,
                'part_name' => $partNameEdit,
                'model' => $partTypeEdit,
                'customer' => $custEdit,
                'line_code' => $lineCodeEdit,
                'lot_size' => $lotSize,
                'qty_kanban' => $qtyEdit,
                'part_status' => '1',
                'created_by' => Auth::user()->UserID,
                'creation_date' => Carbon::now('Asia/Jakarta')
            ]);

            // Commit the transaction
            DB::connection('ekanban')->commit();


            return response()->json([
                'success' => true,
                'message' => 'Kanban updated successfully!'
            ]);
        } catch (\Exception $e) {
            // Rollback transaction in case of an error
            DB::connection('ekanban')->rollback();

            return response()->json([
                'success' => false,
                'message' => 'Error updating Kanban: ' . $e->getMessage()
            ], 500);
        }
    }

    public function master_kanban_log($itemcode)
    {
        // dd($itemcode);

        $getData = Ekanban_param_log_tbl::where('item_code', $itemcode)
            ->select('ekanban_no', 'item_code', 'part_no', 'part_name', 'model', 'customer', 'qty_kanban', 'created_by', 'creation_date')
            ->get();
        // dd($getData);
        echo json_encode($getData);
    }

    public function master_kanban_void(Request $request)
    {
        // dd($request);

        // dd($id);
        date_default_timezone_set("Asia/Jakarta");

        DB::beginTransaction();
        try {
            $get_itemcode = $request->code_void;
            $get_id = (int) $request->id; // Casting ke integer
            $part_status = 0;

            // Update ekanban_param_tbl
            $update_param = Ekanban_param_tbl::where('id', $get_id)
                ->update(['part_status' => $part_status]);

            // Ambil data ekanban_param_tbl berdasarkan item_code
            $data = Ekanban_param_tbl::select('ekanban_no', 'item_code', 'part_no', 'part_name', 'model', 'customer', 'qty_kanban')
                ->where('item_code', $get_itemcode)
                ->first();

            // Insert data ke Ekanban Parameter Log
            $statusChange = "DELETE";
            $lotSize = "1";
            $date = Carbon::now();
            $userstaff = Auth::user()->UserID;

            $upadateParamLog = Ekanban_param_log_tbl::create([
                'status_change' => $statusChange,
                'ekanban_no' => $data->ekanban_no,
                'item_code' => $data->item_code,
                'part_no' => $data->part_no,
                'part_name' => $data->part_name,
                'model' => $data->model,
                'customer' => $data->customer,
                'line_code' => null, // Sesuaikan jika ada nilai untuk line_code
                'lot_size' => $lotSize,
                'qty_kanban' => $data->qty_kanban,
                'part_status' => '1',
                'created_by' => $userstaff,
                'creation_date' => $date
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
}
