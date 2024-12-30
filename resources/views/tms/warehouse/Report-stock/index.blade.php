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
                {{-- <div class="#">
                    <button type="button" class="btn btn-primary btn-flat btn-sm" id="btnFg">
                        &nbsp; Report Stock
                    </button>
                    <!-- Tambahkan spasi di antara dua tombol -->
                    <span id="spasi" style="margin-right: 10px;"></span>
                    <button type="button" class="btn btn-success btn-flat btn-sm" id="btnWip">
                        &nbsp; kanban WIP
                    </button>
                </div> --}}
                {{-- card kanban log finish good  --}}
                <div class="card mt-3" id="cardFg">
                    <div class="card-header">
                        <div class="row">
                            <h4 class="card-header-title">Report Stock</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="filterForm">
                            <div class="row align-items-end">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <form method="POST" action="{{ route('tms.warehouse.report_stock') }}">
                                            @csrf <!-- This is crucial for POST requests -->
                                            <label for="plant"><b>PLANT</b></label>
                                            <select class="form-control form-control-sm" id="plant" name="plant">
                                                <option value="">Pilih Plant</option>
                                                @foreach ($get_plant as $plant)
                                                    <option value="{{ $plant->plant }}">{{ $plant->plant }}</option>
                                                @endforeach
                                            </select>
                                        </form>

                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="slocNo"><b>SLOC</b></label>
                                        <select class="form-control form-control-sm" id="slocNo" name="slocNo">
                                            <option value="">Pilih Sloc</option>
                                            @foreach ($get_sloc as $sloc)
                                                <option value="{{ $sloc->sloc }}">{{ $sloc->sloc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-success"
                                            style="height: 35px; width:50px; position: relative;" id="slocExportexcel">
                                            <i class="fa fa-file-excel-o"
                                                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div id="loadingSpinner" class="text-center d-none">
                            <div class="spinner-container">
                            </div>
                            <p>Loading...</p>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <div class="datatable datatable-primary">
                                    <div class="table-responsive">
                                        <table id="reportStock-datatables" class="table table-bordered table-hover"
                                            style="width:100%">
                                            <thead class="text-center" style="text-transform: uppercase; font-size: 11px;">
                                                <tr>
                                                    <th width="10%">material no</th>
                                                    <th width="10%">part no</th>
                                                    <th width="20%">material desc</th>
                                                    <th width="5%">plant</th>
                                                    <th width="5%">sloc</th>
                                                    <th class="text-center" width="5%">qty</th>
                                                    <th width="5%">satuan</th>
                                                    <th width="5%">satuan conv</th>
                                                    <th width="5%">qty conv</th>
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
<script>
    //get data from datatables
    $(document).ready(function() {

        $('#plant').on('change', function() {
            var plant = $(this).val();
            $.ajax({
                url: '{{ route('tms.warehouse.report_stock') }}',
                method: 'GET',
                data: {
                    plant: plant
                },
                success: function(response) {
                    var slocSelect = $('#slocNo');
                    slocSelect.empty();
                    slocSelect.append('<option value="">Pilih Sloc</option>');
                    $.each(response, function(index, sloc) {
                        slocSelect.append('<option value="' + sloc.sloc + '">' +
                            sloc.sloc + '</option>');
                    });
                }
            });
        });
        // input plan
        $('#plant').select2({
            placeholder: "Pilih plan",
            allowClear: true,
            width: '100%'
        });
        //input sloc
        $('#slocNo').select2({
            placeholder: "Pilih Sloc",
            allowClear: true,
            width: '100%'
        });

        var table = $('#reportStock-datatables').DataTable({
            processing: true,
            serverSide: true,
            deferRender: true,
            responsive: true,
            searching: false,
            ajax: {
                url: "{{ route('tms.warehouse.report_stock_datatables') }}",
                type: 'GET',
                data: function(d) {
                    d.slocNo = $('#slocNo').val();
                    d.plant = $('#plant').val();
                },
                error: function(xhr, error, code) {

                    var errorMessage = xhr.responseJSON
                        .error; // Mendapatkan pesan error dari response JSON

                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: errorMessage // Menampilkan pesan error dari server
                    });

                }
            },
            columnDefs: [{
                "searchable": false,
                "targets": [4, 5, 6, 7]
            }],
            columns: [{
                    data: 'material_no',
                    render: function(data) {
                        return '<span style="font-size: 14px;">' + data + '</span>';
                    }
                },
                {
                    data: 'old_matno',
                    render: function(data) {
                        return '<span style="font-size: 14px;">' + data + '</span>';
                    }
                },
                {
                    data: 'material_desc',
                    render: function(data) {
                        return '<span style="font-size: 14px;">' + data + '</span>';
                    }
                },
                {
                    data: 'plant',
                    render: function(data) {
                        return '<span style="font-size: 14px; display: block; text-align: center;">' +
                            data + '</span>';
                    }
                },
                {
                    data: 'sloc',
                    render: function(data) {
                        return '<span style="font-size: 14px; display: block; text-align: center;">' +
                            data + '</span>';
                    }
                },
                {
                    data: 'quantity',
                    render: function(data) {
                        return '<span style="font-size: 14px; display: block; text-align: center;">' +
                            data + '</span>';
                    }
                },
                {
                    data: 'satuan',
                    render: function(data) {
                        return '<span style="font-size: 14px; display: block; text-align: center;">' +
                            data + '</span>';
                    }
                },
                {
                    data: 'satuan_conv',
                    render: function(data) {
                        return '<span style="font-size: 14px; display: block; text-align: center;">' +
                            data + '</span>';
                    }
                },
                {
                    data: 'quantity_conv',
                    render: function(data) {
                        return '<span style="font-size: 14px; display: block; text-align: center;">' +
                            data + '</span>';
                    }
                }
            ],
            initComplete: function() {
                // Tambahkan event listener pada input slocNo dan plant
                $('#slocNo, #plant').on('change', function() {
                    table.draw();
                });
            },
            responsive: true
        });



        // ambil data dengan id
        let slocNo = $('#slocNo').val();
        let plan = $('#plan').val();
        // export excel sloc
        $('#slocExportexcel').on('click', function() {
            var plant = $('#plant').val();
            var slocNo = $('#slocNo').val();
            if (plant === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error...',
                    text: 'Column cannot be empty'
                });
            } else {
                $.ajax({
                    url: '{{ route('tms.warehouse.report_stock.report_slocExcel') }}',
                    type: 'GET',
                    data: {
                        slocNo: slocNo,
                        plant: plant
                    },
                    success: function(response) {
                        if (response.message && response.message ===
                            "Data Not Found") {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error...',
                                text: 'Data Not Found'
                            });
                        } else {
                            // Redirect to download Excel
                            window.location.href =
                                '{{ route('tms.warehouse.report_stock.report_slocExcel') }}?slocNo=' +
                                slocNo + '&plant=' + plant;
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.responseJSON.error ||
                            'Data Not Found';

                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: errorMessage
                        });
                    }
                });

            }
        });

    });

    // function reloadTable() {
    //     $('#masterMinMax-datatables').DataTable().ajax.reload();
    // }

    // // Panggil fungsi reloadTable() setiap detik (1000 milidetik)
    // setInterval(reloadTable, 1000);
</script>
@endpush
