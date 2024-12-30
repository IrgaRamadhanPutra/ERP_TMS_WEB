<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\FromCollection;

class ReportFgprinted implements FromView, ShouldAutoSize
{
    protected $dataArray;
    protected $date_format;
    protected $fromDatefg;
    protected $toDatefg;
    protected $kanbanNofg;
    protected $createdByfg;
    protected $itemCodefg;

    public function __construct(array $dataArray, $fromDatefg, $toDatefg, $date_format, $createdByfg, $kanbanNofg, $itemCodefg)
    {
        $this->dataArray = $dataArray;
        $this->date_format = $date_format;
        $this->fromDatefg = $fromDatefg;
        $this->toDatefg = $toDatefg;
        $this->kanbanNofg = $kanbanNofg;
        $this->createdByfg = $createdByfg;
        $this->itemCodefg = $itemCodefg; // Add this line if needed
    }
    public function view(): View
    {
        // dd($this-> dataArray);
        return view('tms.warehouse.Kanban-print-log.finish-good.report_excel', [
            'dataArray' => $this->dataArray,
            'date' => $this->date_format,
            'fromDate' => $this->fromDatefg,
            'toDate' => $this->toDatefg,
            'kanbanNofg' => $this->kanbanNofg,
            'createdByfg' => $this->createdByfg,
        ]);
    }
}
