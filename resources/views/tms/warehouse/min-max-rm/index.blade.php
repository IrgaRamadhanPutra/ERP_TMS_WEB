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


        #masterMinMaxRm-datatables tbody tr:hover {
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

        }

        */
    </style>
@section('content')
    <div class="main-content-inner">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="#">
                    <button type="button" class="btn btn-primary btn-flat btn-sm" id="addModalminmaxrm">
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
                            <h4 class="card-header-title">Master Min Max Raw Material </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="loadingSpinner" class="text-center d-none">
                            <div class="spinner-container">
                                {{-- <i class="fa fa-repeat"></i> --}}
                            </div>
                            <p>Loading...</p>
                        </div>
                        <div class="row mt-3" id="masterMinmaxrm">
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
                                        <table id="masterMinMaxRm-datatables" class="table table-bordered table-hover"
                                            style="width:100%">
                                            <thead class="text-center" style="text-transform: uppercase; font-size: 11px;">
                                                <tr>
                                                    <th width="10%">Chutter Address</th>
                                                    <th width="10%">Part No</th>
                                                    <th width="10%">Itemcode</th>
                                                    <th width="20%">Part Name</th>
                                                    <th width="10%">Cust COde</th>
                                                    <th width="5%">Unit</th>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection('content')
@include('tms.warehouse.min-max-rm.modal.create-min-max-rm.create')
@include('tms.warehouse.min-max-rm.modal.create-min-max-rm.import')
@include('tms.warehouse.min-max-rm.modal.view-min-max-rm.view')
@include('tms.warehouse.min-max-rm.modal.edit-min-max-rm.edit')
@include('tms.warehouse.min-max-rm.modal.void-min-max-rm.void')
@include('tms.warehouse.min-max-rm.modal.log-min-max-rm.log')

@push('js')
    <!-- Datatables -->
    <script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var table = $('#masterMinMaxRm-datatables').DataTable({
                serverSide: true,
                deferRender: true,
                responsive: true,
                processing: true,
                language: {
                    processing: "<div class='spinner-border text-primary' role='status'><span class='sr-only'>Loading...</span></div>", // Kustomisasi loading
                },
                ajax: {
                    url: "{{ route('tms.warehouse.get_master_min_max_rm_datatables') }}",
                    data: function(d) {
                        d.plant = $('#plant').val(); // Add plant parameter if needed
                    }
                },
                columns: [{
                        data: 'chutter_address'

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
                        data: 'cust_code'

                    }, // Assuming cust_code refers to Part Type
                    {
                        data: 'satuan'

                    },
                    {
                        data: 'min'

                    },
                    {
                        data: 'max'

                    },
                    {
                        data: 'balance'

                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                order: [
                    [9, 'desc']
                ],
                initComplete: function() {
                    $('#plant').on('change', function() {
                        table.draw();
                    });
                }
            });
        });

        $('#eksportExcel').on('click', function() {
            // Get the selected plant value
            var selectedPlant = $('#plant').val();

            // Get the base route URL for Excel download
            var excelMasterMinMaxRm = "{{ route('tms.warehouse.master_min_max_rm.exportExcel') }}";
            // Append the selected plant as a query parameter
            var downloadUrl = excelMasterMinMaxRm + "?plant=" + encodeURIComponent(selectedPlant);

            // Trigger the download by changing the window location
            window.location.href = downloadUrl;
        });
    </script>
@endpush
