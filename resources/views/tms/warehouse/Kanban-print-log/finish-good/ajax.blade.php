<script>
    $(document).ready(function() {
        // select kanbanNo
        $('#kanbanNofg').select2({
            placeholder: "--CHOOSE--",
            allowClear: true,
            width: '100%'
        });

        $('#itemCodefg').select2({
            placeholder: "--CHOOSE--",
            allowClear: true,
            width: '100%'
        });

        $('#createdByfg').select2({
            placeholder: "--CHOOSE--",
            allowClear: true,
            width: '100%'
        });

        // Tambahkan event listener pada tombol
        $('#btnFg').on('click', function() {
            var tblWip = $('#kanbanWipprintlog-datatables').DataTable();
            // Mengubah tampilan kartu
            $('#cardWip').hide();
            $('#cardFg').show();

            // Mengosongkan nilai input
            $('#kanbanNowip').val('');
            $('#fromDatewip').val('');
            $('#toDatewip').val('');
            $('#createdBywip').val('');
            $('#statusDocsenwip').val('');
            // Reload DataTable
            tblWip.ajax.reload();
        });
        //get data from datatables fg
        var table = $('#kanbanFgprintlog-datatables').DataTable({
            processing: true,
            serverSide: true,
            deferRender: true,
            responsive: true,
            ajax: {
                url: "{{ route('tms.warehouse.get_kanban_fg_print_log_datatables') }}",
                // Tambahkan fungsi untuk mengubah data sesuai dengan nilai filter dari elemen HTML
                data: function(d) {
                    d.kanbanNofg = $('#kanbanNofg').val();
                    d.itemCodefg = $('#itemCodefg').val();
                    d.fromDatefg = $('#fromDatefg').val();
                    d.toDatefg = $('#toDatefg').val();
                    d.createdByfg = $('#createdByfg').val();
                    d.statusDocsenfg = $('#statusDocsenfg').val();
                }
            },
            searching: false,
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
                    data: 'part_name',
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
                // $('#docNofg, #fromDatefg, #toDatefg').on('change','keypress', function() {
                //     table.draw();
                // });
                $('#kanbanNofg, #fromDatefg, #toDatefg, #statusDocsenfg, #createdByfg, #itemCodefg')
                    .on('change',
                        function() {
                            table.draw();
                        });


            }
        });
        // export ecxel ekanban print fg
        $('#fgprintExportexcel').on('click', function() {
            // Ambil nilai dari input chuter, fromDate, dan toDate
            let kanbanNofg = $('#kanbanNofg').val();
            let itemCodefg = $('#itemCodefg').val();
            let fromDatefg = $('#fromDatefg').val();
            let toDatefg = $('#toDatefg').val();
            let createdByfg = $('#createdByfg').val();
            let statusDocsenfg = $('#statusDocsenfg').val();

            if (fromDatefg === '' || toDatefg === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error...',
                    text: 'Column cannot be empty'
                });
            } else {
                $.ajax({
                    method: 'GET',
                    url: '{{ route('tms.warehouse.kanban_print_log.report_excelfgprint') }}',
                    data: {
                        kanbanNofg: kanbanNofg,
                        itemCodefg: itemCodefg,
                        fromDatefg: fromDatefg,
                        toDatefg: toDatefg,
                        createdByfg: createdByfg,
                        statusDocsenfg: statusDocsenfg
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
                                '{{ route('tms.warehouse.kanban_print_log.report_excelfgprint') }}?kanbanNofg=' +
                                kanbanNofg + '&fromDatefg=' + fromDatefg + '&toDatefg=' +
                                toDatefg + '&createdByfg=' + createdByfg;
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                        // alert('Error occurred while fetching data.');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'Request Error 60 second ',
                        })
                    }
                });
            }
        });




    });
</script>
