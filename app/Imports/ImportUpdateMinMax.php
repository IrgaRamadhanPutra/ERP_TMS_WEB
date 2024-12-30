<?php

namespace App\Imports;

use App\Models\Ekanban\Ekanban_stock_limit;
use App\Models\Ekanban\Ekanban_stock_limit_log; // Import the log model
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ImportUpdateMinMax implements ToCollection, WithHeadingRow
{
    protected $chutter_address = [];
    protected $itemCode = [];
    protected $part_number = [];
    protected $part_name = [];
    protected $part_type = [];
    protected $min = [];
    protected $max = [];
    protected $cust_code = [];
    protected $plant = []; // Property for plant
    protected $stock_type = [];
    protected $period = [];

    /**
     * Process the imported data from Excel and update records in Ekanban_stock_limit.
     *
     * @param Collection $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        // Set the timezone
        date_default_timezone_set('Asia/Jakarta');

        // Initialize variables for the update process
        $updates = [];
        $action_name_update = 'UPDATE';
        $action_user = Auth::user()->UserID;

        // Iterate through each row of the imported data
        foreach ($rows as $row) {
            $itemcode = $row['itemcode'];
            $period = $row['period'];
            $min = $row['min'];
            $max = $row['max'];

            // Prepare update data for each item
            $updates[$itemcode] = [
                'period' => $period,
                'min' => $min,
                'max' => $max,
                'action_date' => Carbon::now(), // Set current time for action_date
                'action_name' => $action_name_update // Action name for the update
            ];
        }

        // Perform the batch update on Ekanban_stock_limit table
        foreach ($updates as $itemcode => $updateData) {
            // Fetch the existing record before the update for logging purposes
            $oldItem = Ekanban_stock_limit::where('itemcode', $itemcode)
                ->where('period', $updateData['period'])
                ->first();

            if ($oldItem) {
                $oldItemId = $oldItem->id; // Get the old record ID
                $oldMin = $oldItem->min;
                $oldMax = $oldItem->max;

                // Update the record with the new data
                Ekanban_stock_limit::where('itemcode', $itemcode)
                    ->where('period', $updateData['period'])
                    ->update($updateData);

                // Record the changes in the log if values differ
                $time = Carbon::now();


                    Ekanban_stock_limit_log::create([
                        'table_id' => $oldItemId,
                        'action_name' => $action_name_update,
                        'column_name' => 'min',
                        'old_value' => $oldMin,
                        'new_value' => $updateData['min'],
                        'action_date' => $time,
                        'action_user' => $action_user,
                    ]);



                    Ekanban_stock_limit_log::create([
                        'table_id' => $oldItemId,
                        'action_name' => $action_name_update,
                        'column_name' => 'max',
                        'old_value' => $oldMax,
                        'new_value' => $updateData['max'],
                        'action_date' => $time,
                        'action_user' => $action_user,
                    ]);

            }
        }
    }
}
