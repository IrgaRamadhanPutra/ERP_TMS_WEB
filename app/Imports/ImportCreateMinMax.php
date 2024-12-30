<?php

namespace App\Imports;

use App\Models\Ekanban\Ekanban_stock_limit;
use App\Models\Ekanban\Ekanban_stock_limit_log; // Make sure to import the log model
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ImportCreateMinMax implements ToCollection, WithHeadingRow
{
    protected $chutter_address = [];
    protected $itemCode = [];
    protected $part_number = [];
    protected $part_name = [];
    protected $part_type = [];
    protected $min = [];
    protected $max = [];
    protected $cust_code = [];
    protected $plant = []; // Tambahkan properti untuk plant
    protected $stock_type = [];
    protected $period = [];

    public function collection(Collection $rows)
    {
        // dd($rows);
        date_default_timezone_set("Asia/Jakarta");
        $itemCodes = [];
        $chutterAddresses = [];
        $newRows = [];
        $is_active = 1; // Declare the variable
        $period = Carbon::now()->format('Y-m');

        // Kumpulkan itemcodes dan chutter_addresses untuk pengecekan massal
        foreach ($rows as $row) {
            // Filter out rows with all null values
            if (array_filter($row->toArray())) {
                $itemCodes[] = $row['itemcode'];
                $chutterAddresses[] = $row['chutter_address'];
                $newRows[] = $row;
            }
        }

        // dd($newRows);
        // Ambil data yang sudah ada di database
        $existingItems = Ekanban_stock_limit::whereIn('itemcode', $itemCodes)
            ->whereIn('chutter_address', $chutterAddresses)
            // ->where('is_active', $is_active)
            ->where('period', $period)
            ->get();
        // Filter data yang belum ada di database
        $existingItemCodes = $existingItems->pluck('itemcode')->toArray();
        $existingPeriod = $existingItems->pluck('period')->toArray();
        // dd($existingItemCodes);

        // Persiapan data tambahan
        $plant_void = '1701';
        // Mengecek itemcode dan chutter_address serta mendapatkan item terbaru
        $existingItems2 = Ekanban_stock_limit::where('period', '!=', $period)
            ->where('plant', $plant_void)
            ->where('is_active', '=', '1')
            ->where('stock_type', '=', 'F/G')
            ->get();

        // ini untuk data yang periode tidak di bulan terbaru
        // Memisahkan itemcode dan chutter_address dari data yang ada
        $existingItemCodes2 = $existingItems2->pluck('itemcode')->toArray();
        $existingChutterAddresses2 = $existingItems2->pluck('chutter_address')->toArray();



        DB::connection('ekanban')->beginTransaction();

        try {
            $action_name_update = 'VOID'; // Menyimpan nama aksi
            $status = '0';
            $period = Carbon::now()->format('m-Y');

            // Update semua data yang periodenya tidak sesuai dengan periode terbaru dan memiliki stock_type 'F/G'
            // dan non aktif kan dengan void
            Ekanban_stock_limit::whereIn('itemcode', $existingItemCodes2)
                // ->whereIn('chutter_address', $existingChutterAddresses2)
                ->where('period', '!=', $period) // Kondisi yang memeriksa periode yang tidak sama
                ->where('stock_type', '=', 'F/G') // Kondisi stock_type harus 'F/G'
                ->update([
                    'action_name' => $action_name_update, // Mengupdate action_name dengan nilai 'UPDATE'
                    'is_active' => $status
                ]);

            // Looping untuk memproses setiap baris baru dari Excel
            foreach ($newRows as $row) {
                if (empty($existingItemCodes) ||
                empty($existingPeriod) ||
                !in_array($row['itemcode'], $existingItemCodes) ||
                !in_array($row['period'], $existingPeriod)) {

                    // Data belum ada, masukkan ke array dan simpan ke database
                    $this->chutter_address[] = $row['chutter_address'];
                    $this->itemCode[] = $row['itemcode'];
                    $this->period[] = $row['period'];
                    $this->part_number[] = $row['part_no'];
                    $this->part_name[] = $row['part_name'];
                    $this->part_type[] = $row['part_type'];
                    $this->min[] = $row['min'];
                    $this->max[] = $row['max'];
                    $this->cust_code[] = $row['cust_code'];
                    $this->stock_type[] = $row['stock_type'];
                    $this->plant[] = $row['plant']; // Menambahkan data plant ke array
                    $action_name = 'INSERT';
                    $action_user = Auth::user()->UserID;
                    $action_date = Carbon::now();
                    // Buat entri baru di tabel
                    $newItemData = [
                        'chutter_address' => $row['chutter_address'],
                        'itemcode' => $row['itemcode'],
                        'period' => $row['period'],
                        'part_number' => $row['part_no'],
                        'part_name' => $row['part_name'],
                        'part_type' => $row['part_type'],
                        'min' => $row['min'],
                        'max' => $row['max'],
                        'cust_code' => $row['cust_code'],
                        'stock_type' => $row['stock_type'],
                        'plant' => strval(trim($row['plant'])), // Ensure it's a string and trimmed
                        'is_active' => $is_active,
                        'action_name' => $action_name,
                        'action_user' => $action_user,
                        'action_date' => $action_date,
                    ];


                    // dd($newItemData);
                    // Buat entri baru di tabel
                    $newItem = Ekanban_stock_limit::create($newItemData);

                    // Insert into log table
                    $data_id = $newItem->id;
                    $time = Carbon::now();
                    $column_name = "";
                    $old_value =  "";
                    $new_value = "";

                    Ekanban_stock_limit_log::create([
                        'table_id' => $data_id,
                        'action_name' => $action_name,
                        'column_name' => $column_name,
                        'old_value' => $old_value,
                        'new_value' =>  $new_value,
                        'action_date' =>  $time,
                        'action_user' => $action_user,
                    ]);
                }
            }

            DB::connection('ekanban')->commit(); // Commit transaksi jika tidak ada kesalahan
        } catch (\Exception $e) {
            DB::connection('ekanban')->rollBack(); // Rollback transaksi jika ada kesalahan
            // Anda bisa melakukan logging atau menampilkan pesan kesalahan di sini
            throw $e; // Melempar kembali kesalahan untuk penanganan lebih lanjut
        }
    }
}
