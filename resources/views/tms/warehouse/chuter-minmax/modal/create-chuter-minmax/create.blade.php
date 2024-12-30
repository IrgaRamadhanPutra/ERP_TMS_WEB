<div class="modal fade bd-example-modal-xl createChuterminmax" data-backdrop="static" data-keyboard="false"
    style="z-index: 1041" tabindex="-1" id="createChuterminmax" role="dialog">
    <div class="modal-dialog modal-xl" style="max-width: 1000px; width: 100%">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark">
                <h4 class="modal-title">Form Master Min Max Chuter --Create--</h4>
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body">
                        <form id="form-masterChuterminmax" method="post" action="javascript:void(0)">
                            @csrf
                            @method('POST')
                            {{-- form --}}
                            @include('tms.warehouse.chuter-minmax.modal.create-chuter-minmax.form')
                        </form>
                        <!-- Loading Spinner -->
                        <div id="loadingSpinner" style="display:none;" class="text-center">
                            <i class="fa fa-spinner fa-spin fa-3x"></i>
                            <p>Loading...</p>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <div>
                            <button type="button" data-toggle="tooltip" title="Import" id="showModalimport"
                                data-placement="top" class="btn btn-success btn-lg-6">
                                <i class="fa fa-file-excel-o"></i>
                                Import Excel
                            </button>
                        </div>
                        <div>
                            <button type="button" onclick="clear_input()" data-toggle="tooltip" class="btn text-white"
                                data-dismiss="modal" style="background: rgb(16, 130, 175)">
                                <i class="ti-close"></i>
                                &nbsp;Close
                            </button>
                            <button type="button" data-toggle="tooltip" data-placement="top"
                                class="btn btn-info btn-lg-6 addMasterminmaxchuter">
                                <i class="ti-check"></i>
                                &nbsp;&nbsp; Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // ADD DISPLAY MODAL CREATE
    $(document).ready(function() {
        $(document).on('click', '#addModalminmaxchuter', function(e) {
            e.preventDefault();
            $('#createChuterminmax').modal('show');
            setTimeout(function() {
                $('#chutter_create').focus();
            }, 500);
        });

    });

    /// validasi chutter address
    function validasiChutter() {
        // Mendapatkan nilai dari input dengan ID "chutter_create"
        var chutterValue = $('#chutter_create').val();
        // console.log(chutterValue);
        // Lakukan AJAX request ke server
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('tms.warehouse.master_min_max_chuter.validasi_chutter') }}",
            type: "get",
            data: {
                chutterValue: chutterValue
            }, // Data yang akan dikirim ke server
            dataType: 'json',
            success: function(data) {
                // console.log(data);
                if (Object.keys(data).length === 0) {
                    // alert("masuk");
                } else {
                    // alert("eror");

                    $('#ItemModalAdd1').hide();
                    $('#chutter_create').focus();
                    $('#chutter_create').val("");
                    swal.fire({
                        icon: 'error',
                        // timer: 2000,
                        title: 'Error',
                        text: 'Code Chutter Address Already Exist',
                    });
                    $('#ItemModalAdd1').modal('hide');

                }

            },

        });
    }

    // Call the function when the document is ready
    $(document).ready(function() {
        $('#ekanbanparamButton').click(function(event) {
            event.preventDefault(); // Prevent any default action if needed
            initDataTableAndHandleButtonClick();
        });
    });



    // Function to initialize DataTable and handle button click
    function initDataTableAndHandleButtonClick() {
        var chutter_create = $('#chutter_create').val();
        // console.log(chutter_create)
        // validasi chuter address tidak boleh kosong

        $('#ItemModalAdd1').modal('show');
        var lookUpdataItemcodecreate = $('#lookUpdataItemcodecreate').DataTable();
        lookUpdataItemcodecreate.destroy();
        var lookUpdataItemcodecreate = $('#lookUpdataItemcodecreate').DataTable({
            "pagingType": "numbers",
            ajax: {
                url: "{{ route('tms.warehouse.master_min_max_chuter.getItemcodeekanbanparam') }}",
            },
            serverSide: true,
            deferRender: true,
            responsive: true,
            "bFilter": false,
            "order": [
                [1, 'asc']
            ],
            searching: true,
            columns: [{
                    "data": "item_code"
                },
                {
                    "data": "ekanban_no"
                },
                {
                    "data": "part_no"
                },
                {
                    "data": "part_name"
                },
                {
                    "data": "model"
                },
                {
                    "data": "qty_kanban"
                },
                // {
                //     "data": "customer"
                // },
            ],
            "initComplete": function(settings, json) {
                $('#lookUpdataItemcodecreate tbody').on('dblclick', 'tr', function() {
                    var dataArrItem1 = [];
                    var rowItem1 = $(this);
                    var rowItem1 = lookUpdataItemcodecreate.rows(rowItem1).data();
                    $.each($(rowItem1), function(key, value) {
                        document.getElementById(
                                "itemcode_create")
                            .value =
                            value["item_code"];
                        document.getElementById(
                                "kanban_no_create")
                            .value =
                            value["ekanban_no"];
                        document.getElementById(
                                "partno_create").value =
                            value[
                                "part_no"];
                        document.getElementById(
                                "partname_create")
                            .value =
                            value["part_name"];
                        document.getElementById(
                                "part_type_create")
                            .value =
                            value["model"];
                        document.getElementById(
                                "lot_create").value =
                            value["qty_kanban"];
                        document.getElementById(
                                "cust_code").value =
                            value["customer"];
                        $('#ItemModalAdd1').modal(
                            'hide');
                        $('#min_create').focus();

                    });
                });
            },
        });

    }

    // menentukan inputan min dan max hanya bisa angka
    var minCreateInput = document.getElementById('min_create');

    // Menambahkan event listener untuk mendeteksi perubahan input
    minCreateInput.addEventListener('input', function() {
        // Delete characters other than numbers
        var numericValue = this.value.replace(/\D/g, '');
        this.value = numericValue;
    });
    // menentukan inputan min dan max hanya bisa angka
    var maxCreateInput = document.getElementById('max_create');

    // Menambahkan event listener untuk mendeteksi perubahan input
    maxCreateInput.addEventListener('input', function() {
        // Delete characters other than numbers
        var numericValue = this.value.replace(/\D/g, '');
        this.value = numericValue;
    });

    // valiadsi multiple min for create master min max
    function validasimultiplemin() {
        const fields = [

            {
                id: 'chutter_create',
                name: 'Chuter Address'
            },
            {
                id: 'kanban_no_create',
                name: 'Kanban No '
            },
            {
                id: 'itemcode_create',
                name: 'Itemcode'
            },
            {
                id: 'partno_create',
                name: 'Part Number '
            },
            {
                id: 'partname_create',
                name: 'Part Name'
            },
            {
                id: 'part_type_create',
                name: 'Part Type '
            },
            {
                id: 'lot_create',
                name: 'Lot'
            }
        ];

        for (let field of fields) {
            let value = document.getElementById(field.id).value.trim();
            console.log(value);
            if (!value) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error...',
                    text: `${field.name} cannot be empty!`,
                });
                // Clear the input field
                document.getElementById('min_create').value = '';

                // Set focus to the input field
                document.getElementById('min_create').focus();

                return false;
            }
        }

        // If all fields are filled, you can proceed with your logic
        // Example: alert with all field values
        var itemCode = $('#itemcode_create').val();
        var kanbanNo = $('#kanban_no_create').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('tms.warehouse.master_min_max_chuter.validasi_multiple') }}",
            type: "get",
            data: {
                itemCode: itemCode,
                kanbanNo: kanbanNo
            }, // Data yang akan dikirim ke server
            dataType: 'json',
            success: function(data) {
                console.log(data);
                $('#qty_kanban_min').val(data.qty_kanban);
                // var min = $('#min_create').val();
                // var qty_ekanban = $('#qty_kanban').val();
                var min = parseInt($('#min_create')
                    .val()); // Konversi nilai ke integer jika belum
                var qty_ekanban = parseInt($('#qty_kanban_min')
                    .val()); // Konversi nilai ke integer jika belum
                console.log(min);
                console.log(qty_ekanban);
                if (min % qty_ekanban === 0) {
                    // console.log("Sukses: min adalah kelipatan dari qty_ekanban");

                } else {
                    $('#min_create').focus();
                    $('#min_create').val("");
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Quantity Bukan Kelipatan Lot'
                    });
                }
                // console.log(qty_ekanban);
                // console.log(min);

            },

        });
    }



    // validasi validasimultiplemax for create master min max
    function validasimultiplemax(input) {

        var itemCode = $('#itemcode_create').val();
        var kanbanNo = $('#kanban_no_create').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('tms.warehouse.master_min_max_chuter.validasi_multiple') }}",
            type: "get",
            data: {
                itemCode: itemCode,
                kanbanNo: kanbanNo
            },
            dataType: 'json',
            success: function(data) {
                // console.log(data);
                $('#qty_kanban_max').val(data.qty_kanban);
                /*    $('#cust_code').val(data.customer); */
                // var min = $('#min_create').val();
                // var qty_ekanban = $('#qty_kanban').val();
                var max = parseInt($('#max_create')
                    .val()); // Konversi nilai ke integer jika belum
                var qty_ekanban = parseInt($('#qty_kanban_max')
                    .val()); // Konversi nilai ke integer jika belum
                // console.log(max);
                // console.log(qty_ekanban);
                if (max % qty_ekanban === 0) {
                    // console.log("Sukses: max adalah kelipatan dari qty_ekanban");
                } else {
                    $('#max_create').focus();
                    $('#max_create').val("");
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Quantity Bukan Kelipatan Lot'
                    });
                    // alert('quanty tidak sama dengan lot');
                }
            },

        });


    }

    function clear_input() {
        $('#chutter_create').val("");
        $('#itemcode_create').val("");
        $('#partno_create').val("");
        $('#partname_create').val("");
        $('#part_type_create').val("");
        $('#lot_create').val("");
        $('#min_create').val("");
        $('#max_create').val("");
        $('#kanban_no_create').val("");
        $('#chutter_create').focus();
    }


    // add data for master min max
    $('.modal-footer').on('click', '.addMasterminmaxchuter', function() {
        var chutter_create = $('#chutter_create').val();
        var itemcode_create = $('#itemcode_create').val();
        var kanban_no_create = $('#kanban_no_create').val();
        var partno_create = $('#partno_create').val();
        var partname_create = $('#partname_create').val();
        var part_type_create = $('#part_type_create').val();
        var min_create = $('#min_create').val();
        var max_create = $('#max_create').val();

        // alert(min_create);
        var condtion = !chutter_create || !itemcode_create || !kanban_no_create || !partno_create || !
            partname_create || !part_type_create || !min_create || !max_create
        if (condtion) {
            Swal.fire({
                icon: 'warning',
                // timer: 2000,
                title: 'The input form cannot have be empty!'
            })
        } else {
            // Show loading spinner
            $('#loadingSpinner').show();
            var itemCode = $('#itemcode_create').val();
            var kanbanNo = $('#kanban_no_create').val();
            // console.log(itemcode);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('tms.warehouse.master_min_max_chuter.validasi_multiple') }}",
                type: "get",
                data: {
                    itemCode: itemCode,
                    kanbanNo: kanbanNo
                }, // Data yang akan dikirim ke server
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    // alert('langsung add');
                    $('#qty_kanban_max').val(data.qty_kanban);
                    /*    $('#cust_code').val(data.customer); */
                    // var min = $('#min_create').val();
                    // var qty_ekanban = $('#qty_kanban').val();
                    var max = parseInt($('#max_create')
                        .val()); // Konversi nilai ke integer jika belum
                    var qty_ekanban = parseInt($('#qty_kanban_max')
                        .val()); // Konversi nilai ke integer jika belum
                    // console.log(max);
                    // console.log(qty_ekanban);
                    if (max % qty_ekanban === 0) {
                        // alert('add entry');
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            url: "{{ route('tms.warehouse.master_min_max_chuter.store_masterminmax_chuter') }}",
                            type: "POST",
                            data: $('#form-masterChuterminmax').serialize(),
                            dataType: 'json',
                            success: function(data) {
                                // On success, show success alert and perform other actions
                                $('#loadingSpinner').hide();
                                clear_input();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Successfully!',
                                    text: 'Data added successfully ',
                                }).then(function() {
                                    $('#createChuterminmax').modal('hide');
                                    $('#masterMinMaxchuter-datatables')
                                        .DataTable().ajax.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                // On error, show error alert
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Failed to add data: ' + xhr
                                        .responseJSON.message,
                                });
                            }
                        });

                        // $('.addMasterdies').html('Saving...')

                        // }
                        // console.log("Sukses: max adalah kelipatan dari qty_ekanban");
                    } else {
                        $('#loadingSpinner').hide();
                        $('#max_create').focus();
                        $('#max_create').val("");
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'Quantity Bukan Kelipatan Lot'
                        });
                        // alert('quanty tidak sama dengan lot');
                    }

                },

            });

        }
    })
</script>
