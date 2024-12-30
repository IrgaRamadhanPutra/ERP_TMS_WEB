<script>
    $(document).ready(function() {


        $('#chuter').select2({
            placeholder: "Pilih",
            allowClear: true,
            width: '100%'
        });

        // date defult filter chuter

        // Dapatkan tanggal saat ini
        var today = new Date();
        var year = today.getFullYear();
        var month = String(today.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0
        var day = String(today.getDate()).padStart(2, '0');
        var currentDate = year + '-' + month + '-' + day;

        // Atur nilai default pada input tanggal
        $('#fromDate').val(currentDate);
        $('#toDate').val(currentDate);
        // $('#slocNo').select2({
        //     placeholder: "Pilih Sloc",
        //     allowClear: true,
        //     width: '100%'
        // });
        // Variabel flag untuk menandakan status tampilan
        var isFilterShown = false;

        // Ketika tombol #filerChuter diklik
        $("#filerChuter").click(function() {
            $("#masterMinmax").hide();
            $("#filter").show();
            $("#filterMinmax").show();
            $("#backIndexminmax").show();
            $(".card-header-title").html("Min Max &nbsp;&nbsp;&nbsp; -- Filter Chuter --");

            // Cek apakah DataTable sudah diinisialisasi
            if ($.fn.DataTable.isDataTable('#filterMinMax-datatables')) {
                // Jika sudah diinisialisasi, hancurkan dan buat yang baru
                $('#filterMinMax-datatables').DataTable().destroy();
                $('#filterMinMax-datatables').empty(); // Bersihkan tabel jika diperlukan

                // Tambahkan kembali header tabel
                $('#filterMinMax-datatables').html(`
                <thead class="text-center" style="text-transform: uppercase; font-size: 12px;">
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
            <tbody></tbody>`);
            }

            // Inisialisasi DataTable
            let table = $('#filterMinMax-datatables').DataTable({
                processing: true,
                serverSide: true,
                deferRender: true,
                responsive: true,
                ajax: {
                    url: "{{ route('tms.warehouse.master_minmax.getFilter') }}",
                    // Tambahkan fungsi untuk mengubah data sesuai dengan nilai filter dari elemen HTML
                    data: function(d) {
                        d.chuter = $('#chuter').val();
                        d.fromDate = $('#fromDate').val();
                        d.toDate = $('#toDate').val();
                    }
                },
                order: [
                    [0, 'asc']
                ],
                columnDefs: [{
                    searchable: false,
                    targets: [1, 2, 3,
                        5
                    ] // Tambahkan kolom mana yang tidak akan di-searching
                }],
                columns: [{
                        data: 'chutter_address',
                        className: "text-center"
                    },
                    {
                        data: 'kanban_no',
                        className: "text-center"
                    },
                    {
                        data: 'part_no',
                        className: "text-center"
                    },
                    {
                        data: 'seq',
                        className: "text-center"
                    },
                    {
                        data: 'qty',
                        className: "text-center"
                    },
                    {
                        data: 'creation_date',
                        className: "text-center"
                    },
                    {
                        data: 'in_datetime',
                        className: "text-center"
                    },
                    {
                        data: 'out_datetime',
                        className: "text-center"
                    }
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).css("font-size", "13px");
                },
                initComplete: function() {
                    // Add event listener to the date input
                    $('#chuter, #fromDate, #toDate').on('change', function() {
                        table.draw();
                    });
                }
            });
        });
        // button back to master min max
        $("#backIndexminmax").click(function() {
            $("#masterMinmax").show();
            $("#filter, #filterMinmax, #backIndexminmax").hide();
            $(".card-header-title").text("Master Min Max");
        });

        // button filterexpoert excel
        $('#filterExportexcel').on('click', function() {
            // Ambil nilai dari input chuter, fromDate, dan toDate
            let chuter = $('#chuter').val();
            let fromDate = $('#fromDate').val();
            let toDate = $('#toDate').val();

            if (fromDate === '' || toDate === '') {
                // alert('Please select both From Date and To Date.');
                // return; // Berhenti eksekusi jika fromDate atau toDate kosong
                // console.log('eror');
                Swal.fire({
                    icon: 'error',
                    title: 'Error...',
                    text: 'Column cannot be empty'
                });
            } else {
                $.ajax({
                    method: 'GET',
                    url: '{{ route('tms.warehouse.master_minmax.filterChuterexcel') }}',
                    data: {
                        chuter: chuter,
                        fromDate: fromDate,
                        toDate: toDate
                    },
                    success: function(response) {
                        // Handle success response
                        if (response.message && response.message === "No data found") {
                            // Jika tidak ada data ditemukan
                            Swal.fire({
                                icon: 'error',
                                title: 'Error...',
                                text: 'Data Not Found'
                            });
                        } else {
                            // Jika ada data ditemukan, redirect untuk download Excel
                            window.location.href =
                                '{{ route('tms.warehouse.master_minmax.filterChuterexcel') }}?chuter=' +
                                chuter + '&fromDate=' + fromDate + '&toDate=' + toDate;
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                        // alert('Error occurred while fetching data.');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'Data Not Found',
                        })
                    }
                });
            }
        });

        // get data to option chuter address
        $("#chuter").on("input", function() {
            var value = $(this).val().toLowerCase();
            $("#chuterList option").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });


    });
</script>
