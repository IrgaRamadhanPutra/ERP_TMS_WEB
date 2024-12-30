<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportFilterchuter implements FromView, ShouldAutoSize
{
    protected $data;
    protected $date_format;
    protected $fromDate;
    protected $toDate;

    public function __construct(array $data, $fromDate, $toDate, $date_format)
    {
        $this->data = $data;
        $this->date_format = $date_format;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function view(): View
    {
        return view('tms.warehouse.min-max.Filter-chuter.filter_ekspor_excel', [
            'data' => $this->data,
            'date' => $this->date_format,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
        ]);
    }
}

