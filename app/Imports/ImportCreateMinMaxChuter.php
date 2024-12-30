<?php

namespace App\Imports;

use App\Models\Ekanban\Ekanban_chuter_limit;
use App\Models\Ekanban\Ekanban_chuter_limit_log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportCreateMinMaxChuter implements ToCollection
{
    protected $chutter_address = [];
    protected $kanban_no = [];
    protected $itemCode = [];
    protected $part_number = [];
    protected $part_name = [];
    protected $part_type = [];
    protected $min = [];
    protected $max = [];
    protected $cust_code = [];

    public function collection(Collection $rows)
    {
        // dd($rows);
        date_default_timezone_set("Asia/Jakarta");
        $itemCodes = [];
        $chutterAddresses = [];
        $kanbanNos = [];
        $newRows = [];
        $is_active = 1; // Declare the variable

        // Kumpulkan itemcodes, chutter_addresses, dan kanban_nos untuk pengecekan massal
        foreach ($rows as $key => $row) {
            // Skip the header row
            if ($key === 0) {
                continue;
            }

            $itemCodes[] = $row->get(2); // Akses itemcode pada index 2
            $chutterAddresses[] = $row->get(0); // Akses chutter_address pada index 0
            $kanbanNos[] = $row->get(1); // Akses kanban_no pada index 1
            $newRows[] = $row;
        }

        // Ambil data yang sudah ada di database berdasarkan itemcode, chutter_address, dan kanban_no
        $existingItems = Ekanban_chuter_limit::whereIn('itemcode', $itemCodes)
            ->whereIn('chutter_address', $chutterAddresses)
            ->whereIn('kanban_no', $kanbanNos) // Tambahkan pengecekan kanban_no
            ->where('is_active', $is_active)
            ->get();
        // dd($existingItems);

        // Filter data yang belum ada di database
        $existingItemCodes = $existingItems->pluck('itemcode')->toArray();
        $existingChutterAddresses = $existingItems->pluck('chutter_address')->toArray();
        $existingKanbanNos = $existingItems->pluck('kanban_no')->toArray(); // Ambil kanban_no yang ada

        // Persiapan data tambahan
        $action_name = 'INSERT';
        $action_user = Auth::user()->UserID;
        $action_date = Carbon::now();
        $period = Carbon::now()->format('Y-m');

        foreach ($newRows as $row) {
            if (
                !in_array($row->get(2), $existingItemCodes) ||
                !in_array($row->get(0), $existingChutterAddresses) ||
                !in_array($row->get(1), $existingKanbanNos) // Cek kanban_no juga
            ) {
                // Data belum ada, masukkan ke array dan simpan ke database
                $this->chutter_address[] = $row->get(0);
                $this->itemCode[] = $row->get(2);
                $this->part_number[] = $row->get(3);
                $this->part_name[] = $row->get(4);
                $this->part_type[] = $row->get(5);
                $this->min[] = $row->get(7);
                $this->max[] = $row->get(8);
                $this->cust_code[] = $row->get(6);

                // Buat entri baru di tabel
                $newItem = Ekanban_chuter_limit::create([
                    'chutter_address' => $row->get(0),
                    'itemcode' => $row->get(2),
                    'kanban_no' => $row->get(1), // Tambahkan kanban_no ke dalam create
                    'part_number' => $row->get(3),
                    'part_name' => $row->get(4),
                    'part_type' => $row->get(5),
                    'min' => $row->get(7),
                    'max' => $row->get(8),
                    'cust_code' => $row->get(6),
                    'is_active' => $is_active,
                    'action_name' => $action_name,
                    'action_user' => $action_user,
                    'action_date' => $action_date,
                    'period' => $period,
                ]);

                // Insert into log table
                $data_id = $newItem->id;
                $time = Carbon::now();
                $column_name = "";
                $old_value =  "";
                $new_value = "";

                Ekanban_chuter_limit_log::create([
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
    }


}
