<?php

namespace App\Http\Controllers\Tms\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Ekanban\ekanban_fg_chuter_tbl;
use App\Models\Ekanban\Ekanban_fgin_tbl;
use App\Models\Ekanban\Ekanban_stock_limit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MonitoringMinMaxController extends Controller
{
    //
    public function index_monitoring()
    {
        date_default_timezone_set('Asia/Jakarta');


        $mpname = Carbon::now()->format('m-Y');

        // Ambil data dari database
        $getdata = Ekanban_stock_limit::select(

            DB::raw('MAX(ekanban_stock_limit.min) as min'),
            DB::raw('MAX(ekanban_stock_limit.max) as max'),
            DB::raw('MAX(ekanban_fg_chuter_tbl.balance) as balance')
        )
            ->leftJoin('ekanban_fg_chuter_tbl', function ($join) {
                $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_fg_chuter_tbl.item_code');
            })
            ->leftJoin('ekanban_fgin_tbl', function ($join) {
                $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_fgin_tbl.item_code')
                    ->whereNull('ekanban_fgin_tbl.chutter_address')
                    ->whereNull('ekanban_fgin_tbl.last_updated_by');
            })
            ->where('ekanban_fg_chuter_tbl.mpname', '=', $mpname)
            ->where('ekanban_stock_limit.is_active', '1')
            ->orderByDesc('ekanban_stock_limit.action_date')
            ->groupBy('ekanban_stock_limit.itemcode')
            ->get()
            ->toArray();

        // Filter untuk balance < min
        $getDatakritisFiltered = array_filter($getdata, function ($item) {
            return $item['balance'] < $item['min'];
        });
        $getDataoverFiltered = array_filter($getdata, function ($item) {
            return $item['balance'] > $item['max'];
        });
        // Menghitung jumlah data sebelum dan setelah filter
        $totalItemcodekritis = count($getDatakritisFiltered);
        $totalItemcodeover = count($getDataoverFiltered);
        // dd($totalItemcodeover);
        // dd($getDatakritisFiltered);

        return view('tms.warehouse.monitoring-min-max.index', compact('totalItemcodekritis', 'totalItemcodeover'));
    }
    public function getDatakritis(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $cutOffdate = '2024-05-20';
        $mpname = Carbon::now()->format('m-Y');

        $getdata = Ekanban_stock_limit::select(
            DB::raw('MAX(ekanban_stock_limit.id) as id'),
            DB::raw('MAX(ekanban_stock_limit.chutter_address) as chutter_address'),
            DB::raw('MAX(ekanban_stock_limit.part_number) as part_number'),
            DB::raw('MAX(ekanban_stock_limit.part_name) as part_name'),
            DB::raw('MAX(ekanban_stock_limit.itemcode) as itemcode'),
            DB::raw('MAX(ekanban_stock_limit.min) as min'),
            DB::raw('MAX(ekanban_stock_limit.max) as max'),
            DB::raw('MAX(ekanban_fg_chuter_tbl.balance) as balance'),
            DB::raw('COUNT(ekanban_fgin_tbl.item_code) as jumlah_kanban'), // jumlah kanban
            DB::raw('COALESCE(SUM(ekanban_fgin_tbl.qty), 0) as jumlah_qty') // jumlah qty
        )
            ->leftJoin('ekanban_fg_chuter_tbl', function ($join) {
                $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_fg_chuter_tbl.item_code');
            })
            ->leftJoin('ekanban_fgin_tbl', function ($join) use ($cutOffdate) {
                $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_fgin_tbl.item_code')
                    ->whereNull('ekanban_fgin_tbl.chutter_address')
                    ->whereNull('ekanban_fgin_tbl.last_updated_by')
                    ->where('ekanban_fgin_tbl.creation_date', '>', $cutOffdate); // Change to '>' comparison
            })
            ->where('ekanban_fg_chuter_tbl.mpname', '=', $mpname)
            ->where('ekanban_stock_limit.is_active', '1')
            ->orderByDesc('ekanban_stock_limit.action_date')
            ->groupBy('ekanban_stock_limit.itemcode')
            ->get()
            ->toArray();


        // Filter untuk balance < min
        $getDatakritisFiltered = array_filter($getdata, function ($item) {
            return $item['balance'] < $item['min'];
        });

        // dd($getDatakritisFiltered);
        return DataTables::of($getDatakritisFiltered)->make(true);
    }

    public function getDataover(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $cutOffdate = '2024-05-20';
        $mpname = Carbon::now()->format('m-Y');

        $getdata = Ekanban_stock_limit::select(
            DB::raw('MAX(ekanban_stock_limit.id) as id'),
            DB::raw('MAX(ekanban_stock_limit.chutter_address) as chutter_address'),
            DB::raw('MAX(ekanban_stock_limit.part_number) as part_number'),
            DB::raw('MAX(ekanban_stock_limit.part_name) as part_name'),
            DB::raw('MAX(ekanban_stock_limit.itemcode) as itemcode'),
            DB::raw('MAX(ekanban_stock_limit.min) as min'),
            DB::raw('MAX(ekanban_stock_limit.max) as max'),
            DB::raw('MAX(ekanban_fg_chuter_tbl.balance) as balance'),
            DB::raw('COUNT(ekanban_fgin_tbl.item_code) as jumlah_kanban'), // jumlah kanban
            DB::raw('COALESCE(SUM(ekanban_fgin_tbl.qty), 0) as jumlah_qty') // jumlah qty
        )
            ->leftJoin('ekanban_fg_chuter_tbl', function ($join) {
                $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_fg_chuter_tbl.item_code');
            })
            ->leftJoin('ekanban_fgin_tbl', function ($join) use ($cutOffdate) {
                $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_fgin_tbl.item_code')
                    ->whereNull('ekanban_fgin_tbl.chutter_address')
                    ->whereNull('ekanban_fgin_tbl.last_updated_by')
                    ->where('ekanban_fgin_tbl.creation_date', '>', $cutOffdate); // Change to '>' comparison
            })
            ->where('ekanban_fg_chuter_tbl.mpname', '=', $mpname)
            ->where('ekanban_stock_limit.is_active', '1')
            ->orderByDesc('ekanban_stock_limit.action_date')
            ->groupBy('ekanban_stock_limit.itemcode')
            ->get()
            ->toArray();

        // Filter untuk balance < min
        $getDatakritisFiltered = array_filter($getdata, function ($item) {
            return $item['balance'] > $item['max'];
        });
        // dd($getDatakritisFiltered);
        return DataTables::of($getDatakritisFiltered)->make(true);
    }

    public function index_chuter2_All()
    {
        date_default_timezone_set('Asia/Jakarta');

        $mpname = Carbon::now()->format('m-Y');
        $cutOffdate = '2024-05-20'; // Tanggal cut off yang Anda sebutkan
        // $get_data_out = DB::connection('ekanban')
        // ->table('ekanban_chutter_fgout')
        // ->select('qty')
        // ->get();
        // dd($get_data_out);
        $data = Ekanban_stock_limit::select(
            'ekanban_stock_limit.itemcode',
            DB::raw('MAX(ekanban_stock_limit.chutter_address) as chutter_address'),
            DB::raw('MAX(ekanban_stock_limit.part_number) as part_number'),
            DB::raw('MAX(ekanban_stock_limit.part_name) as part_name'),
            DB::raw('MAX(ekanban_stock_limit.part_type) as part_type'),
            DB::raw('MAX(ekanban_stock_limit.min) as min'),
            DB::raw('MAX(ekanban_stock_limit.max) as max'),
            DB::raw('MAX(ekanban_fg_chuter_tbl.balance) as balance'),
            DB::raw('COALESCE(SUM(ekanban_fgin_tbl.qty), 0) as jumlah_qty'),  // jumlah qty
            // DB::raw('COALESCE(SUM(ekanban_chutter_fgout.qty), 0) as total_staging'), // total qty from ekaban_chutter_fgout
            DB::raw('MAX(ekanban_fg_chuter_tbl.balance) + COALESCE(SUM(ekanban_fgin_tbl.qty), 0) as total') // total of balance + jumlah_qty
            // DB::raw('MAX(ekanban_chutter_fgout.kanban_no) as kanban_no') // Added kanban_no column
        )
            ->leftJoin('ekanban_fg_chuter_tbl', function ($join) {
                $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_fg_chuter_tbl.item_code');
            })
            ->leftJoin('ekanban_fgin_tbl', function ($join) use ($cutOffdate) {
                $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_fgin_tbl.item_code')
                    ->whereNull('ekanban_fgin_tbl.chutter_address')
                    ->whereNull('ekanban_fgin_tbl.last_updated_by')
                    ->where('ekanban_fgin_tbl.creation_date', '>', $cutOffdate);
            })
            // ->leftJoin('ekanban_chutter_fgout', function ($join) {
            //     $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_chutter_fgout.item_code');
            // })
            // // Join with ekanban_admout_tbl but filter out matching seq
            // ->leftJoin('ekanban_admout_tbl', function ($join) {
            //     $join->on('ekanban_chutter_fgout.kanban_no', '=', 'ekanban_admout_tbl.ekanban_no')
            //          ->on('ekanban_chutter_fgout.seq', '=', 'ekanban_admout_tbl.seq');
            // })
            // ->whereNull('ekanban_admout_tbl.seq') // Only include rows not in ekanban_admout_tbl
            ->where('ekanban_fg_chuter_tbl.mpname', '=', $mpname)
            ->where('ekanban_stock_limit.is_active', '1')
            ->where(function ($query) {
                $query->where('ekanban_stock_limit.chutter_address', 'LIKE', 'O%')
                    ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'M%')
                    ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'N%')
                    ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'L%')
                    ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'K%')
                    ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'R%');
                // $query->where('ekanban_stock_limit.chutter_address', 'LIKE', 'N%');
            })
            ->groupBy('ekanban_stock_limit.itemcode')
            // ->havingRaw('MAX(ekanban_fg_chuter_tbl.balance) < MAX(ekanban_stock_limit.min) OR MAX(ekanban_fg_chuter_tbl.balance) > MAX(ekanban_stock_limit.max)')
            ->orderBy('ekanban_stock_limit.chutter_address', 'asc')
            ->get()
            ->toArray();

        // dd($data);
        // Group the data by the first character of chutter_address
        $groupedData = collect($data)->groupBy(function ($item) {
            return substr($item['chutter_address'], 0, 1); // Kelompokkan berdasarkan karakter pertama dari chutter_address
        });
        // for itemcode
        // Inisialisasi variabel
        $carouselDataitemcode = [];
        $currentTables = [];

        // Urutan chutter address yang diinginkan
        $orderedChutterAddresses = ['O', 'M', 'N', 'L', 'K', 'R'];

        // Memproses setiap chutter address sesuai urutan yang ditentukan
        foreach ($orderedChutterAddresses as $chutterAddress) {
            if ($groupedData->has($chutterAddress)) {
                $items = $groupedData->get($chutterAddress);
                $chunks = $items->chunk(10); // Memecah item menjadi bagian-bagian dengan maksimal 15 baris per tabel

                foreach ($chunks as $chunk) {
                    $currentTables[] = [
                        'chutter_address' => $chutterAddress,
                        'items' => $chunk->toArray()
                    ];

                    // Jika sudah ada 3 tabel, tambahkan ke carousel data
                    if (count($currentTables) == 3) {
                        $carouselDataitemcode[] = $currentTables;
                        $currentTables = []; // Reset untuk halaman carousel berikutnya
                    }
                }
            }
        }

        // Menangani tabel yang tersisa (hanya dari chutter address saat ini)
        if (!empty($currentTables)) {
            // Tambahkan tabel yang tersisa ke carouselDataitemcode
            $carouselDataitemcode[] = $currentTables;
        }

        // fot chuter
        $carouselDatachuter = [];
        $currentTables = [];

        // Urutan chutter address yang diinginkan
        $orderedChutterAddresses = ['O', 'M', 'N', 'L', 'K', 'R'];

        // Memproses setiap chutter address sesuai urutan yang ditentukan
        foreach ($orderedChutterAddresses as $chutterAddress) {
            if ($groupedData->has($chutterAddress)) {
                $items = $groupedData->get($chutterAddress);
                $chunks = $items->chunk(25); // Memecah item menjadi bagian-bagian dengan maksimal 15 baris per tabel

                foreach ($chunks as $chunk) {
                    $currentTables[] = [
                        'chutter_address' => $chutterAddress,
                        'items' => $chunk->toArray()
                    ];

                    // Jika sudah ada 3 tabel, tambahkan ke carousel data
                    if (count($currentTables) == 3) {
                        $carouselDatachuter[] = $currentTables;
                        $currentTables = []; // Reset untuk halaman carousel berikutnya
                    }
                }
            }
        }

        // Menangani tabel yang tersisa (hanya dari chutter address saat ini)
        if (!empty($currentTables)) {
            // Tambahkan tabel yang tersisa ke carouselDatachuter
            $carouselDatachuter[] = $currentTables;
        }
        // dd($carouselData);

        // Return the view with the prepared carousel data
        return view('tms.warehouse.monitoring-min-max.visual.chuter2_all', compact('carouselDataitemcode', 'carouselDatachuter'));
    }
    public function index_chuter2_abnormality()
    {
        date_default_timezone_set('Asia/Jakarta');

        $mpname = Carbon::now()->format('m-Y');
        $cutOffdate = '2024-05-20'; // Tanggal cut off yang Anda sebutkan

        $data = Ekanban_stock_limit::select(
            'ekanban_stock_limit.itemcode',
            DB::raw('MAX(ekanban_stock_limit.chutter_address) as chutter_address'),
            DB::raw('MAX(ekanban_stock_limit.part_number) as part_number'),
            DB::raw('MAX(ekanban_stock_limit.part_name) as part_name'),
            DB::raw('MAX(ekanban_stock_limit.part_type) as part_type'),
            DB::raw('MAX(ekanban_stock_limit.min) as min'),
            DB::raw('MAX(ekanban_stock_limit.max) as max'),
            DB::raw('MAX(ekanban_fg_chuter_tbl.balance) as balance'),
            DB::raw('COALESCE(SUM(ekanban_fgin_tbl.qty), 0) as jumlah_qty'),  // jumlah qty
            // DB::raw('COALESCE(SUM(ekanban_chutter_fgout.qty), 0) as total_staging'), // total qty from ekaban_chutter_fgout
            DB::raw('MAX(ekanban_fg_chuter_tbl.balance) + COALESCE(SUM(ekanban_fgin_tbl.qty), 0) as total') // total of balance + jumlah_qty
            // DB::raw('MAX(ekanban_chutter_fgout.kanban_no) as kanban_no') // Added kanban_no column
        )
            ->leftJoin('ekanban_fg_chuter_tbl', function ($join) {
                $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_fg_chuter_tbl.item_code');
            })
            ->leftJoin('ekanban_fgin_tbl', function ($join) use ($cutOffdate) {
                $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_fgin_tbl.item_code')
                    ->whereNull('ekanban_fgin_tbl.chutter_address')
                    ->whereNull('ekanban_fgin_tbl.last_updated_by')
                    ->where('ekanban_fgin_tbl.creation_date', '>', $cutOffdate);
            })
            // ->leftJoin('ekanban_chutter_fgout', function ($join) {
            //     $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_chutter_fgout.item_code');
            // })
            // // Join with ekanban_admout_tbl but filter out matching seq
            // ->leftJoin('ekanban_admout_tbl', function ($join) {
            //     $join->on('ekanban_chutter_fgout.kanban_no', '=', 'ekanban_admout_tbl.ekanban_no')
            //          ->on('ekanban_chutter_fgout.seq', '=', 'ekanban_admout_tbl.seq');
            // })
            // ->whereNull('ekanban_admout_tbl.seq') // Only include rows not in ekanban_admout_tbl
            ->where('ekanban_fg_chuter_tbl.mpname', '=', $mpname)
            ->where('ekanban_stock_limit.is_active', '1')
            ->where(function ($query) {
                $query->where('ekanban_stock_limit.chutter_address', 'LIKE', 'O%')
                    ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'M%')
                    ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'N%')
                    ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'L%')
                    ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'K%')
                    ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'R%');
                // $query->where('ekanban_stock_limit.chutter_address', 'LIKE', 'N%');
            })
            ->groupBy('ekanban_stock_limit.itemcode')
            ->havingRaw('MAX(ekanban_fg_chuter_tbl.balance) < MAX(ekanban_stock_limit.min) OR MAX(ekanban_fg_chuter_tbl.balance) > MAX(ekanban_stock_limit.max)')
            ->orderBy('ekanban_stock_limit.chutter_address', 'asc')
            ->get()
            ->toArray();

        // dd($data);

        // Group the data by the first character of chutter_address
        $groupedData = collect($data)->groupBy(function ($item) {
            return substr($item['chutter_address'], 0, 1); // Kelompokkan berdasarkan karakter pertama dari chutter_address
        });


        // for itemcode
        // Inisialisasi variabel
        $carouselDataitemcode = [];
        $currentTables = [];

        // Urutan chutter address yang diinginkan
        $orderedChutterAddresses = ['O', 'M', 'N', 'L', 'K', 'R'];

        // Memproses setiap chutter address sesuai urutan yang ditentukan
        foreach ($orderedChutterAddresses as $chutterAddress) {
            if ($groupedData->has($chutterAddress)) {
                $items = $groupedData->get($chutterAddress);
                $chunks = $items->chunk(10); // Memecah item menjadi bagian-bagian dengan maksimal 15 baris per tabel

                foreach ($chunks as $chunk) {
                    $currentTables[] = [
                        'chutter_address' => $chutterAddress,
                        'items' => $chunk->toArray()
                    ];

                    // Jika sudah ada 3 tabel, tambahkan ke carousel data
                    if (count($currentTables) == 3) {
                        $carouselDataitemcode[] = $currentTables;
                        $currentTables = []; // Reset untuk halaman carousel berikutnya
                    }
                }
            }
        }

        // Menangani tabel yang tersisa (hanya dari chutter address saat ini)
        if (!empty($currentTables)) {
            // Tambahkan tabel yang tersisa ke carouselDataitemcode
            $carouselDataitemcode[] = $currentTables;
        }

        // dd($carouselData);

        // fot chuter
        $carouselDatachuter = [];
        $currentTables = [];

        // Urutan chutter address yang diinginkan
        $orderedChutterAddresses = ['O', 'M', 'N', 'L', 'K', 'R'];

        // Memproses setiap chutter address sesuai urutan yang ditentukan
        foreach ($orderedChutterAddresses as $chutterAddress) {
            if ($groupedData->has($chutterAddress)) {
                $items = $groupedData->get($chutterAddress);
                $chunks = $items->chunk(25); // Memecah item menjadi bagian-bagian dengan maksimal 15 baris per tabel

                foreach ($chunks as $chunk) {
                    $currentTables[] = [
                        'chutter_address' => $chutterAddress,
                        'items' => $chunk->toArray()
                    ];

                    // Jika sudah ada 3 tabel, tambahkan ke carousel data
                    if (count($currentTables) == 3) {
                        $carouselDatachuter[] = $currentTables;
                        $currentTables = []; // Reset untuk halaman carousel berikutnya
                    }
                }
            }
        }

        // Menangani tabel yang tersisa (hanya dari chutter address saat ini)
        if (!empty($currentTables)) {
            // Tambahkan tabel yang tersisa ke carouselDatachuter
            $carouselDatachuter[] = $currentTables;
        }
        // dd($carouselData);


        // dd($carouselData);
        // Return the view with the prepared carousel data
        return view('tms.warehouse.monitoring-min-max.visual.chuter2_abnormality', compact('carouselDataitemcode', 'carouselDatachuter'));
    }
    public function index_chuter1_All()
    {
        date_default_timezone_set('Asia/Jakarta');

        $mpname = Carbon::now()->format('m-Y');
        $cutOffdate = '2024-05-20'; // Tanggal cut off yang Anda sebutkan
        // $get_data_out = DB::connection('ekanban')
        // ->table('ekanban_chutter_fgout')
        // ->select('qty')
        // ->get();
        // dd($get_data_out);
        $data = Ekanban_stock_limit::select(
            'ekanban_stock_limit.itemcode',
            DB::raw('MAX(ekanban_stock_limit.chutter_address) as chutter_address'),
            DB::raw('MAX(ekanban_stock_limit.part_number) as part_number'),
            DB::raw('MAX(ekanban_stock_limit.part_name) as part_name'),
            DB::raw('MAX(ekanban_stock_limit.part_type) as part_type'),
            DB::raw('MAX(ekanban_stock_limit.min) as min'),
            DB::raw('MAX(ekanban_stock_limit.max) as max'),
            DB::raw('MAX(ekanban_fg_chuter_tbl.balance) as balance'),
            DB::raw('COALESCE(SUM(ekanban_fgin_tbl.qty), 0) as jumlah_qty'),  // jumlah qty
            // DB::raw('COALESCE(SUM(ekanban_chutter_fgout.qty), 0) as total_staging'), // total qty from ekaban_chutter_fgout
            DB::raw('MAX(ekanban_fg_chuter_tbl.balance) + COALESCE(SUM(ekanban_fgin_tbl.qty), 0) as total') // total of balance + jumlah_qty
            // DB::raw('MAX(ekanban_chutter_fgout.kanban_no) as kanban_no') // Added kanban_no column
        )
            ->leftJoin('ekanban_fg_chuter_tbl', function ($join) {
                $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_fg_chuter_tbl.item_code');
            })
            ->leftJoin('ekanban_fgin_tbl', function ($join) use ($cutOffdate) {
                $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_fgin_tbl.item_code')
                    ->whereNull('ekanban_fgin_tbl.chutter_address')
                    ->whereNull('ekanban_fgin_tbl.last_updated_by')
                    ->where('ekanban_fgin_tbl.creation_date', '>', $cutOffdate);
            })
            // ->leftJoin('ekanban_chutter_fgout', function ($join) {
            //     $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_chutter_fgout.item_code');
            // })
            // // Join with ekanban_admout_tbl but filter out matching seq
            // ->leftJoin('ekanban_admout_tbl', function ($join) {
            //     $join->on('ekanban_chutter_fgout.kanban_no', '=', 'ekanban_admout_tbl.ekanban_no')
            //          ->on('ekanban_chutter_fgout.seq', '=', 'ekanban_admout_tbl.seq');
            // })
            // ->whereNull('ekanban_admout_tbl.seq') // Only include rows not in ekanban_admout_tbl
            ->where('ekanban_fg_chuter_tbl.mpname', '=', $mpname)
            ->where('ekanban_stock_limit.is_active', '1')
            ->where(function ($query) {
                $query->where('ekanban_stock_limit.chutter_address', 'LIKE', 'J%')
                    ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'I%')
                    ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'H%')
                    ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'G%')
                    ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'F%');
                // $query->where('ekanban_stock_limit.chutter_address', 'LIKE', 'N%');
            })
            ->groupBy('ekanban_stock_limit.itemcode')
            // ->havingRaw('MAX(ekanban_fg_chuter_tbl.balance) < MAX(ekanban_stock_limit.min) OR MAX(ekanban_fg_chuter_tbl.balance) > MAX(ekanban_stock_limit.max)')
            ->orderBy('ekanban_stock_limit.chutter_address', 'asc')
            ->get()
            ->toArray();

        // dd($data);
        // Group the data by the first character of chutter_address
        $groupedData = collect($data)->groupBy(function ($item) {
            return substr($item['chutter_address'], 0, 1); // Kelompokkan berdasarkan karakter pertama dari chutter_address
        });
        // for itemcode
        // Inisialisasi variabel
        $carouselDataitemcode = [];
        $currentTables = [];

        // Urutan chutter address yang diinginkan
        $orderedChutterAddresses = ['J', 'I', 'H', 'G', 'F'];

        // Memproses setiap chutter address sesuai urutan yang ditentukan
        foreach ($orderedChutterAddresses as $chutterAddress) {
            if ($groupedData->has($chutterAddress)) {
                $items = $groupedData->get($chutterAddress);
                $chunks = $items->chunk(10); // Memecah item menjadi bagian-bagian dengan maksimal 15 baris per tabel

                foreach ($chunks as $chunk) {
                    $currentTables[] = [
                        'chutter_address' => $chutterAddress,
                        'items' => $chunk->toArray()
                    ];

                    // Jika sudah ada 3 tabel, tambahkan ke carousel data
                    if (count($currentTables) == 3) {
                        $carouselDataitemcode[] = $currentTables;
                        $currentTables = []; // Reset untuk halaman carousel berikutnya
                    }
                }
            }
        }

        // Menangani tabel yang tersisa (hanya dari chutter address saat ini)
        if (!empty($currentTables)) {
            // Tambahkan tabel yang tersisa ke carouselDataitemcode
            $carouselDataitemcode[] = $currentTables;
        }

        // fot chuter
        $carouselDatachuter = [];
        $currentTables = [];

        // Urutan chutter address yang diinginkan
        $orderedChutterAddresses = ['J', 'I', 'H', 'G', 'F'];

        // Memproses setiap chutter address sesuai urutan yang ditentukan
        foreach ($orderedChutterAddresses as $chutterAddress) {
            if ($groupedData->has($chutterAddress)) {
                $items = $groupedData->get($chutterAddress);
                $chunks = $items->chunk(25); // Memecah item menjadi bagian-bagian dengan maksimal 15 baris per tabel

                foreach ($chunks as $chunk) {
                    $currentTables[] = [
                        'chutter_address' => $chutterAddress,
                        'items' => $chunk->toArray()
                    ];

                    // Jika sudah ada 3 tabel, tambahkan ke carousel data
                    if (count($currentTables) == 3) {
                        $carouselDatachuter[] = $currentTables;
                        $currentTables = []; // Reset untuk halaman carousel berikutnya
                    }
                }
            }
        }

        // Menangani tabel yang tersisa (hanya dari chutter address saat ini)
        if (!empty($currentTables)) {
            // Tambahkan tabel yang tersisa ke carouselDatachuter
            $carouselDatachuter[] = $currentTables;
        }
        // dd($carouselData);

        // Return the view with the prepared carousel data
        return view('tms.warehouse.monitoring-min-max.visual.chuter1_all', compact('carouselDataitemcode', 'carouselDatachuter'));

    }
    public function index_chuter1_abnormality()
    {

        date_default_timezone_set('Asia/Jakarta');

        $mpname = Carbon::now()->format('m-Y');
        $cutOffdate = '2024-05-20'; // Tanggal cut off yang Anda sebutkan

        $data = Ekanban_stock_limit::select(
            'ekanban_stock_limit.itemcode',
            DB::raw('MAX(ekanban_stock_limit.chutter_address) as chutter_address'),
            DB::raw('MAX(ekanban_stock_limit.part_number) as part_number'),
            DB::raw('MAX(ekanban_stock_limit.part_name) as part_name'),
            DB::raw('MAX(ekanban_stock_limit.part_type) as part_type'),
            DB::raw('MAX(ekanban_stock_limit.min) as min'),
            DB::raw('MAX(ekanban_stock_limit.max) as max'),
            DB::raw('MAX(ekanban_fg_chuter_tbl.balance) as balance'),
            DB::raw('COALESCE(SUM(ekanban_fgin_tbl.qty), 0) as jumlah_qty'),  // jumlah qty
            // DB::raw('COALESCE(SUM(ekanban_chutter_fgout.qty), 0) as total_staging'), // total qty from ekaban_chutter_fgout
            DB::raw('MAX(ekanban_fg_chuter_tbl.balance) + COALESCE(SUM(ekanban_fgin_tbl.qty), 0) as total') // total of balance + jumlah_qty
            // DB::raw('MAX(ekanban_chutter_fgout.kanban_no) as kanban_no') // Added kanban_no column
        )
            ->leftJoin('ekanban_fg_chuter_tbl', function ($join) {
                $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_fg_chuter_tbl.item_code');
            })
            ->leftJoin('ekanban_fgin_tbl', function ($join) use ($cutOffdate) {
                $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_fgin_tbl.item_code')
                    ->whereNull('ekanban_fgin_tbl.chutter_address')
                    ->whereNull('ekanban_fgin_tbl.last_updated_by')
                    ->where('ekanban_fgin_tbl.creation_date', '>', $cutOffdate);
            })
            ->leftJoin('ekanban_chutter_fgout', function ($join) {
                $join->on('ekanban_stock_limit.itemcode', '=', 'ekanban_chutter_fgout.item_code');
            })
            // // Join with ekanban_admout_tbl but filter out matching seq
            // ->leftJoin('ekanban_admout_tbl', function ($join) {
            //     $join->on('ekanban_chutter_fgout.kanban_no', '=', 'ekanban_admout_tbl.ekanban_no')
            //          ->on('ekanban_chutter_fgout.seq', '=', 'ekanban_admout_tbl.seq');
            // })
            // ->whereNull('ekanban_admout_tbl.seq') // Only include rows not in ekanban_admout_tbl
            ->where('ekanban_fg_chuter_tbl.mpname', '=', $mpname)
            ->where('ekanban_stock_limit.is_active', '1')
            ->where(function ($query) {
                $query->where('ekanban_stock_limit.chutter_address', 'LIKE', 'J%')
                    ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'I%')
                    ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'H%')
                    ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'G%')
                    ->orWhere('ekanban_stock_limit.chutter_address', 'LIKE', 'F%');
            })
            ->groupBy('ekanban_stock_limit.itemcode')
            ->havingRaw('MAX(ekanban_fg_chuter_tbl.balance) < MAX(ekanban_stock_limit.min) OR MAX(ekanban_fg_chuter_tbl.balance) > MAX(ekanban_stock_limit.max)')
            ->orderBy('ekanban_stock_limit.chutter_address', 'asc')
            ->get()
            ->toArray();

        // dd($data);

        // Group the data by the first character of chutter_address
        $groupedData = collect($data)->groupBy(function ($item) {
            return substr($item['chutter_address'], 0, 1); // Kelompokkan berdasarkan karakter pertama dari chutter_address
        });


        // for itemcode
        // Inisialisasi variabel
        $carouselDataitemcode = [];
        $currentTables = [];

        // Urutan chutter address yang diinginkan
        $orderedChutterAddresses = ['J', 'I', 'H', 'G', 'F'];

        // Memproses setiap chutter address sesuai urutan yang ditentukan
        foreach ($orderedChutterAddresses as $chutterAddress) {
            if ($groupedData->has($chutterAddress)) {
                $items = $groupedData->get($chutterAddress);
                $chunks = $items->chunk(10); // Memecah item menjadi bagian-bagian dengan maksimal 15 baris per tabel

                foreach ($chunks as $chunk) {
                    $currentTables[] = [
                        'chutter_address' => $chutterAddress,
                        'items' => $chunk->toArray()
                    ];

                    // Jika sudah ada 3 tabel, tambahkan ke carousel data
                    if (count($currentTables) == 3) {
                        $carouselDataitemcode[] = $currentTables;
                        $currentTables = []; // Reset untuk halaman carousel berikutnya
                    }
                }
            }
        }

        // Menangani tabel yang tersisa (hanya dari chutter address saat ini)
        if (!empty($currentTables)) {
            // Tambahkan tabel yang tersisa ke carouselDataitemcode
            $carouselDataitemcode[] = $currentTables;
        }

        // dd($carouselData);

        // fot chuter
        $carouselDatachuter = [];
        $currentTables = [];

        // Urutan chutter address yang diinginkan
        $orderedChutterAddresses = ['O', 'M', 'N', 'L', 'K', 'R'];

        // Memproses setiap chutter address sesuai urutan yang ditentukan
        foreach ($orderedChutterAddresses as $chutterAddress) {
            if ($groupedData->has($chutterAddress)) {
                $items = $groupedData->get($chutterAddress);
                $chunks = $items->chunk(25); // Memecah item menjadi bagian-bagian dengan maksimal 15 baris per tabel

                foreach ($chunks as $chunk) {
                    $currentTables[] = [
                        'chutter_address' => $chutterAddress,
                        'items' => $chunk->toArray()
                    ];

                    // Jika sudah ada 3 tabel, tambahkan ke carousel data
                    if (count($currentTables) == 3) {
                        $carouselDatachuter[] = $currentTables;
                        $currentTables = []; // Reset untuk halaman carousel berikutnya
                    }
                }
            }
        }

        // Menangani tabel yang tersisa (hanya dari chutter address saat ini)
        if (!empty($currentTables)) {
            // Tambahkan tabel yang tersisa ke carouselDatachuter
            $carouselDatachuter[] = $currentTables;
        }
        // dd($carouselData);


        // dd($carouselData);
        // Return the view with the prepared carousel data
        return view('tms.warehouse.monitoring-min-max.visual.chuter1_abnormality', compact('carouselDataitemcode', 'carouselDatachuter'));
    }
}
