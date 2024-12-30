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


        /* Tombol Add New Data */
        #addModalminmaxchuter {
            transition: background-color 0.3s, color 0.3s, box-shadow 0.3s, transform 0.3s;
        }

        #addModalminmaxchuter:hover {
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
    </style>
@section('content')
    <div class="main-content-inner">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="#">
                    <button type="button" class="btn btn-primary btn-flat btn-sm" id="addModalminmaxchuter">
                        <i class="ti-plus"></i> &nbsp;Add New Data
                    </button>
                    <!-- Tambahkan spasi di antara dua tombol -->
                    <span id="spasi" style="margin-right: 10px;"></span>
                    <button type="button" class="btn btn-success btn-flat btn-sm" id="eksportExcel">
                        <i class="fa fa-download"></i> &nbsp; Export Excel
                    </button>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <h4 class="card-header-title">Master Min Max Chuter</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="loadingSpinner" class="text-center d-none">
                            <div class="spinner-container">
                                {{-- <i class="fa fa-repeat"></i> --}}
                            </div>
                            <p>Loading...</p>
                        </div>
                        <div class="row mt-3" id="masterMinmaxchuter">
                            <div class="col">
                                <div class="datatable datatable-primary">
                                    <div class="table-responsive">
                                        <table id="masterMinMaxchuter-datatables" class="table table-bordered table-hover"
                                            style="width:100%">
                                            <thead class="text-center" style="text-transform: uppercase; font-size: 11px;">
                                                <tr>
                                                    <th width="10%">Chutter Address</th>
                                                    <th width="10%">Kanban No</th>
                                                    <th width="10%">Itemcode</th>
                                                    <th width="10%">Part No</th>
                                                    <th width="20">Part Name</th>
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
                                        {{-- tbl for filter --}}

                                        {{-- <div id="totalRows"></div> --}}
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
@include('tms.warehouse.chuter-minmax.modal.create-chuter-minmax.create')
@include('tms.warehouse.chuter-minmax.modal.get-popup.get_itemcode_create')
@include('tms.warehouse.chuter-minmax.modal.create-chuter-minmax.import_excel_create')
@includeIf('tms.warehouse.chuter-minmax.modal.view-chuter-minmax.view')
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
            var table = $('#masterMinMaxchuter-datatables').DataTable({
                processing: true,
                serverSide: true,
                deferRender: true,
                responsive: true,
                ajax: {
                    url: "{{ route('tms.warehouse.getmaster_min_max_chuter_datatables') }}",
                },
                order: [
                    [10, 'desc'] // Urutkan berdasarkan kolom ke-9 (action_date) dalam urutan descending
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
                        data: 'kanban_no'
                    },

                    {
                        data: 'itemcode'
                    },
                    {
                        data: 'part_number'
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

            });

            // eksport excel

            // $('#eksportExcel').on('click', function() {
            //     // Get the route URL for Excel download
            //     var excelMasterminmax = "{{ route('tms.warehouse.master_minmax.kanbanExcel') }}";

            //     // Trigger the download by changing the window location
            //     window.location.href = excelMasterminmax;
            // });



        });

        // function reloadTable() {
        //     $('#masterMinMax-datatables').DataTable().ajax.reload();
        // }

        // // Panggil fungsi reloadTable() setiap detik (1000 milidetik)
        // setInterval(reloadTable, 1000);
    </script>
@endpush
