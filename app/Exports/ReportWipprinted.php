<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportWipprinted  implements FromView, ShouldAutoSize
{
    protected $data;
    protected $date_format;
    protected $fromDatewip;
    protected $toDatewip;
    protected $kanbanNowip;
    protected $createdBywip;

    public function __construct(array $data,$fromDatewip,$toDatewip, $date_format, $createdBywip, $kanbanNowip)
    {
        $this->data = $data;
        $this->date_format = $date_format;
        $this->fromDatewip = $fromDatewip;
        $this->toDatewip = $toDatewip;
        $this->createdBywip = $createdBywip;
        $this->kanbanNowip = $kanbanNowip;
    }
    public function view(): View
    {
        // dd($this-> data);
        return view('tms.warehouse.Kanban-print-log.wip.report_excel', [
            'data' => $this->data,
            'date' => $this->date_format,
            'fromDate' => $this->fromDatewip,
            'toDate' => $this->toDatewip,
            'createdBywip'=>$this->createdBywip,
            'kanbanNowip' => $this->kanbanNowip,

        ]);
    }

}
