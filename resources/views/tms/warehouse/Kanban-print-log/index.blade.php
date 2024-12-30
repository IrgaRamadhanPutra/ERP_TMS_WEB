@extends('master')
@section('title', 'TMS | Warehouse - Kanban Print Log')
@section('css')
    <!-- DATATABLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/datetimepicker/css/bootstrap-datetimepicker.css') }}" />
    <script src="{{ asset('vendor/datetimepicker/js/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/datetimepicker/js/moment.min.js') }}"></script>
    <script src="{{ asset('vendor/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        input[type=search] {
            text-transform: uppercase;
        }

        .card {
            display: none;
        }
    </style>
@section('content')
@section('content')
    <div class="main-content-inner">
        <div class="row">
        </div>
        <div class="row">
            <div class="col-12 mt-5">
                <div class="#">
                    <button type="button" class="btn btn-primary btn-flat btn-sm" id="btnFg">
                        &nbsp;Kanban Finish Good
                    </button>
                    <!-- Tambahkan spasi di antara dua tombol -->
                    <span id="spasi" style="margin-right: 10px;"></span>
                    <button type="button" class="btn btn-success btn-flat btn-sm" id="btnWip">
                        &nbsp; kanban WIP
                    </button>
                </div>
                {{-- card kanban log finish good  --}}
                <div class="card mt-3" id="cardFg">
                    <div class="card-header">
                        <div class="row">
                            <h4 class="card-header-title">Kanban Print Log Finish Good</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="kanbanNofg"><b>Kanban No</b></label>
                                    <select class="form-control form-control-sm" id="kanbanNofg" name="kanbanNofg">
                                        <option value="">--CHOOSE--</option>
                                        @foreach ($get_kanban_Itemcode1 as $no)
                                            <option value="{{ $no->ekanban_no }}">{{ $no->ekanban_no }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="itemCodefg"><b>Itemcode</b></label>
                                    <select class="form-control form-control-sm" id="itemCodefg" name="itemCodefg">
                                        <option value="">--CHOOSE--</option>
                                        @foreach ($get_kanban_Itemcode1 as $no)
                                            <option value="{{ $no->item_code }}">{{ $no->item_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="statusDocsenfg"><b>Status</b></label>
                                    <select class="form-control form-control-sm" id="statusDocsenfg" name="statusDocsenfg">
                                        <option value="">--CHOOSE--</option>
                                        <option value="NULL">NOT SCAN IN</option>
                                        <option value="NOT_NULL">ALREADY SCAN IN</option>
                                        <option value="0">ABNORMALITY</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="createdByfg"><b>Created By</b></label>
                                    <select class="form-control form-control-sm" id="createdByfg" name="createdByfg">
                                        <option value="">--CHOOSE--</option>
                                        @foreach ($get_createdBy1 as $createdBy)
                                            <option value="{{ $createdBy->created_by }}">{{ $createdBy->created_by }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="fromDatefg"><b>From Date:</b></label>
                                    <input type="date" class="form-control form-control-sm" id="fromDatefg"
                                        name="fromDatefg">
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="toDatefg"><b>To Date:</b></label>
                                    <input type="date" class="form-control form-control-sm" id="toDatefg"
                                        name="toDatefg">
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <!-- Tambahkan id pada tombol -->
                                    <button type="button" class="btn btn-success" style="height: 30px; position: relative;"
                                        id="fgprintExportexcel">
                                        <i class="fa fa-file-excel-o"
                                            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                                        {{-- <i class="fa fa-hourglass-2 text-white" ></i> --}}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="loadingSpinner" class="text-center d-none">
                            <div class="spinner-container">
                                {{-- <i class="fa fa-repeat"></i> --}}
                            </div>
                            <p>Loading...</p>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <div class="datatable datatable-primary">
                                    <div class="table-responsive">
                                        <table id="kanbanFgprintlog-datatables" class="table table-bordered table-hover"
                                            style="width:100%">
                                            <thead class="text-center" style="text-transform: uppercase; font-size: 11px;">
                                                <tr>
                                                    {{-- <th width="10%">Doc No Send</th>
                                                    <th width="10%">Doc No Rec</th> --}}
                                                    <th width="10%">Kanban No</th>
                                                    <th width="10%">Itemcode</th>
                                                    <th width="10%">Part No</th>
                                                    <th width="20%">Part Name</th>
                                                    <th width="10%">Seq</th>
                                                    <th width="10%">Seq Total</th>
                                                    <th width="10%">kanban Qty</th>
                                                    <th width="10%">kanban Qty Total</th>
                                                    <th width="10%">Created By</th>
                                                    <th width="10%">Creation Date</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- card kanban log Wip  --}}
                <div class="card mt-3" id="cardWip" style="display: none;">
                    <div class="card-header">
                        <div class="row">
                            <h4 class="card-header-title">Kanban Print Log WIP</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="kanbanNowip"><b>Kanban No</b></label>
                                    <select class="form-control form-control-sm" id="kanbanNowip" name="kanbanNowip">
                                        <option value="">--CHOOSE--</option>
                                        @foreach ($get_kanban_Itemcode2 as $no)
                                            <option value="{{ $no->ekanban_no }}">{{ $no->ekanban_no }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="itemCodewip"><b>Itemcode</b></label>
                                    <select class="form-control form-control-sm" id="itemCodewip" name="itemCodewip">
                                        <option value="">--CHOOSE--</option>
                                        @foreach ($get_kanban_Itemcode2 as $no)
                                            <option value="{{ $no->item_code }}">{{ $no->item_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="statusDocsenwip"><b>Status</b></label>
                                    <select class="form-control form-control-sm" id="statusDocsenwip"
                                        name="statusDocsenwip">
                                        <option value="">--CHOOSE--</option>
                                        <option value="NULL">NOT SCAN IN</option>
                                        <option value="NOT_NULL">ALREADY SCAN IN</option>
                                        <option value="0">ABNORMALITY</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="createdBywip"><b>Created By</b></label>
                                    <select class="form-control form-control-sm" id="createdBywip" name="createdBywip">
                                        <option value="">--CHOOSE--</option>
                                        @foreach ($get_createdBy2 as $createdBy)
                                            <option value="{{ $createdBy->created_by }}">{{ $createdBy->created_by }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="fromDatewip"><b>From Date:</b></label>
                                    <input type="date" class="form-control form-control-sm " id="fromDatewip"
                                        name="fromDatewip">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="toDatewip"><b>To Date:</b></label>
                                    <input type="date" class="form-control form-control-sm " id="toDatewip"
                                        name="toDatewip">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <!-- Tambahkan id pada tombol -->
                                    <button type="button" class="btn btn-success"
                                        style="height: 30px; position: relative;" id="WipprintExportexcel">
                                        <i class="fa fa-file-excel-o"
                                            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="loadingSpinner" class="text-center d-none">
                            <div class="spinner-container">

                            </div>
                            <p>Loading...</p>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <div class="datatable datatable-primary">
                                    <div class="table-responsive">
                                        <table id="kanbanWipprintlog-datatables" class="table table-bordered table-hover"
                                            style="width:100%">
                                            <thead class="text-center"
                                                style="text-transform: uppercase; font-size: 11px;">
                                                <tr>
                                                    <th width="10%">Kanban No</th>
                                                    <th width="10%">Itemcode</th>
                                                    <th width="20%">Part No</th>
                                                    <th width="10%">Seq</th>
                                                    <th width="10%">Seq Total</th>
                                                    <th width="10%">kanban Qty</th>
                                                    <th width="10%">kanban Qty Total</th>
                                                    <th width="10%">Created By</th>
                                                    <th width="10%">Creation Date</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection('content')
@endsection('content')
@push('js')
<!-- Datatables -->
<script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
{{-- <script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script> --}}
{{-- ajax kanban log print --}}
@include('tms.warehouse.Kanban-print-log.finish-good.ajax')
@include('tms.warehouse.Kanban-print-log.wip.ajax')
@endpush
