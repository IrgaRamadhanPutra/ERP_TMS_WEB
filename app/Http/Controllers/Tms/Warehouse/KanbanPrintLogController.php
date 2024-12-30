<?php

namespace App\Http\Controllers\Tms\Warehouse;

use App\Exports\ReportFgprinted;
use App\Exports\ReportWipprinted;
use App\Http\Controllers\Controller;
use App\Models\Ekanban\Ekanban_Fgprinted_Log;
use App\Models\Ekanban\Ekanban_wipprinted_log_tbl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Svg\Tag\Rect;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Facades\Excel;

class KanbanPrintLogController extends Controller
{
    //
    public function index_printlog()
    {
        $get_kanban_Itemcode1 = Ekanban_Fgprinted_Log::select('ekanban_no', 'item_code')
            ->groupBy('ekanban_no', 'item_code')
            ->get();
        $get_kanban_Itemcode2 = Ekanban_wipprinted_log_tbl::select('ekanban_no', 'item_code')
            ->groupBy('ekanban_no', 'item_code')
            ->get();
        $get_createdBy1 = Ekanban_Fgprinted_Log::select('created_by')
            ->groupBy('created_by')
            ->get();
        $get_createdBy2 = Ekanban_wipprinted_log_tbl::select('created_by')
            ->groupBy('created_by')
            ->get();
        return view('tms.warehouse.Kanban-print-log.index', compact('get_kanban_Itemcode1', 'get_kanban_Itemcode2', 'get_createdBy1', 'get_createdBy2'));
    }

    public function get_kanban_fg_print_log_datatables(Request $request)
    {
        if ($request->ajax()) {
            $kanbanNofg = $request->kanbanNofg;
            $itemCodefg = $request->itemCodefg;
            $fromDatefg = $request->fromDatefg;
            $toDatefg = $request->toDatefg;
            $statusDocsenfg = $request->statusDocsenfg;
            $createdByfg = $request->createdByfg;
            // dd($statusDocsenfg);
            // Get the base query from the model
            $query = Ekanban_Fgprinted_Log::getQueryWithJoin();
            // logika kanban no
            // dd($query);
            if ($kanbanNofg) {
                $query->where('ekanban_fgprinted_log_tbl.ekanban_no', 'like', "%$kanbanNofg%");
            }
            if ($itemCodefg) {
                $query->where('ekanban_fgprinted_log_tbl.item_code', $itemCodefg);
            }
            // logika created by
            if ($createdByfg) {
                $query->where('ekanban_fgprinted_log_tbl.created_by', $createdByfg);
            }
            // logika status
            if ($statusDocsenfg == "NULL") {
                $query->whereNull('ekanban_fgprinted_log_tbl.doc_no_rec');
            } elseif ($statusDocsenfg == "NOT_NULL") {
                $query->whereNotNull('ekanban_fgprinted_log_tbl.doc_no_rec');
            } elseif ($statusDocsenfg == "0") {
                $query->where('ekanban_fgprinted_log_tbl.doc_no_rec', $statusDocsenfg);
            }

            // logika  from date and to date
            if ($fromDatefg && $toDatefg) {
                // Tambahkan satu hari ke toDatefg
                $toDatefgPlusOne = date('Y-m-d', strtotime($toDatefg . ' +1 day'));
                $query->whereBetween('ekanban_fgprinted_log_tbl.creation_date', [$fromDatefg, $toDatefgPlusOne]);
            } elseif ($fromDatefg) {
                $query->where('ekanban_fgprinted_log_tbl.creation_date', '>=', $fromDatefg);
            }
            // Gunakan DataTables untuk paginasi dan filter otomatis
            return DataTables::eloquent($query)->make(true);
        }
    }
    public function report_excelfgprint(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");
        $kanbanNofg = $request->kanbanNofg;
        $itemCodefg = $request->itemCodefg;
        $fromDatefg = $request->fromDatefg;
        $toDatefg = $request->toDatefg;
        $statusDocsenfg = $request->statusDocsenfg;
        $createdByfg = $request->createdByfg;

        // Get the base query from the model
        $query = Ekanban_Fgprinted_Log::getQueryWithJoin();

        // Apply filters based on the request parameters
        if ($kanbanNofg) {
            $query->where('ekanban_fgprinted_log_tbl.ekanban_no', $kanbanNofg);
        }
        if ($itemCodefg) {
            $query->where('ekanban_fgprinted_log_tbl.item_code', $itemCodefg);
        }
        if ($createdByfg) {
            $query->where('ekanban_fgprinted_log_tbl.created_by', $createdByfg);
        }
        if ($statusDocsenfg == "NULL") {
            $query->whereNull('ekanban_fgprinted_log_tbl.doc_no_rec');
        } elseif ($statusDocsenfg == "NOT_NULL") {
            $query->whereNotNull('ekanban_fgprinted_log_tbl.doc_no_rec');
        } elseif ($statusDocsenfg == "0") {
            $query->where('ekanban_fgprinted_log_tbl.doc_no_rec', $statusDocsenfg);
        }

        if ($fromDatefg && $toDatefg) {
            $toDatefgPlusOne = date('Y-m-d', strtotime($toDatefg . ' +1 day'));
            $query->whereBetween('ekanban_fgprinted_log_tbl.creation_date', [$fromDatefg, $toDatefgPlusOne]);
        } elseif ($fromDatefg) {
            $query->where('ekanban_fgprinted_log_tbl.creation_date', '>=', $fromDatefg);
        }

        // Use chunking for large datasets
        $data = [];
        $query->chunk(1000, function ($chunk) use (&$data) {
            foreach ($chunk as $item) {
                $data[] = $item;
            }
        });

        // Check if the collection is empty
        if (empty($data)) {
            // If no data found, send JSON response to Ajax
            return response()->json(["message" => "No data found"]);
        } else {
            $date_format = date('Y-m-d H:i:s');

            // Convert data to an array
            $dataArray = array_map(function ($item) {
                return $item->toArray();
            }, $data);

            // Pass the array to the export class
            $export = new ReportFgprinted($dataArray, $fromDatefg, $toDatefg, $date_format, $createdByfg, $kanbanNofg, $itemCodefg);
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            return Excel::download($export, 'Report_Fgprinted_' . $fromDatefg . '_' . $toDatefg . '.xlsx');
        }
    }


    public function get_kanban_wip_print_log_datatables(Request $request)
    {
        // dd($request);
        if ($request->ajax()) {
            $kanbanNowip = $request->kanbanNowip;
            $itemCodewip = $request->itemCodewip;
            $fromDatewip = $request->fromDatewip;
            $toDatewip = $request->toDatewip;
            $statusDocsenwip = $request->statusDocsenwip;
            $createdBywip = $request->createdBywip;

            // Get the base query from the model
            $query = Ekanban_wipprinted_log_tbl::getQueryWithJoin();
            // dd($query);
            // logika kanban no
            if ($kanbanNowip) {
                $query->where('ekanban_wipprinted_log_tbl.ekanban_no', 'like', "%$kanbanNowip%");
            }
            if ($itemCodewip) {
                $query->where('ekanban_wipprinted_log_tbl.item_code', $itemCodewip);
            }
            // logika created by
            if ($createdBywip) {
                $query->where('ekanban_wipprinted_log_tbl.created_by', $createdBywip);
            }
            // logika from and to date
            if ($fromDatewip && $toDatewip) {
                // Tambahkan satu hari ke toDatewip
                $toDatewipPlusOne = date('Y-m-d', strtotime($toDatewip . ' +1 day'));
                $query->whereBetween('ekanban_wipprinted_log_tbl.creation_date', [$fromDatewip, $toDatewipPlusOne]);
            } elseif ($fromDatewip) {
                $query->where('ekanban_wipprinted_log_tbl.creation_date', '>=', $fromDatewip);
            }
            // logika status
            if ($statusDocsenwip == "NULL") {
                $query->whereNull('ekanban_wipprinted_log_tbl.doc_no_rec');
            } elseif ($statusDocsenwip == "NOT_NULL") {
                $query->whereNotNull('ekanban_wipprinted_log_tbl.doc_no_rec');
            } elseif ($statusDocsenwip == "0") {
                $query->where('ekanban_wipprinted_log_tbl.doc_no_rec', $statusDocsenwip);
            }

            // Eksekusi query dan kirimkan hasilnya ke DataTables
            // $data = $query->get()->toArray();
            // dd($data);
            return DataTables::eloquent($query)->make(true);
        }
    }


    public function report_excelwipprint(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");
        $kanbanNowip = $request->kanbanNowip;
        $itemCodewip = $request->itemCodewip;
        $fromDatewip = $request->fromDatewip;
        $toDatewip = $request->toDatewip;
        $statusDocsenwip = $request->statusDocsenwip;
        $createdBywip = $request->createdBywip;

        // Get the base query from the model
        $query = Ekanban_wipprinted_log_tbl::getQueryWithJoin();
        // dd($query);
        // logika kanban no
        if ($kanbanNowip) {
            $query->where('ekanban_wipprinted_log_tbl.ekanban_no', 'like', "%$kanbanNowip%");
        }
        if ($itemCodewip) {
            $query->where('ekanban_wipprinted_log_tbl.item_code', $itemCodewip);
        }
        // logika created by
        if ($createdBywip) {
            $query->where('ekanban_wipprinted_log_tbl.created_by', $createdBywip);
        }
        // logika from and to date
        if ($fromDatewip && $toDatewip) {
            // Tambahkan satu hari ke toDatewip
            $toDatewipPlusOne = date('Y-m-d', strtotime($toDatewip . ' +1 day'));
            $query->whereBetween('ekanban_wipprinted_log_tbl.creation_date', [$fromDatewip, $toDatewipPlusOne]);
        } elseif ($fromDatewip) {
            $query->where('ekanban_wipprinted_log_tbl.creation_date', '>=', $fromDatewip);
        }
        // logika status
        if ($statusDocsenwip == "NULL") {
            $query->whereNull('ekanban_wipprinted_log_tbl.doc_no_rec');
        } elseif ($statusDocsenwip == "NOT_NULL") {
            $query->whereNotNull('ekanban_wipprinted_log_tbl.doc_no_rec');
        } elseif ($statusDocsenwip == "0") {
            $query->where('ekanban_wipprinted_log_tbl.doc_no_rec', $statusDocsenwip);
        }

        // dd($fromDatewip);
        // dd($data);
        $data = $query->get()->toArray();
        // dd($data);
        if (empty($data)) {
            // Jika data tidak ditemukan, kirimkan JSON response ke Ajax
            return response()->json(["message" => "No data found"]);
        } else {
            // echo "masuk";
            $kanbanNowip = $request->kanbanNowip;
            $fromDatewip = $request->fromDatewip;
            $toDatewip = $request->toDatewip;
            $createdBywip = $request->createdBywip;
            $date_format = date('Y-m-d H:i:s');

            $export = new ReportWipprinted($data, $fromDatewip, $toDatewip, $date_format, $createdBywip, $kanbanNowip);
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            // dd($export);
            return Excel::download($export, 'Report_Wipprinted_' . $fromDatewip . '_' . $toDatewip . '.xlsx');
        }
    }
}
