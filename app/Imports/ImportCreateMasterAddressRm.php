<?php

namespace App\Imports;

use App\Models\Ekanban\Master_address_rm;
use App\Models\Ekanban\Master_address_rm_log_tbl;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportCreateMasterAddressRm implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    protected $chutter_address = [];
    protected $itemCode = [];
    protected $part_no = [];
    protected $part_name = [];
    protected $part_type = [];
    protected $process_code = [];
    protected $cust_code = [];
    protected $supplier = [];
    protected $kanban_type = []; // Tambahkan properti untuk plant
    public function collection(Collection $rows)
    {
        // Set timezone
        date_default_timezone_set("Asia/Jakarta");

        // Inisialisasi array
        $itemCodes = [];
        $chutterAddresses = [];
        $newRows = [];
        $status = 'ACTIVE';
        $action_date = Carbon::now();

        foreach ($rows as $row) {
            // Ubah $row menjadi array
            $rowData = $row->toArray();

            // Filter hanya baris yang memiliki nilai (tidak kosong)
            if (array_filter($rowData)) {
                // Ambil nilai dengan key yang sesuai
                $itemCodes[] = $rowData['itemcode'] ?? null;
                $chutterAddresses[] = $rowData['chuter_address'] ?? null;

                // Tambahkan baris ke $newRows
                $newRows[] = $rowData;
            }
        }

        // GET DATA ALREADY IN DATABASE WHERE chutter_address & itemcode
        $existingItems = Master_address_rm::whereIn('itemcode', $itemCodes)
            ->whereIn('chuter_address', $chutterAddresses)
            ->where('status', $status)
            ->get()
            ->toArray();

        // Normalisasi data untuk perbandingan (hanya `chuter_address` dan `itemcode`)
        $newRowsIndexed = array_map(function ($row) {
            return [
                'itemcode' => $row['itemcode'] ?? null,
                'chuter_address' => $row['chuter_address'] ?? null,
            ];
        }, $newRows);

        $existingItemsIndexed = array_map(function ($row) {
            return [
                'itemcode' => $row['itemcode'] ?? null,
                'chuter_address' => $row['chuter_address'] ?? null,
            ];
        }, $existingItems);

        // Data yang ada di $newRows tapi tidak ada di $existingItems
        $dataNotInExisting = array_udiff($newRowsIndexed, $existingItemsIndexed, function ($a, $b) {
            return strcmp(json_encode($a), json_encode($b));
        });
        DB::connection('ekanban')->beginTransaction();
        try {
            // Format ulang hasil agar sesuai dengan urutan yang diminta
            foreach ($dataNotInExisting as $data) {
                $foundRow = collect($newRows)->firstWhere('itemcode', $data['itemcode']);

                // Format ulang data yang akan di-insert ke Master_address_rm
                $formattedRow = [
                    'chuter_address' => $foundRow['chuter_address'] ?? null,
                    'itemcode' => $foundRow['itemcode'] ?? null,
                    'part_no' => $foundRow['part_no'] ?? null,
                    'part_name' => $foundRow['part_name'] ?? null,
                    'part_type' => $foundRow['part_type'] ?? null,
                    'process_code' => $foundRow['process_code'] ?? null,
                    'cust_code' => $foundRow['cust_code'] ?? null,
                    'supplier' => $foundRow['supplier'] ?? null,
                    'address_type' => trim($foundRow['address_type'] ?? null), // Trim untuk address_type
                    'plant' => trim($foundRow['plant'] ?? ''),                // Trim untuk plant
                    'action_name' => $foundRow['action_name'] ?? null,
                    'status' => $status,
                    'created_date' => $action_date,
                ];

                // dd($formattedRow);

                // Simpan data ke Master_address_rm
                $newItem = Master_address_rm::create($formattedRow);
                $data_id = $newItem->id;

                // Simpan log ke Master_address_rm_log_tbl
                Master_address_rm_log_tbl::create([
                    'tbl_id' => $data_id,
                    'chuter_address' => $foundRow['chuter_address'] ?? null,
                    'status_change' => 'INSERT',
                    'create_date' => Carbon::now(),
                    'create_user' => $foundRow['action_name'] ?? null,
                ]);
            }
            DB::connection('ekanban')->commit(); // Commit transaksi jika tidak ada kesalahan
        } catch (\Exception $e) {
            DB::connection('ekanban')->rollBack(); // Rollback transaksi jika ada kesalahan
            // Anda bisa melakukan logging atau menampilkan pesan kesalahan di sini
            throw $e; // Melempar kembali kesalahan untuk penanganan lebih lanjut
        }
    }
}
