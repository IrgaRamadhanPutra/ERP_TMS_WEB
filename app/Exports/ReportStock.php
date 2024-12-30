<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportStock implements FromView, ShouldAutoSize
{
    protected $data;
    protected $date_format;
    protected $plant;
    protected $slocNo;

    public function __construct(array $data, $date_format,$plant, $slocNo)
    {
        $this->data = $data;
        $this->date_format = $date_format;
        $this->slocNo = $slocNo;
        $this->plant = $plant;

    }

    public function view(): View
    {
        // dd($this->data);
        return view('tms.warehouse.Report-stock.report_excel', [
            'data' => $this->data,
            'date' => $this->date_format,
            'plant'=>$this->plant,
            'slocNo'=>$this->slocNo,
        ]);
    }
}
