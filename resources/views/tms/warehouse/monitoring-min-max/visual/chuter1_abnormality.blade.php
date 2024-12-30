<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'TMS | Trimitra Manufacturing System')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/tms-icon-blue.ico') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <!-- SRTDash native css -->
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/slicknav.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/typography.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/default-css.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/responsive.css') }}">

    <!-- Custom css -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <!-- modernizr css -->
    <script src="{{ asset('vendor/srtdash/js/vendor/modernizr-2.8.3.min.js') }}"></script>

    <script src="{{ asset('vendor/srtdash/js/vendor/jquery-2.2.4.min.js') }}"></script>

    @yield('css')

</head>
<style>
    /* custom body */
    body.custom_page::before {
        content: '';
        position: fixed;
        /* Change from absolute to fixed to cover the entire viewport */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        background-image:
            linear-gradient(to bottom, rgba(255, 255, 255, 0.6), rgba(0, 0, 0, 0.6)),
            linear-gradient(135deg, rgba(31, 16, 16, 0.25) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.25) 50%, rgba(255, 255, 255, 0.25) 75%, transparent 75%, transparent),
            linear-gradient(45deg, rgba(255, 255, 255, 0.25) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.25) 50%, rgba(255, 255, 255, 0.25) 75%, transparent 75%, transparent);
        background-blend-mode: overlay, overlay, normal;
        background-color: #F7F7F7;
        background-size: 40px 40px, 40px 40px, 80px 80px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        z-index: -1;
        /* Ensure it's behind all content */
    }

    /* header btn and a */
    .d-flex {
        font-size: 12px;
    }

    .d-flex .btn {
        font-size: 10px;
    }

    .d-flex .badge {
        font-size: 15px;
    }

    .d-flex h3,
    .d-flex h4 {
        font-size: 15px;
    }

    .btn {
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    /* animasi for image */
    /* @keyframes flipHorizontal {
        0% {
            transform: rotateY(0deg);
        }

        100% {
            transform: rotateY(360deg);
        }
    }

    img.logo-animated {
        animation: flipHorizontal 4s linear infinite;
    } */


    /* Custom table styles */
    .custom-table {
        width: 100%;
        border: 2px solid black;
        border-collapse: collapse;
        font-size: 0.9em;
        /* Adjust font size for better fit */
    }

    .custom-table th,
    .custom-table td {
        padding: 2px 2px;
        /* Reduced padding for tighter rows */
        border: 2px solid black;
        font-weight: bold;
        background-color: #FFFFFF;
    }

    .table-responsive {
        margin-bottom: 10px;
        /* Reduce space between tables */
    }

    /* coontainer */
    .custom-container {
        margin-left: 15px;
        /* Adjust the left margin as needed */
        margin-right: 15px;
        /* Adjust the right margin as needed */
    }
    .btn {
        margin-right: 10px;
    }

    .badges h4 {
        margin-right: 12px;
    }

    /* carousel */
    .carousel-inner {
        border-radius: 8px;
        overflow: hidden;
    }

    .carousel-item {
        transition: opacity 0.6s ease-in-out;
    }

    .carousel-item.active {
        opacity: 1;
    }

    /* modal */
    .modal-content {
        border-radius: 8px;
    }

    .modal-header {
        border-bottom: none;
    }

    .modal-body {
        padding: 20px;
    }

    /* animasi carousel */
    .carousel-item {
        transition: transform 1s ease, opacity 1s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .fade-in {
        animation: fadeIn 1s ease-in-out;
    }

    /* drop down */
    /* Dropdown open on hover */
    .dropdown:hover .dropdown-menu {
        display: block;
        margin-top: 0;
        /* Removes margin so it aligns with button */
    }

    /* Remove caret from button */
    .btn-primary::after {
        content: none;
    }

    /* Add transition effect for smoother dropdown appearance */
    .dropdown-menu {
        transition: opacity 0.3s ease;
        opacity: 0;
        display: block;
        position: absolute;
        transform: translateY(10px);
        /* Small initial offset */
        visibility: hidden;
        /* Hide by default */
        border-radius: 0.5rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        /* Box shadow for dropdown */
    }

    .dropdown:hover .dropdown-menu {
        opacity: 1;
        visibility: visible;
        /* Show on hover */
        transform: translateY(0);
        /* Align with the button */
    }

    /* Adjusted button style to be smaller */
    .btn-primary {
        background-color: #007bff;
        /* Change color if needed */
        border: none;
        box-shadow: 0 2px 5px rgba(0, 123, 255, 0.4);
        padding: 0.3rem 0.6rem;
        /* Reduced padding for smaller size */
        font-size: 0.875rem;
        /* Smaller font size */
    }

    .btn-primary:hover {
        background-color: #0056b3;
        /* Darken color on hover */
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.4);
    }

    /* Dropdown item styles */
    .dropdown-item {
        position: relative;
        overflow: hidden;
        transition: color 0.3s ease;
        padding: 0.5rem 1rem;
        /* Adjusted padding for items */
    }

    .dropdown-item:before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 0.2rem;
        background-color: #007bff;
        transform: scaleX(0);
        transform-origin: bottom right;
        transition: transform 0.3s ease;
    }

    .dropdown-item:hover:before,
    .dropdown-item:focus:before {
        transform: scaleX(1);
        transform-origin: bottom left;
    }

    .dropdown-item:hover,
    .dropdown-item:focus {
        color: #007bff;
    }

    .dropdown-item.active {
        background-color: #0056b3;
        color: #fff;
    }

    /* Optional: Adjust icon size for better visual appearance */
    .fa {
        font-size: 1rem;
        /* Smaller icon size */
    }
</style>

<body class="custom_page">
    <div class="custom-container mt-2">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <!-- Dropdown Menu -->
            <!-- Dropdown Container -->
            <div class="dropdown d-flex align-items-center">
                <!-- Custom Button for Dropdown -->
                <button class="btn btn-primary mr-2" type="button" id="dropdownMenuButton">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Dropdown Menu -->
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('tms.warehouse.monitoring_minmax') }}">Home</a>
                    <a class="dropdown-item" href="{{ route('tms.warehouse.index_chuter1_All') }}">All</a>
                    <a class="dropdown-item" href="javascript:void(0)" onclick="refreshPage()">Refresh</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" title="Itemcode" id="byItemcode">By Itemcode</a>
                    <a class="dropdown-item" href="#" title="Chuter" id="byChuter">By Chuter</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="javascript:void(0);" id="prevButton" title="Previous">
                        <i class="fa fa-arrow-circle-o-left fa-lg"></i> Previous
                    </a>
                    <a class="dropdown-item" href="javascript:void(0);" id="nextButton" title="Next">
                        <i class="fa fa-arrow-circle-o-right fa-lg"></i> Next
                    </a>
                </div>
            </div>

            <!-- Logo -->
            <div class="mt-1">
                <img src="{{ asset('images/tch-logo-new.png') }}" alt="Logo" class="logo-animated"
                    style="width: 100px; height: auto;">
            </div>

            <!-- Title and Date -->
            <div class="mt-1 text-center">
                <h3 class="ml-2 mt-0 mb-0" style="font-size: 15px"><b>ABNORMALITY Lantai 1</b></h3>
                <h3 class="ml-2 mt-0 mb-0" id="currentDate"><b></b></h3>
            </div>

            <!-- Status Badges -->
            <div class="badges d-flex">
                <h4><span class="badge bg-danger">KRITIS</span></h4>
                <h4><span class="badge bg-warning">OVER</span></h4>
                <h4><span class="badge bg-success">OK</span></h4>
            </div>
        </div>

        <div id="cardCarousel" class="carousel slide" data-ride="carousel" data-interval="60000">
            <div class="carousel-inner">
                @foreach ($carouselDataitemcode as $index => $carouselItem)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <div class="row">
                            @foreach ($carouselItem as $table)
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="table-responsive">
                                        <table class="table mt-1 custom-table">
                                            <thead class="">
                                                <tr>
                                                    <th class="text-center" rowspan="2"
                                                        style="vertical-align: middle;">ITEMCODE</th>
                                                    <th class="text-center" rowspan="2"
                                                        style="vertical-align: middle;">CHUTER</th>
                                                    <th rowspan="2" style="vertical-align: middle;">PARTNO -
                                                        PARTNAME
                                                    </th>
                                                    <th class="text-center">MIN</th>
                                                    <th class="text-center" rowspan="2"
                                                        style="vertical-align: middle;">STOCK CHUTER</th>
                                                    <th class="text-center" rowspan="2"
                                                        style="vertical-align: middle;">OVER FLOW</th>
                                                    <th class="text-center" rowspan="2"
                                                        style="vertical-align: middle;">TOTAL FG</th>
                                                    <th class="text-center" rowspan="2"
                                                        style="vertical-align: middle;">STAGING</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center">MAX</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($table['items'] as $item)
                                                    <tr>
                                                        <td rowspan="2" class="text-center"
                                                            style="vertical-align: middle;">{{ $item['itemcode'] }}
                                                        </td>
                                                        <td rowspan="2" class="text-center"
                                                            style="vertical-align: middle;">
                                                            {{ $item['chutter_address'] }}</td>
                                                        <td rowspan="2" style="vertical-align: middle;">
                                                            {{ $item['part_number'] }} - {{ $item['part_name'] }}</td>
                                                        <td style="text-align: center; vertical-align: middle;">
                                                            {{ $item['min'] }}</td>
                                                        <td rowspan="2" class="text-center"
                                                            style="background-color:@if ($item['max'] < $item['balance']) rgba(255, 196, 0, 0.801);@elseif($item['min'] > $item['balance']) rgba(255, 0, 0, 0.801); @endif; vertical-align: middle;">
                                                            {{ $item['balance'] }}</td>
                                                        <td rowspan="2" class="text-center"
                                                            style="background-color: rgba(0, 204, 255, 0.801); vertical-align: middle;">
                                                            {{ $item['jumlah_qty'] }}</td>
                                                        <td rowspan="2" class="text-center"
                                                            style="background-color: @if ($item['max'] < $item['total']) rgba(255, 196, 0, 0.801); @elseif($item['min'] > $item['total']) rgba(255, 0, 0, 0.801); @endif; vertical-align: middle;">
                                                            {{ $item['total'] }}</td>
                                                        <td rowspan="2" class="text-center"
                                                            style=" vertical-align: middle;">0
                                                            {{-- {{ $item['total_staging'] }}</td> --}}
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: center; vertical-align: middle;">
                                                            {{ $item['max'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div id="cardCarousel2" class="carousel slide" data-ride="carousel" data-interval="60000"
            style="display: none;">
            <div class="carousel-inner">
                @foreach ($carouselDatachuter as $index => $carouselItem)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <div class="row">
                            @foreach ($carouselItem as $table)
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="table-responsive">
                                        <table class="table mt-1 custom-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="vertical-align: middle;">CHUTER
                                                    </th>
                                                    <th class="text-center" style="vertical-align: middle;">ITEMCODE
                                                    </th>
                                                    <th class="text-center" style="vertical-align: middle;">STOCK</th>
                                                    <th class="text-center" style="vertical-align: middle;">OVER FLOW
                                                    </th>
                                                    <th class="text-center" style="vertical-align: middle;">TOTAL</th>
                                                    <th class="text-center" style="vertical-align: middle;">STAGING
                                                    </th>
                                                </tr>

                                            </thead>
                                            <tbody>
                                                @foreach ($table['items'] as $item)
                                                    <tr>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            {{ $item['chutter_address'] }}</td>
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            {{ $item['itemcode'] }}
                                                        </td>

                                                        <td class="text-center"
                                                            style="background-color: @if ($item['max'] < $item['balance']) rgba(255, 196, 0, 0.801); @elseif($item['min'] > $item['balance']) rgba(255, 0, 0, 0.801); @endif; vertical-align: middle;">
                                                            {{ $item['balance'] }}</td>
                                                        <td class="text-center"
                                                            style="background-color: rgba(0, 204, 255, 0.801); vertical-align: middle;">
                                                            {{ $item['jumlah_qty'] }}</td>
                                                        <td class="text-center"
                                                            style="background-color: @if ($item['max'] < $item['total']) rgba(255, 196, 0, 0.801); @elseif($item['min'] > $item['total']) rgba(255, 0, 0, 0.801); @endif; vertical-align: middle;">
                                                            {{ $item['total'] }}</td>
                                                        <td class="text-center" style="vertical-align: middle;">0</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        {{-- <div class="modal fade" id="popup-modal" tabindex="-1" role="dialog" aria-labelledby="popup-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="popup-modal-label"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="popup-text" class="popup-text"></div>
            </div>
        </div>
    </div>
</div> --}}
        {{-- <a class="carousel-control-prev" href="#cardCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>

    <!-- Tombol Next -->
    <a class="carousel-control-next" href="#cardCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a> --}}

        <script src="{{ asset('vendor/srtdash/js/popper.min.js') }}"></script>
        <script src="{{ asset('vendor/srtdash/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('vendor/srtdash/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('vendor/srtdash/js/metisMenu.min.js') }}"></script>
        <script src="{{ asset('vendor/srtdash/js/jquery.slimscroll.min.js') }}"></script>
        <script src="{{ asset('vendor/srtdash/js/jquery.slicknav.min.js') }}"></script>
        <script src="{{ asset('vendor/srtdash/js/line-chart.js') }}"></script>
        <script src="{{ asset('vendor/srtdash/js/pie-chart.js') }}"></script>
        <script src="{{ asset('vendor/srtdash/js/plugins.js') }}"></script>
        <script src="{{ asset('vendor/srtdash/js/scripts.js') }}"></script>
        <script src="{{ asset('vendor/select2/js/select2.full.min.js') }}"></script>
        @include('sweetalert::alert')
        <!-- Custom js -->
        <script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
        {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> --}}
        <script src="{{ asset('js/custom-general.js') }}"></script>
        @stack('js')

        @yield('script')
        <script>
            function refreshPage() {
                // Menggunakan location.reload() untuk me-refresh halaman
                location.reload();
            }
            setTimeout(function() {
                location.reload();
            }, 300000); // 300000 milidetik = 5 menit

            window.onload = function() {
                document.body.classList.add('fade-in');
            };

            function formatDate(date) {
                const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                    'October', 'November', 'December'
                ];

                const day = days[date.getDay()];
                const dayOfMonth = date.getDate().toString().padStart(2, '0'); // Pad with leading zero if needed
                const month = months[date.getMonth()];
                const year = date.getFullYear();

                return `${day}, ${dayOfMonth} ${month} ${year}`;
            }

            function updateCurrentDateAndDay() {
                const currentDateElement = $('#currentDate');
                const currentDate = new Date();
                const formattedDate = formatDate(currentDate);

                currentDateElement.text(formattedDate);
            }

            // Update the current date and day when the page is loaded
            $(document).ready(function() {
                updateCurrentDateAndDay();
            });

            // change font size base on amount tbl
            document.addEventListener('DOMContentLoaded', function() {
                var customTables = document.querySelectorAll('.custom-table');


                customTables.forEach(function(table) {
                    // Hitung jumlah tabel
                    var tableCount = customTables.length;

                    // Sesuaikan ukuran font berdasarkan jumlah tabel
                    var fontSize =
                        '12px'; // Ukuran default jika tidak ada tabel atau tidak memenuhi kondisi berikut
                    if (tableCount === 2) {
                        fontSize = '14px'; // Misalnya, untuk 2 tabel ukuran font lebih besar
                    } else if (tableCount === 3 || tableCount === 4 || tableCount === 5) {
                        fontSize = '10px'; // Untuk 3, 4, atau 5 tabel, ukuran font sedang
                    }

                    // Terapkan gaya untuk th dan td di dalam tabel
                    var ths = table.querySelectorAll('th');
                    var tds = table.querySelectorAll('td');

                    ths.forEach(function(th) {
                        th.style.fontSize = fontSize;

                    });

                    tds.forEach(function(td) {
                        td.style.fontSize = fontSize;
                    });
                });
            });

            // pop up modal for detail chuter
            document.addEventListener('DOMContentLoaded', function() {
                $('#popup-modal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggergba(255, 0, 0, 0.801); the modal
                    var tableContent = button.closest('div[data-popup-content]')
                        .html(); // Extract info from data-* attributes

                    var modal = $(this);
                    modal.find('.modal-title').text('Details for ' + button.data('popup-content'));
                    modal.find('.modal-body #popup-text').html(tableContent);

                });
            });

            $(document).ready(function() {
                // Default: Show first carousel, hide second
                $('#cardCarousel').show();
                $('#cardCarousel2').hide();

                // Navigation buttons
                $('#prevButton').on('click', function() {
                    if ($('#cardCarousel').is(':visible')) {
                        $('#cardCarousel').carousel('prev');
                    } else {
                        $('#cardCarousel2').carousel('prev');
                    }
                });

                $('#nextButton').on('click', function() {
                    if ($('#cardCarousel').is(':visible')) {
                        $('#cardCarousel').carousel('next');
                    } else {
                        $('#cardCarousel2').carousel('next');
                    }
                });

                // Switch to second carousel when 'By Chuter' is clicked
                $('#byChuter').on('click', function(e) {
                    e.preventDefault();
                    $('#cardCarousel').hide(); // Hide first carousel
                    $('#cardCarousel2').show(); // Show second carousel
                });

                // Switch to first carousel when 'By Itemcode' is clicked
                $('#byItemcode').on('click', function(e) {
                    e.preventDefault();
                    $('#cardCarousel2').hide(); // Hide second carousel
                    $('#cardCarousel').show(); // Show first carousel
                });
            });
        </script>
</body>

</html>
