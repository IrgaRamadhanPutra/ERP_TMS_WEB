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

        .card.info-card {
            border: 2px solid #e0e0e0;
            /* Light grey border */
            border-radius: 15px;
            /* Rounded corners for the card */
        }

        .btn.chuter-btn {
            border-radius: 25px;
            /* Rounded corners for the button */
            transition: background-color 0.3s, transform 0.3s;
            /* Smooth transition for hover effects */
        }

        .btn.chuter-btn:hover {
            background-color: #062b7f;
            /* Darker shade of blue on hover */
            transform: scale(1.05);
            /* Slightly enlarge the button on hover */
        }

        /* font size thead & tbody */
        #tbl-data-over,
        #tbl-data-kritis tbody {
            font-size: 13px;
        }

        #tbl-data-over,
        #tbl-data-kritis table {
            font-size: 13px;
        }

        /* for buttion card chuter  */
        .card-button {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
        }

        .card-button::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background-color: rgba(6, 43, 127, 0.3);
            /* Warna latar belakang overlay */
            transition: all 0.3s ease;
            transform: translate(-50%, -50%) rotate(45deg);
            /* Mengatur posisi dan rotasi */
            opacity: 0;
            /* Awalnya tidak terlihat */
        }

        .card-button:hover::before {
            opacity: 1;
            /* Menampilkan overlay saat di-hover */
        }

        .chuter-btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            position: relative;
        }

        .chuter-btn:hover {
            background-color: #044090;
            /* Warna background tombol saat di-hover */
            transition: background-color 0.3s ease;
        }

        .chuter-btn:hover .bi-hand-index-thumb-fill {
            animation: thumb-wiggle 1s infinite;
            /* Animasi ikon saat di-hover */
        }

        @keyframes thumb-wiggle {
            0% {
                transform: rotate(0deg);
            }

            50% {
                transform: rotate(10deg);
            }

            100% {
                transform: rotate(0deg);
            }
        }

        .bi-hand-index-thumb-fill {
            margin-left: 5px;
            font-size: 1.2rem;
            vertical-align: middle;
        }




        /*  */
        .chart-inner {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;

            /* same height as the canvas */
        }

        .chart-data {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            pointer-events: none;
            /* Supaya tidak mengganggu interaksi dengan canvas */
        }
    </style>
@endsection
@section('content')
    <div class="main-content-inner">
        <div class="row">

            <!-- Card: Doughnut Charts with Header -->
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <h4 class="card-header-title">Monitoring Min Max By Itemcode</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Doughnut Chart 1 -->
                            <div class="col-6">
                                <div class="chart-title text-center">
                                    <h3>KRITIS</h3>
                                </div>
                                <div class="chart-container text-center">
                                    <div class="chart-inner">
                                        <canvas id="kritis" width="200" height="200"></canvas>
                                        <div class="chart-data">
                                            <h3>{{ $totalItemcodekritis }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Doughnut Chart 2 -->
                            <div class="col-6">
                                <div class="chart-title text-center">
                                    <h3>Over</h3>
                                </div>
                                <div class="chart-container text-center">
                                    <div class="chart-inner">
                                        <canvas id="over" width="200" height="200"></canvas>
                                        <div class="chart-data">
                                            <h3>{{ $totalItemcodeover }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- card monitoring mina max by chuter address --}}
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <h4 class="card-header-title">Monitoring Chuter By Chuter Address</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Chuter Card 1: Lantai 1 -->
                            <div class="col-xxl-4 col-md-6 mb-4">
                                <div class="card h-100 shadow">
                                    <div class="card-body card-button text-center">
                                        <h5 class="card-title">CHUTER LANTAI 1</h5>
                                        <a href="{{ route('tms.warehouse.index_chuter1_abnormality') }}"
                                            class="btn btn-default chuter-btn" style="background-color: #062b7f">
                                            <span class="text-white">Klik Disini</span>
                                            <i class="fa fa-hand-pointer-o"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Chuter Card 2: Lantai 2 -->
                            <div class="col-xxl-4 col-md-6 mb-4">
                                <div class="card h-100 shadow">
                                    <div class="card-body card-button text-center">
                                        <h5 class="card-title">CHUTER LANTAI 2</h5>
                                        <a href="{{ route('tms.warehouse.index_chuter2_abnormality') }}"
                                            class="btn btn-default chuter-btn" style="background-color: #062b7f">
                                            <span class="text-white">Klik Disini</span>
                                            <i class="fa fa-hand-pointer-o"></i>
                                        </a>
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
@include('tms.warehouse.monitoring-min-max.modal.show_kritis')
@include('tms.warehouse.monitoring-min-max.modal.show_over')

{{-- generate datatable mto-entry --}}
@push('js')
    <!-- Datatables -->
    <script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    {{-- <script src="{{ asset('js/monitoring_chart.js') }}"></script> --}}
    <script src="{{ asset('js/chart.js') }}"></script>
    <script src="{{ asset('js/chartjs-plugin-datalabels@2.0.js') }}"></script>
    <script src="{{ asset('js/chartjs-plugin-annotation.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Doughnut Chart 1: Kritis
            var totalItemcodekritis = @json($totalItemcodekritis);
            var totalItemcodeover = @json($totalItemcodeover);

            // Ensure the chart displays even when the data is 0
            var kritisData = totalItemcodekritis > 0 ? totalItemcodekritis : 0.01;
            var overData = totalItemcodeover > 0 ? totalItemcodeover : 0.01;

            // Doughnut Chart 1: Kritis
            var kritisCtx = document.getElementById('kritis').getContext('2d');
            var kritisChart = new Chart(kritisCtx, {
                type: 'doughnut',
                data: {
                    labels: [
                        'Kritis ' + totalItemcodekritis + ' Itemcode'
                    ],
                    datasets: [{
                        data: [
                            kritisData
                        ],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: true,
                        position: 'bottom', // Label ditempatkan di bagian bawah chart
                        onClick: null // Menonaktifkan aksi klik pada legend untuk menyembunyikan data
                    }
                }
            });

            // Doughnut Chart 2: Over
            var overCtx = document.getElementById('over').getContext('2d');
            var overChart = new Chart(overCtx, {
                type: 'doughnut',
                data: {
                    labels: [
                        'Over ' + totalItemcodeover + ' Itemcode'
                    ],
                    datasets: [{
                        data: [
                            overData
                        ],
                        backgroundColor: [
                            'rgba(255, 206, 86, 0.2)', // Warna kuning dengan opacity 0.2
                        ],
                        borderColor: [
                            'rgba(255, 206, 86, 1)', // Warna kuning solid
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: true,
                        position: 'bottom', // Label ditempatkan di bagian bawah chart
                        onClick: null // Menonaktifkan aksi klik pada legend untuk menyembunyikan data
                    }
                }
            });



            // Event listener untuk chart Kritis
            $('#kritis').on('click', function(event) {
                // Tampilkan modal dan isi dengan detail itemcode
                $('#detailModalkritis').modal('show');
                var tblKritis = $('#tbl-data-kritis').DataTable();
                tblKritis.destroy();
                var tblKritis = $('#tbl-data-kritis').DataTable({
                    "pagingType": "numbers",
                    ajax: {
                        url: "{{ route('tms.warehouse.getDatakritis') }}",
                    },
                    serverSide: true,
                    deferRender: true,
                    responsive: true,
                    "bFilter": false,
                    "order": [
                        [1, 'asc']
                    ],
                    searching: true,
                    columns: [

                        {
                            "data": "itemcode"
                        },
                        {
                            "data": "part_number"
                        },
                        {
                            "data": "part_name"
                        },
                        {
                            "data": "balance"
                        },
                        {
                            "data": "min"
                        },
                        {
                            "data": "max"
                        },


                    ],
                });
            });

            // Event listener untuk chart Over
            $('#over').on('click', function(event) {
                // Tampilkan modal dan isi dengan detail itemcode
                $('#detailModalover').modal('show');
                var tblOver = $('#tbl-data-over').DataTable();
                tblOver.destroy();
                var tblOver = $('#tbl-data-over').DataTable({
                    "pagingType": "numbers",
                    ajax: {
                        url: "{{ route('tms.warehouse.getDataover') }}",
                    },
                    serverSide: true,
                    deferRender: true,
                    responsive: true,
                    "bFilter": false,
                    "order": [
                        [1, 'asc']
                    ],
                    searching: true,
                    columns: [

                        {
                            "data": "itemcode"
                        },
                        {
                            "data": "part_number"
                        },
                        {
                            "data": "part_name"
                        },
                        {
                            "data": "balance"
                        },
                        {
                            "data": "min"
                        },
                        {
                            "data": "max"
                        },


                    ],
                    // "css": {
                    //     "table": {
                    //         "fontSize": "50px"
                    //     },
                    //     "tbody": {
                    //         "fontSize": "50px"
                    //     }
                    // }
                });
            });
        });
    </script>
@endpush
