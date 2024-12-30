<script>
    $(document).ready(function() {
        // select kanbanNo
        $('#kanbanNowip').select2({
            placeholder: "--CHOOSE--",
            allowClear: true,
            width: '100%'
        });
        // select itemcode
        $('#itemCodewip').select2({
            placeholder: "--CHOOSE--",
            allowClear: true,
            width: '100%'
        });
        // select createdBy
        $('#createdBywip').select2({
            placeholder: "--CHOOSE--",
            allowClear: true,
            width: '100%'
        });
        // Tambahkan event listener pada tombol
        $('#btnWip').on('click', function() {
            var tblKanbanfg = $('#kanbanFgprintlog-datatables').DataTable();
            // Mengubah tampilan kartu
            $('#cardFg').hide();
            $('#cardWip').show();

            // Mengosongkan nilai input
            $('#kanbanNofg').val('');
            $('#fromDatefg').val('');
            $('#toDatefg').val('');
            $('#createdByfg').val('');
            $('#statusDocsenfg').val('');

            // Reload DataTable
            tblKanbanfg.ajax.reload();
        });


        // datatables kanban wip
        var table = $('#kanbanWipprintlog-datatables').DataTable({
            processing: true,
            serverSide: true,
            deferRender: true,
            responsive: true,
            ajax: {
                url: "{{ route('tms.warehouse.get_kanban_wip_print_log_datatables') }}",
                // Tambahkan fungsi untuk mengubah data sesuai dengan nilai filter dari elemen HTML
                data: function(d) {
                    d.kanbanNowip = $('#kanbanNowip').val();
                    d.itemCodewip = $('#itemCodewip').val();
                    d.fromDatewip = $('#fromDatewip').val();
                    d.toDatewip = $('#toDatewip').val();
                    d.statusDocsenwip = $('#statusDocsenwip').val();
                    d.createdBywip = $('#createdBywip').val();
                }
            },
            searching: false,
            // order: [
            //     [0,
            //         'desc'
            //     ] // Set the default sorting order to be in descending order based on 'action_date'
            // ],
            // columnDefs: [{
            //     "searchable": false,
            //     "targets": [4, 5, 6, 7]
            // }],
            // bInfo: false, /* untuk menghilangkan showing entries  */
            responsive: true,

            columns: [{
                    data: 'ekanban_no',
                    className: "text-center",
                },
                {
                    data: 'item_code',
                    className: "text-center",
                },
                {
                    data: 'part_no',
                    className: "text-center",
                },
                {
                    data: 'seq',
                    className: "text-center",
                },
                {
                    data: 'seq_tot',
                    className: "text-center",
                },
                {
                    data: 'kanban_qty',
                    className: "text-center",
                },
                {
                    data: 'kanban_qty_tot',
                    className: "text-center",
                },
                {
                    data: 'created_by',
                    className: "text-center",
                },
                {
                    data: 'creation_date',
                    className: "text-center",
                },
            ],
            createdRow: function(row, data, dataIndex) {
                $(row).css("font-size", "13px");
            },
            initComplete: function() {
                // Add event listener to the date input
                $('#kanbanNowip, #fromDatewip, #toDatewip, #statusDocsenwip, #createdBywip, #itemCodewip').on(
                    'change',
                    function() {
                        table.draw();
                    });
            }
        });




        $('#WipprintExportexcel').on('click', function() {
            // Ambil nilai dari input chuter, fromDate, dan toDate
            let kanbanNowip = $('#kanbanNowip').val();
            let fromDatewip = $('#fromDatewip').val();
            let toDatewip = $('#toDatewip').val();
            let createdBywip = $('#createdBywip').val();
            let statusDocsenwip = $('#statusDocsenwip').val();
            // validasi colum tidak boloeh
            if (fromDatewip === '' || toDatewip === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error...',
                    text: 'Column cannot be empty'
                });
            } else {
                $.ajax({
                    method: 'GET',
                    url: '{{ route('tms.warehouse.kanban_print_log.report_excelwipprint') }}',
                    data: {
                        kanbanNowip: kanbanNowip,
                        fromDatewip: fromDatewip,
                        toDatewip: toDatewip,
                        createdBywip: createdBywip,
                        statusDocsenwip: statusDocsenwip
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
                                '{{ route('tms.warehouse.kanban_print_log.report_excelwipprint') }}?kanbanNowip=' +
                                kanbanNowip + '&fromDatewip=' + fromDatewip +
                                '&toDatewip=' +
                                toDatewip + '&createdBywip=' + createdBywip;
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

            // button untuk wip


            // button untuk kanban fg
            // Tambahkan event listener pada tombol

        });
    });
</script>
