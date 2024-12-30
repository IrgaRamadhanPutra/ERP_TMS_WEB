<?php

namespace App\Exports;

use App\Models\Ekanban\Ekanban_stock_limit_rm;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReportMinMaxRm implements FromView, ShouldAutoSize
{
    protected $mpname;
    protected $date;
    protected $plant;

    public function __construct($mpname, $date, $plant)
    {
        $this->mpname = $mpname;
        $this->date = $date;
        $this->plant = $plant;
        // dd($plant);
        // Log nilai-nilai untuk debugging
        // dd([
        //     'mpname' => $this->mpname,
        //     'date' => $this->date,
        //     'plant' => $this->plant,
        // ]);
    }

    public function view(): View
    {
        // dd($plant);
        // Ambil data dari API
        $apiResponse = Http::withBasicAuth(
            config('app.user_sap'),
            config('app.pass_sap')
        )
            ->timeout(config('app.timeout_ex'))
            ->get(config('app.sap_get_stock_all') . '&PLANT=1701&SLOC=1010');

        $apiOutput = collect($apiResponse->json()['it_output'] ?? []);

        // Query data dari database berdasarkan parameter
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
            ->where('stock_type', 'R/M');

        if ($this->plant !== null) {
            $get_stock_limit_rm->where('plant', '=', $this->plant); // Filter berdasarkan plant jika tidak null
        }

        $get_stock_limit_rm = $get_stock_limit_rm
            ->groupBy('itemcode')
            ->orderBy('action_date', 'desc')
            ->get();

        // Gabungkan data dari API dengan hasil query database
        $data = $get_stock_limit_rm->map(function ($item) use ($apiOutput) {
            $materialFromApi = $apiOutput->firstWhere('material_no', $item->itemcode);

            return [
                'itemcode' => $item->itemcode,
                'chutter_address' => $item->chutter_address,
                'part_number' => $item->part_number,
                'part_name' => $item->part_name,
                'part_type' => $item->part_type,
                'cust_code' => $item->cust_code,
                'min' => $item->min,
                'max' => $item->max,
                'material_desc' => $materialFromApi['material_desc'] ?? null,
                'balance' => $materialFromApi['quantity'] ?? 0,
                'satuan' => $materialFromApi['satuan'] ?? null,
                'quantity_plant' => $materialFromApi['quantity_plant'] ?? 0,
                'action_date' => $item->action_date,
            ];
        });
        // dd($data);
        // Kembalikan view yang sesuai
        return view('tms.warehouse.min-max-rm.ekspor.export_excel', [
            'data' => $data,
            'date' => $this->date,
            // 'mpname' => $this->mpname,
        ]);
    }
}
