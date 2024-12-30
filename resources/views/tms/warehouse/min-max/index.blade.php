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

        .edit-modal {
            overflow-y: scroll;
            height: calc(100% - 100px);
        }

        /* .create-modal,
            .view-modal {
                overflow-y: scroll;
                height: calc(100% - 100px);
            } */

        .uppercase-input {
            text-transform: uppercase;
        }

        .tr {
            background-color: red;
        }

        #loadingSpinner {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .spinner-container {
            font-size: 2em;
            /* Sesuaikan ukuran ikon sesuai kebutuhan */
        }

        #loadingSpinner p {
            margin-top: 10px;
            font-size: 14px;
            color: #333;
        }


        #masterMinMax-datatables tbody tr:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
            transition: box-shadow 0.3s ease;
            /* cursor: pointer; */
        }


        /* Tombol Add New Data */
        #addModalminmax {
            transition: background-color 0.3s, color 0.3s, box-shadow 0.3s, transform 0.3s;
        }

        #addModalminmax:hover {
            background-color: #1b80d8;
            /* Ganti dengan warna latar belakang yang diinginkan saat dihover */
            color: #fff;
            /* Ganti dengan warna teks yang diinginkan saat dihover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
            cursor: pointer;
        }

        /* Tombol Export Excel */
        #eksportExcel {
            transition: background-color 0.3s, color 0.3s, box-shadow 0.3s, transform 0.3s;
        }

        #eksportExcel:hover {
            background-color: #5cb85c;
            /* Ganti dengan warna latar belakang yang diinginkan saat dihover */
            color: #ececec;
            /* Ganti dengan warna teks yang diinginkan saat dihover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
            cursor: pointer;
        }

        /* Tombol Import Excel */
        #importExcel {
            transition: background-color 0.3s, color 0.3s, box-shadow 0.3s, transform 0.3s;
        }

        #importExcel:hover {
            background-color: #5cb85c;
            /* Ganti dengan warna latar belakang yang diinginkan saat dihover */
            color: #ffffff;
            /* Ganti dengan warna teks yang diinginkan saat dihover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
            cursor: pointer;
        }

        /* Tombol Change Kanban Chuter */
        #changeKanbanchuter {
            transition: background-color 0.3s, color 0.3s, box-shadow 0.3s, transform 0.3s;
        }

        #changeKanbanchuter:hover {
            background-color: #5bc0de;
            /* Ganti dengan warna latar belakang yang diinginkan saat dihover */
            color: #fff;
            /* Ganti dengan warna teks yang diinginkan saat dihover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
            cursor: pointer;
        }

        #backIndexminmax:hover {
            background-color: #de855b;
            /* Ganti dengan warna latar belakang yang diinginkan saat dihover */
            color: #fff;
            /* Ganti dengan warna teks yang diinginkan saat dihover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
            cursor: pointer;
        }

        #filerChuter {
            transition: background-color 0.3s, color 0.3s, box-shadow 0.3s, transform 0.3s;
        }

        #filerChuter:hover {
            background-color: #dec05b;
            color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
            cursor: pointer;
        }


        #filterMinMax-datatables tbody tr:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
            transition: box-shadow 0.3s ease;
            cursor: auto;
            /* Mengubah cursor menjadi default */
        }
    </style>
@section('content')
    <div class="main-content-inner">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="#">
                    <button type="button" class="btn btn-primary btn-flat btn-sm" id="addModalminmax">
                        <i class="ti-plus"></i> &nbsp;Add New Data
                    </button>
                    <!-- Tambahkan spasi di antara dua tombol -->
                    <span id="spasi" style="margin-right: 10px;"></span>
                    <button type="button" class="btn btn-success btn-flat btn-sm" id="eksportExcel">
                        <i class="fa fa-download"></i> &nbsp; Export Excel
                    </button>
                    <span id="spasi" style="margin-right: 10px;"></span>
                    <button type="button" class="btn btn-success btn-flat btn-sm" id="importExcel">
                        {{-- <i class="fa fa-file-excel-o"></i> --}}
                        <i class="fa fa-file-excel-o"></i> &nbsp; Import Excel Update Min Max
                    </button>
                    <span id="spasi" style="margin-right: 10px;"></span>
                    <button type="button" class="btn btn-info btn-flat btn-sm" id="changeKanbanchuter">
                        {{-- <i class="fa fa-file-excel-o"></i> --}}
                        <i class="fa fa-clone"></i> &nbsp; Change Kanban Chuter
                    </button>
                    <span id="spasi" style="margin-right: 10px;"></span>
                    <button type="button" class="btn btn-warning btn-flat btn-sm" id="filerChuter">
                        {{-- <i class="fa fa-file-excel-o"></i> --}}
                        <i class="fa fa-hourglass"></i> &nbsp; Filter Chuter
                    </button>
                    <span id="spasi" style="margin-right: 10px;"></span>
                    <button type="button" class="btn btn-flat btn-sm" id="backIndexminmax"
                        style="display: none; background-color: #db7f34;">
                        {{-- <i class="fa fa-file-excel-o"></i> --}}
                        <i class="fa fa-mail-reply-all"></i>&nbsp; Back
                    </button>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <h4 class="card-header-title">Master Min Max </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="loadingSpinner" class="text-center d-none">
                            <div class="spinner-container">
                                {{-- <i class="fa fa-repeat"></i> --}}
                            </div>
                            <p>Loading...</p>
                        </div>
                        <div class="row mt-3" id="masterMinmax">
                            <div class="col">
                                <!-- Dropdown for plant selection -->
                                <div class="form-group">
                                    <label for="plant">Select Plant:</label>
                                    <select id="plant" class="form-control" style="width: 200px;">
                                        <option value="">PILIH</option>
                                        <option value="1701">1701</option>
                                        <option value="1702">1702</option>
                                    </select>
                                </div>

                                <div class="datatable datatable-primary">
                                    <div class="table-responsive">
                                        <table id="masterMinMax-datatables" class="table table-bordered table-hover" style="width:100%">
                                            <thead class="text-center" style="text-transform: uppercase; font-size: 11px;">
                                                <tr>
                                                    <th width="10%">Chutter Address</th>
                                                    <th width="10%">Part No</th>
                                                    <th width="10%">Itemcode</th>
                                                    <th width="20%">Part Name</th>
                                                    <th width="10%">Part Type</th>
                                                    <th width="5%">Lot</th>
                                                    <th width="5%">Min</th>
                                                    <th width="5%">Max</th>
                                                    <th width="5%">Stock</th>
                                                    <th class="text-center" width="5%">ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- form input for Filter  --}}
                        <div id="filter" style="display: none;">
                            <div class="row align-items-end">
                                {{-- <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="chuter"><b>Chuter:</b></label>
                                        <select class="form-control form-control-sm" id="chuter" name="chuter">
                                            <option value="">Pilih</option>
                                            @foreach ($get_chuter as $chuter)
                                                <option value="{{ $chuter->chutter_address }}">{{ $chuter->chutter_address }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="chuter"><b>Chuter</b></label>
                                        <select class="form-control form-control-sm" id="chuter" name="chuter">
                                            <option value=""></option>
                                            @foreach ($get_chuter as $chuter)
                                                <option value="{{ $chuter->chutter_address }}">
                                                    {{ $chuter->chutter_address }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="chuter"><b>Chuter:</b></label>
                                        <input type="text" class="form-control form-control-sm" id="chuter"
                                            name="chuter" list="chuterList">
                                        <datalist id="chuterList">
                                            @foreach ($get_chuter as $chuter)
                                                <option value="{{ $chuter->chutter_address }}">
                                                    {{ $chuter->chutter_address }}</option>
                                            @endforeach
                                        </datalist>
                                    </div>
                                </div> --}}
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="fromDate"><b>From Date:</b></label>
                                        <input type="date" class="form-control form-control-sm" id="fromDate"
                                            name="fromDate">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="toDate"><b>To Date:</b></label>
                                        <input type="date" class="form-control form-control-sm" id="toDate"
                                            name="toDate">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <!-- Tambahkan id pada tombol -->
                                        <button type="button" class="btn btn-success"
                                            style="height: 30px; position: relative;" id="filterExportexcel">
                                            <i class="fa fa-file-excel-o"
                                                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                                            {{-- <i class="fa fa-hourglass-2 text-white" ></i> --}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- tbl for filter  --}}
                        <div class="row mt-3" id="filterMinmax" style="display: none;">
                            <div class="col">
                                <div class="datatable datatable-primary">
                                    <div class="table-responsive">
                                        <table id="filterMinMax-datatables" class="table table-bordered table-hover"
                                            style="width:100%">
                                            <thead class="text-center"
                                                style="text-transform: uppercase; font-size: 12px;">
                                                <tr>
                                                    <th width="8%">Chutter Address</th>
                                                    <th width="10%">Kanban No</th>
                                                    <th width="10%">Part No</th>
                                                    <th width="10%">Sequence</th>
                                                    <th width="5%">Qty</th>
                                                    <th width="10%">Kanban Print</th>
                                                    <th width="10%">In Date</th>
                                                    <th width="10%">Out Date</th>
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
@include('tms.warehouse.min-max.modal.create-min-max.create')
@include('tms.warehouse.min-max.modal.create-min-max.import_excel_create')
@include('tms.warehouse.min-max.modal.get-popup.get_itemcode_create')
@include('tms.warehouse.min-max.modal.view-min-max.view')
@include('tms.warehouse.min-max.modal.edit-min-max.edit')
@include('tms.warehouse.min-max.modal.log-min-max.log')
@include('tms.warehouse.min-max.modal.void-min-max.void')
@include('tms.warehouse.min-max.modal.import.import_excel_update')
@include('tms.warehouse.min-max.modal.change-kanban.change_kanban')
@include('tms.warehouse.min-max.Filter-chuter.filter')
{{-- @include('tms.warehouse.min-max.Filter-chuter.filter_ekspor_excel') --}}
{{-- @section('script')
    @include('tms.warehouse.master-item-auto.ajax')
@endsection --}}
@push('js')
    <!-- Datatables -->
    <script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            //get data from datatables
            var table = $('#masterMinMax-datatables').DataTable({
                // processing: true,
                serverSide: true,
                deferRender: true,
                responsive: true,
                ajax: {
                    url: "{{ route('tms.warehouse.get_masterminmax_datatables') }}",
                    data: function(d) {
                    d.plant = $('#plant').val();
                }
                },
                order: [
                    [9, 'desc'] // Urutkan berdasarkan kolom ke-9 (action_date) dalam urutan descending
                ],
                columnDefs: [{
                    "searchable": false,
                    "targets": [4, 5, 6, 7]
                }],
                // bInfo: false, /* untuk menghilangkan showing entries  */
                responsive: true,

                columns: [{
                        data: 'chutter_address',
                        className: "text-center",
                        render: function(data, type, row) {
                            var balance = parseFloat(row['stock']);
                            var min = parseFloat(row['min']);
                            var max = parseFloat(row['max']);

                            // Set the background color based on the conditions
                            if (!isNaN(balance) && !isNaN(min) && balance < min) {
                                return '<span style="background-color: red; display: block;">' +
                                    data + '</span>';
                            } else if (!isNaN(balance) && !isNaN(max) && balance > max) {
                                return '<span style="background-color: yellow; display: block;">' +
                                    data + '</span>';
                            } else {
                                return data;
                            }
                        },
                    },
                    {
                        data: 'part_number'
                    },
                    {
                        data: 'itemcode'
                    },
                    {
                        data: 'part_name'
                    },
                    {
                        data: 'part_type'
                    },
                    {
                        data: 'lot',
                        className: "text-center"
                    },
                    {
                        data: 'min',
                        className: "text-center"
                    },
                    {
                        data: 'max',
                        className: "text-center"
                    },
                    {
                        data: 'stock',
                        className: "text-center"
                    },
                    {
                        data: 'action'
                    },
                    {
                        data: 'action_date',
                        visible: false // Sembunyikan kolom action_date
                    }
                ],
                initComplete: function() {
                // Add event listener to the date input
                // $('#docNofg, #fromDatefg, #toDatefg').on('change','keypress', function() {
                //     table.draw();
                // });
                $('#plant').on('change',function() {table.draw();});


            }

            });

            // eksport excel

            $('#eksportExcel').on('click', function() {
                // Get the route URL for Excel download
                var excelMasterminmax = "{{ route('tms.warehouse.master_minmax.kanbanExcel') }}";

                // Trigger the download by changing the window location
                window.location.href = excelMasterminmax;
            });



        });

        // function reloadTable() {
        //     $('#masterMinMax-datatables').DataTable().ajax.reload();
        // }

        // // Panggil fungsi reloadTable() setiap detik (1000 milidetik)
        // setInterval(reloadTable, 1000);
    </script>
@endpush
