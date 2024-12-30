<?php

namespace App\Http\Controllers\Tms\Warehouse;

use App\Exports\ReportStock;
use App\Http\Controllers\Controller;
use App\Models\Dbtbs\Entry_Log_Transaction_Sap;
use App\Models\Dbtbs\Tbl_Sloc;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class ReportStockController extends Controller
{
    //
    public function index_report_stock(Request $request)
    {
        // Retrieve the 'plant' input value
        $plant = $request->input('plant');

        // Debugging to see the plant value
        // dd($plant);

        // Fetch plants from Tbl_Sloc table
        $get_plant = Tbl_Sloc::select('plant')->groupBy('plant')->get();

        // Handle AJAX requests (if any)
        if ($request->ajax() && $request->has('plant')) {
            $selected_plant = $request->input('plant');
            $last_digit = substr($selected_plant, -1);  // Get the last digit of the plant
            $get_sloc = Tbl_Sloc::select('sloc')
                ->where('sloc', 'LIKE', "{$last_digit}%")
                ->groupBy('sloc')
                ->get();
            return response()->json($get_sloc);
        }

        // Fetch SLOCs from Tbl_Sloc table
        $get_sloc = Tbl_Sloc::groupBy('sloc')->get();

        // Return the view with the fetched data
        return view('tms.warehouse.Report-stock.index', compact('get_sloc', 'get_plant'));
    }

    public function getStockData(Request $request)
    {
        // dd($request);
        // dd($request->all());
        // $password = 'Einvoice01';
        // $username = 'dpm-einvc';
        $sapClient = env('SAP_API_CLIENT');
        $sapUsername = env('SAP_API_USERNAME');
        $sapPassword = env('SAP_API_PASSWORD');
        $get_branch = Auth::user()->Branch;

        // default page
        // Determine the plant based on the branch
        if ($get_branch == "HO") {
            $get_plant = "1701";
        } else {
            $get_plant = "1702";
        }

        // Get input values
        $plant = $request->input('plant');
        $slocNo = $request->input('slocNo');

        // Construct the API URL based on the values of slocNo and plant
        if (!is_null($plant) && is_null($slocNo)) {
            // If plant is not null and slocNo is null
            $apiUrl = 'http://erpqas-dp.dharmap.com:8001/sap/zapi/ZMM_TCH_GET_STOCK?MATERIAL_NO=02G500SWM1RAW-SHE&PLANT=' . $plant . '&batch&sap-client=' . $sapClient . '&SLOC=1320';
        } elseif (!is_null($slocNo) && is_null($plant)) {
            // If slocNo is not null and plant is null
            $apiUrl = 'http://erpqas-dp.dharmap.com:8001/sap/zapi/ZMM_TCH_GET_STOCK?MATERIAL_NO=02G500SWM1RAW-SHE&PLANT=' . $get_plant . '&batch&sap-client=' . $sapClient . '&SLOC=' . $slocNo;
        } elseif (!is_null($plant) && !is_null($slocNo)) {
            // If both plant and slocNo are not null
            $apiUrl = 'http://erpqas-dp.dharmap.com:8001/sap/zapi/ZMM_TCH_GET_STOCK?MATERIAL_NO=02G500SWM1RAW-SHE&PLANT=' . $plant . '&batch&sap-client=' . $sapClient . '&SLOC=' . $slocNo;
        } else {
            // If both plant and slocNo are null
            $apiUrl = 'http://erpqas-dp.dharmap.com:8001/sap/zapi/ZMM_TCH_GET_STOCK?MATERIAL_NO=02G500SWM1RAW-SHE&PLANT=' . $get_plant . '&batch&sap-client=' . $sapClient . '&SLOC=1320';
        }
        // dd($apiUrl);
        // Melakukan permintaan API dengan timeout
        try {
            // Mengirim request HTTP dengan authentikasi dasar dan batas waktu 60 detik
            $response = Http::withBasicAuth($sapUsername, $sapPassword)
                ->timeout(180) // Set timeout to 180 seconds
                ->get($apiUrl);
            // Mendekode respons JSON ke dalam array asosiatif
            $responseBody = json_decode($response->body(), true);
            // Memfilter data dari respons
            $filteredData = $responseBody['it_output'];
            // dd($filteredData);

            // Mengembalikan data yang diformat untuk DataTables
            return DataTables::of($filteredData)->make(true);
        } catch (\Exception $e) {
            // Tangani pengecualian timeout atau kesalahan lainnya
            $errorMessage = $e->getMessage(); // Mendapatkan pesan error dari Exception

            // Simpan pesan error ke dalam log transaksi
            $created_date = Carbon::now();
            $module = "Report Stock Tms - GET";
            $log_message = $errorMessage;

            Entry_Log_Transaction_Sap::create([
                'created_date' => $created_date,
                'module' => $module,
                'log_message' => $log_message
            ]);

            // Mengirimkan pesan error kembali ke AJAX
            return response()->json(['error' => $errorMessage], 500);
        }
    }
    public function report_slocExcel(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");
        $sapClient = env('SAP_API_CLIENT');
        $sapUsername = env('SAP_API_USERNAME');
        $sapPassword = env('SAP_API_PASSWORD');
        $get_branch = Auth::user()->Branch;

        // default page
        // Determine the plant based on the branch
        if ($get_branch == "HO") {
            $get_plant = "1701";
        } else {
            $get_plant = "1702";
        }

        // Get input values
        $plant = $request->input('plant');
        $slocNo = $request->input('slocNo');

        // Construct the API URL based on the values of slocNo and plant
        if (!is_null($plant) && is_null($slocNo)) {
            // If plant is not null and slocNo is null
            $apiUrl = 'http://erpqas-dp.dharmap.com:8001/sap/zapi/ZMM_TCH_GET_STOCK?MATERIAL_NO=02G500SWM1RAW-SHE&PLANT=' . $plant . '&batch&sap-client=' . $sapClient . '&SLOC=1320';
        } elseif (!is_null($slocNo) && is_null($plant)) {
            // If slocNo is not null and plant is null
            $apiUrl = 'http://erpqas-dp.dharmap.com:8001/sap/zapi/ZMM_TCH_GET_STOCK?MATERIAL_NO=02G500SWM1RAW-SHE&PLANT=' . $get_plant . '&batch&sap-client=' . $sapClient . '&SLOC=' . $slocNo;
        } elseif (!is_null($plant) && !is_null($slocNo)) {
            // If both plant and slocNo are not null
            $apiUrl = 'http://erpqas-dp.dharmap.com:8001/sap/zapi/ZMM_TCH_GET_STOCK?MATERIAL_NO=02G500SWM1RAW-SHE&PLANT=' . $plant . '&batch&sap-client=' . $sapClient . '&SLOC=' . $slocNo;
        } else {
            // If both plant and slocNo are null
            $apiUrl = 'http://erpqas-dp.dharmap.com:8001/sap/zapi/ZMM_TCH_GET_STOCK?MATERIAL_NO=02G500SWM1RAW-SHE&PLANT=' . $get_plant . '&batch&sap-client=' . $sapClient . '&SLOC=1320';
        }
        try {
            // Mengirim request HTTP dengan authentikasi dasar dan batas waktu 60 detik
            $response = Http::withBasicAuth($sapUsername, $sapPassword)
                ->timeout(180) // Set timeout to 180 seconds
                ->get($apiUrl);

            // Mendekode respons JSON ke dalam array asosiatif
            $responseBody = json_decode($response->body(), true);

            // Memeriksa apakah data kosong
            if (empty($responseBody['it_output'])) {
                return response()->json(["message" => "Data Not Found"], 404);
            }

            // Jika data tidak kosong, lanjutkan untuk memproses dan mengembalikan data
            $filteredData = $responseBody['it_output'];
            $date_format = date('Y-m-d H:i:s');
            $export = new ReportStock($filteredData, $date_format, $plant, $slocNo);

            // Mengunduh file Excel
            return Excel::download($export, 'Report_Stock_Sloc_' . $date_format . '.xlsx');
        } catch (\Exception $e) {
            // Tangani pengecualian timeout atau kesalahan lainnya
            $errorMessage = $e->getMessage(); // Mendapatkan pesan error dari Exception

            // Simpan pesan error ke dalam log transaksi
            $created_date = Carbon::now();
            $module = "Report Stock Tms - GET";
            $log_message = $errorMessage;

            Entry_Log_Transaction_Sap::create([
                'created_date' => $created_date,
                'module' => $module,
                'log_message' => $log_message
            ]);

            // Mengirimkan pesan error kembali ke AJAX
            return response()->json(['error' => $errorMessage], 500);
        }
    }
}
