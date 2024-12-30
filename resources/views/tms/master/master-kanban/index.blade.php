@extends('master')
@section('title', 'TMS | Warehouse - Master Min Max')
@section('css')
    <!-- DATATABLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/datetimepicker/css/bootstrap-datetimepicker.css') }}" />
    <script src="{{ asset('vendor/datetimepicker/js/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/datetimepicker/js/moment.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <script src="{{ asset('vendor/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <style>
        input[type=search] {
            text-transform: uppercase;
        }
    </style>
@section('content')
    <div class="main-content-inner">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="#">
                    <button type="button" class="btn btn-primary btn-flat btn-sm" id="addModalkanban">
                        <i class="ti-plus"></i> &nbsp;Add New Data
                    </button>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <h4 class="card-header-title">Master Kanban</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="loadingSpinner" class="text-center d-none">
                            <div class="spinner-container">
                                {{-- <i class="fa fa-repeat"></i> --}}
                            </div>
                            <p>Loading...</p>
                        </div>
                        <div class="row mt-3" id="masterkanban">
                            <div class="col">
                                <!-- Dropdown for plant selection -->
                                <div class="form-group">
                                    <label for="branch">Select Branch:</label>
                                    <select id="branch" class="form-control" style="width: 200px;">
                                        <option value="">Choice</option>
                                        <option value="1701">1701</option>
                                        <option value="1702">1702</option>
                                    </select>
                                </div>

                                <div class="datatable datatable-primary">
                                    <div class="table-responsive">
                                        <table id="masterKanban-datatables" class="table table-bordered table-hover"
                                            style="width:100%">
                                            <thead class="text-center" style="text-transform: uppercase; font-size: 11px;">
                                                <tr>
                                                    <th width="10%">Kanban No</th>
                                                    <th width="10%">Itemcode</th>
                                                    <th width="10%">Part No</th>
                                                    <th width="20%">Part Name</th>
                                                    <th width="5%">Part Type</th>
                                                    <th width="5%">Cust</th>
                                                    <th width="5%">Sloc</th>
                                                    <th width="5%">Qty Kanban</th>
                                                    <th width="11%">Created Date</th>
                                                    <th width="5%">Created By</th>
                                                    <th class="text-center" width="5%">ACTION</th>
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
{{-- @include('tms.warehouse.master-kanban.modal.create-kanban.create')
@include('tms.warehouse.master-kanban.modal.view-kanban.view')
@include('tms.warehouse.master-kanban.modal.edit-kanban.edit')
@include('tms.warehouse.master-kanban.modal.log-kanban.log')
@include('tms.warehouse.master-kanban.modal.void-kanban.void') --}}
@include('tms.master.master-kanban.modal.create-kanban.create')
@include('tms.master.master-kanban.modal.view-kanban.view')
@include('tms.master.master-kanban.modal.edit-kanban.edit')
@include('tms.master.master-kanban.modal.log-kanban.log')
@include('tms.master.master-kanban.modal.void-kanban.void')
@push('js')
    <!-- Datatables -->
    <script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            //get data from datatables
            var table = $('#masterKanban-datatables').DataTable({
                // pro  cessing: true,
                serverSide: true,
                deferRender: true,
                responsive: true,
                ajax: {
                    url: "{{ route('tms.warehouse.get_masterkanban_datatables') }}",
                    data: function(d) {
                        d.branch = $('#branch').val();
                    }
                },
                order: [
                    [8, 'desc'] // Urutkan berdasarkan kolom ke-9 (action_date) dalam urutan descending
                ],
                columnDefs: [{
                    "searchable": false,
                    "targets": [4, 5, 6, 7]
                }],
                // bInfo: false, /* untuk menghilangkan showing entries  */
                responsive: true,

                columns: [{
                        data: 'ekanban_no'
                    },
                    {
                        data: 'item_code'
                    },
                    {
                        data: 'part_no'
                    },
                    {
                        data: 'part_name'
                    },
                    {
                        data: 'model'
                    },
                    {
                        data: 'customer',
                        className: "text-center"
                    },
                    {
                        data: 'sloc',
                        className: "text-center"
                    },
                    {
                        data: 'qty_kanban',
                        className: "text-center"
                    },
                    {
                        data: 'creation_date'

                    },
                    {
                        data: 'created_by',
                        className: "text-center"
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    } // Atur kolom action di sini
                ],
                initComplete: function() {
                    // Add event listener to the date input
                    $('#branch').on('change', function() {
                        table.draw();
                    });


                }

            });

        });
    </script>
@endpush
