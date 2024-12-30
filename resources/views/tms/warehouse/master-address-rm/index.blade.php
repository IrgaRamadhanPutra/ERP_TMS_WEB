@extends('master')
@section('title', 'TMS | Warehouse - Master Address Rm')
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


        #masterAddressRm-datatables tbody tr:hover {
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


        */
    </style>
@section('content')
    <div class="main-content-inner">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="#">
                    <button type="button" class="btn btn-primary btn-flat btn-sm" id="addModalMasterAddressRm">
                        <i class="ti-plus"></i> &nbsp;Add New Data
                    </button>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <h4 class="card-header-title">Master Address RM </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mt-3">
                            <div class="col">
                                <div class="datatable datatable-primary">
                                    <div class="table-responsive">
                                        <div class="datatable datatable-primary">
                                            <div class="table-responsive">
                                                <table id="masterAddressRm-datatables"
                                                    class="table table-bordered table-hover" style="width:100%">
                                                    <thead class="text-center"
                                                        style="text-transform: uppercase; font-size: 11px;">
                                                        <tr>
                                                            <th width="10%">Chuter Address</th>
                                                            <th width="10%">Itemcode</th>
                                                            <th width="10%">Part No</th>
                                                            <th width="10%">Part Name</th>
                                                            <th width="10%">Process Code</th>
                                                            <th width="5%">Cust Code</th>
                                                            <th width="10%">Supplier</th>
                                                            <th width="5%">Kanban Type</th>
                                                            <th width="5%">ACTION</th>
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
    </div>
    </div>

@endsection('content')
@include('tms.warehouse.master-address-rm.modal.create-address-rm.create')
@include('tms.warehouse.master-address-rm.modal.create-address-rm.import')
@include('tms.warehouse.master-address-rm.modal.view-adress-rm.view')
@include('tms.warehouse.master-address-rm.modal.edit-address-rm.edit')
@include('tms.warehouse.master-address-rm.modal.log-address-rm.log')
@include('tms.warehouse.master-address-rm.modal.void-address-rm.void')
@push('js')
    <!-- Datatables -->
    <script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            //get data from datatables
            var table = $('#masterAddressRm-datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('tms.warehouse.master_address_rm_datatables') }}",
                },
                order: [
                    [0, 'desc']
                ],
                responsive: true,
                columns: [{
                        data: 'chuter_address',

                    },
                    {
                        data: 'itemcode',

                    },

                    {
                        data: 'part_no',

                    },
                    {
                        data: 'part_name',

                    },
                    {
                        data: 'process_code',

                    },
                    {
                        data: 'cust_code',

                    },
                    {
                        data: 'supplier',

                    },
                    {
                        data: 'address_type',

                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });


            // ajax for pdf
            $(document).on('click', '.print', function(e) {
                e.preventDefault();

                var id_database = $(this).attr('row-id'); // Use 'row-id' to match the attribute in the HTML
                var chuter_address_database = $(this).attr('data-chuter'); // This one is correct

                // console.log(id_database); // This will now log the correct value
                // console.log(chuter_address_database);

                // Buat route URL dinamis dengan mengganti placeholder :id dan :chuter_address
                var route =
                    "{{ route('tms.warehouse.master_address_rm.get_address_pdf', [':id', ':chuter_address']) }}";

                // Ganti placeholder :id dengan id_database
                route = route.replace(':id', id_database);

                // Ganti placeholder :chuter_address dengan chuter_address_database
                route = route.replace(':chuter_address', chuter_address_database);


                // Kirim AJAX request untuk mengambil PDF
                $.ajax({
                    url: route, // Gunakan route yang sudah diubah dengan itemcode_database
                    type: 'GET',
                    xhrFields: {
                        responseType: 'blob' // Pastikan respons berupa blob untuk PDF
                    },
                    success: function(response) {
                        // Jika sukses, buka PDF di window/tab baru
                        var blob = new Blob([response], {
                            type: 'application/pdf'
                        });
                        var pdfUrl = URL.createObjectURL(blob);
                        window.open(pdfUrl, '_blank'); // Buka di tab baru
                    },
                    error: function(xhr) {
                        alert('Error generating PDF');
                    }
                });
            });
        });
    </script>
@endpush
