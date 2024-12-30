<div class="modal fade bd-example-modal-xl createMinMax" data-backdrop="static" data-keyboard="false" style="z-index: 1041" tabindex="-1" id="createMinMax" role="dialog">
    <div class="modal-dialog modal-xl" style="max-width: 1000px; width: 100%">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark">
                <h4 class="modal-title">Form Master Min Max --Create--</h4>
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body">
                        <form id="form-masterMinMax" method="post" action="javascript:void(0)">
                            @csrf
                            @method('POST')
                            @include('tms.warehouse.min-max.modal.create-min-max.form')
                        </form>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <div>
                            {{-- <button type="button" class="btn btn-success btn-flat btn-lg" >
                                <i class="fa fa-file-excel-o"></i>
                            </button> --}}
                            <button type="button" data-toggle="tooltip" title="Import" id="showImport" data-placement="top" class="btn btn-success btn-lg-6">
                                <i class="fa fa-file-excel-o"></i>
                                Import Excel
                            </button>
                        </div>
                        <div>
                            <button type="button" onclick="clear_input()" data-toggle="tooltip" class="btn text-white" data-dismiss="modal" style="background: rgb(16, 130, 175)">
                                <i class="ti-close"></i>
                                &nbsp;Close
                            </button>
                            <button type="button" data-toggle="tooltip" data-placement="top" class="btn btn-info btn-lg-6 addMasterminmax">
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
        $(document).on('click', '#addModalminmax', function(e) {
            e.preventDefault();
            $('#createMinMax').modal('show');
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
            url: "{{ route('tms.warehouse.master_min_max.validasi_chutter') }}",
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
                    $('#chutter_create').focus();
                    $('#chutter_create').val("");
                    swal.fire({
                        icon: 'error',
                        // timer: 2000,
                        title: 'Error',
                        text: 'Code Chutter Address Already Exist',
                    });

                }

            },

        });
    }

    // Call the function when the document is ready
    $(document).ready(function() {
        var ekanbanparamButton = $('#ekanbanparamButton');

        ekanbanparamButton.click(function() {
            initDataTableAndHandleButtonClick();
        });
    });

    // Function to initialize DataTable and handle button click
    function initDataTableAndHandleButtonClick() {
        $('#ItemModalAdd1').modal('show');
        var lookUpdataItemcodecreate = $('#lookUpdataItemcodecreate').DataTable();
        lookUpdataItemcodecreate.destroy();
        var lookUpdataItemcodecreate = $('#lookUpdataItemcodecreate').DataTable({
            "pagingType": "numbers",
            ajax: {
                url: "{{ route('tms.warehouse.getItemcodeekanbanparam') }}",
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
                        $(document).ready(function() {

                            // validasi itemcode
                            document.getElementById("item_code_check").value =
                                value["item_code"];
                            var itemCodecheck = $('#item_code_check').val();
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $(
                                        'meta[name="csrf-token"]').attr(
                                        'content')
                                }
                            });
                            $.ajax({
                                url: "{{ route('tms.warehouse.master_min_max.validasi_itemcode') }}",
                                type: "get",
                                data: {
                                    itemCodecheck: itemCodecheck
                                }, // Data yang akan dikirim ke server
                                dataType: 'json',
                                success: function(data) {
                                    // console.log(data);
                                    if (JSON.stringify(data) !== '{}') {
                                        // if (data != "") {
                                        swal.fire({
                                            icon: 'error',
                                            // timer: 2000,
                                            title: 'Error',
                                            text: 'Itemcode Already Exist',
                                        });

                                    } else {
                                        document.getElementById(
                                                "itemcode_create")
                                            .value =
                                            value["item_code"];
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
                                    }
                                }
                            });

                        });

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
    function validasimultiplemin(input) {
        var itemCode = $('#itemcode_create').val();
        // console.log(itemcode);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('tms.warehouse.master_min_max.validasi_multiple') }}",
            type: "get",
            data: {
                itemCode: itemCode
            }, // Data yang akan dikirim ke server
            dataType: 'json',
            success: function(data) {
                // console.log(data);
                $('#qty_kanban_min').val(data.qty_kanban);
                // var min = $('#min_create').val();
                // var qty_ekanban = $('#qty_kanban').val();
                var min = parseInt($('#min_create')
                    .val()); // Konversi nilai ke integer jika belum
                var qty_ekanban = parseInt($('#qty_kanban_min')
                    .val()); // Konversi nilai ke integer jika belum
                // console.log(min);
                // console.log(qty_ekanban);
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('tms.warehouse.master_min_max.validasi_multiple') }}",
            type: "get",
            data: {
                itemCode: itemCode
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
        $('#stock_type_create').val("");
        $('#plant_create').val("");
        $('#chutter_create').focus();
    }

    // add data for master min max
    $('.modal-footer').on('click', '.addMasterminmax', function() {
        var chutter_create = $('#chutter_create').val();
        var itemcode_create = $('#itemcode_create').val();
        var partno_create = $('#partno_create').val();
        var partname_create = $('#partname_create').val();
        var part_type_create = $('#part_type_create').val();
        var min_create = $('#min_create').val();
        var max_create = $('#max_create').val();
        var stock_type_create = $('#stock_type_create').val();
        // alert(min_create);
        var condtion = !chutter_create || !itemcode_create || !partno_create || !
            partname_create || !
            part_type_create || !min_create || !max_create||!stock_type_create
        if (condtion) {
            Swal.fire({
                icon: 'warning',
                // timer: 2000,
                title: 'The input form cannot have be empty!'
            })
        } else {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $(
                        'meta[name="csrf-token"]').attr(
                        'content')
                }
            });
            $.ajax({
                url: "{{ route('tms.warehouse.master_min_max.validasi_period') }}",
                type: "get",
                data: {
                    itemcode_create: itemcode_create
                },
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    if (data === "") {
                        // alert('add');
                        // validasi multiple max

                        var itemCode = $('#itemcode_create').val();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('tms.warehouse.master_min_max.validasi_multiple') }}",
                            type: "get",
                            data: {
                                itemCode: itemCode
                            },
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
                                            'X-CSRF-TOKEN': $(
                                                'meta[name="csrf-token"]'
                                            ).attr(
                                                'content')
                                        }
                                    });
                                    $.ajax({
                                        url: "{{ route('tms.warehouse.master_min_max.store_masterminmax') }}",
                                        type: "POST",
                                        data: $('#form-masterMinMax')
                                            .serialize(),
                                        dataType: 'json',
                                        success: function(data) {
                                            // console.log(data);
                                            // alert('sukses');
                                            clear_input();
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Successfully!',
                                                text: 'Data added successfully ',
                                                // timer: 3000
                                            }).then(function() {
                                                $('#createMinMax')
                                                    .modal(
                                                        'hide');
                                                $('#masterMinMax-datatables')
                                                    .DataTable()
                                                    .ajax
                                                    .reload();
                                                // location.reload();
                                            });

                                            // Swal.fire({
                                            //     icon: 'success',
                                            //     timer: 2500,
                                            //     title: 'Successfully!',
                                            //     text: 'Data berhasil ditambahkan',

                                            // });
                                            // $('#createModalMaster').modal('hide');
                                            // $('#masterdies-datatables').DataTable().ajax.reload();
                                        }
                                    });
                                    // $('.addMasterdies').html('Saving...')

                                    // }
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

                    } else {
                        clear_input();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'Period Tidak Boleh Sama '
                        });

                    }

                }
            });
        }
    })
</script>
