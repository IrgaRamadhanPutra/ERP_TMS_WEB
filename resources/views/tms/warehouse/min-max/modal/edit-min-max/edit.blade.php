<div class="modal fade bd-example-modal-xl editMinmax" data-backdrop="static" data-keyboard="false" style="z-index: 1041"
    tabindex="-1" id="editMinmax" role="dialog">
    <div class="modal-dialog modal-xl" style="max-width: 1000px; width: 100%">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark">
                <h4 class="modal-title">Form Master Min Max --Edit--</h4>

                {{-- <button type="button" class="close" data-dismiss="modal" onclick="clear_value_edit_page()"><span>&times;</span></button> --}}
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body edit-modal">
                        {{-- <div class="alert alert-danger print-error-msg" role="alert" style="display: none">
                            <ul></ul>
                        </div> --}}
                        <form id="form-masterMinMaxedit" method="post" action="javascript:void(0)">
                            @csrf
                            @method('POST')
                            <input class="" type="hidden" name="id_minmax_edit" id="id_minmax_edit">
                            <input class="" type="hidden" name="chutter_address_database"
                                id="chutter_address_database">
                            @include('tms.warehouse.min-max.modal.edit-min-max.form')
                        </form>
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" data-toggle="tooltip"  data-placement="top" title="Add Item" class="btn btn-info" id="addRow"> Add Item</button> --}}
                        <button type="button" onclick="clear_input_edit()" data-toggle="tooltip" class="btn text-white"
                            data-dismiss="modal" style="background: rgb(16, 130, 175)">
                            <i class="ti-close"></i>
                            &nbsp;Close
                        </button>
                        <button type="button" data-toggle="tooltip" data-placement="top"
                            class="btn btn-info btn-lg-6 updateMasterminmax">
                            <i class="ti-check"></i>
                            &nbsp;&nbsp; Save
                        </button> {{-- @php $counter @endphp --}} {{-- <input type="hidden" id="jml_row" name="jml_row" value=""> --}}
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
    // ADD DISPLAY MODAL EDIT
    $(document).on('click', '.edit', function(e) {
        e.preventDefault();
        var id = $(this).attr('row-id');
        // alert(id);
        var chutter_address_database = $(this).attr('data-id')
        // alert(chutter_address);
        $('#id_minmax_edit').val(id);
        $('#chutter_address_database').val(chutter_address_database);
        $('#editMinmax').modal('show');

        // Menunda pengaturan fokus sampai modal sepenuhnya ditampilkan
        setTimeout(function() {
            $('#chutter_edit').focus();
        }, 500); // Ganti 500 dengan waktu penundaan yang sesuai jika diperlukan
        Editdata(id);
    });

    // funtion clear inputan
    function clear_input_edit() {
        $('#chutter_edit').val("");
        $('#itemcode_edit').val("");
        $('#partno_edit').val("");
        $('#partname_edit').val("");
        $('#part_type_edit').val("");
        $('#lot_edit').val("");
        $('#min_edit').val("");
        $('#max_edit').val("");
    }


    // edit data view
    function Editdata(id) {
        var route = "{{ route('tms.warehouse.master_minmax_edit', ':id') }}";
        route = route.replace(':id', id);
        $.ajax({
            url: route,
            method: 'get',
            dataType: 'json',
            success: function(data) {
                // console.log(data);
                $('#chutter_edit').val(data['getEdit'][0].chutter_address);
                $('#itemcode_edit').val(data['getEdit'][0].itemcode);
                $('#partno_edit').val(data['getEdit'][0].part_number);
                $('#partname_edit').val(data['getEdit'][0].part_name);
                $('#part_type_edit').val(data['getEdit'][0].part_type);
                $('#lot_edit').val(data['getEdit'][0].qty_kanban);
                $('#stock_type_edit').val(data['getEdit'][0].stock_type);
                $('#min_edit').val(data['getEdit'][0].min);
                $('#max_edit').val(data['getEdit'][0].max);
                $('#plant_edit').val(data['getEdit'][0].plant);
                $('#stock_edit').val(data['totalQty']);
            }
        })
    }


    var minCreateInput = document.getElementById('min_edit');

    // Menambahkan event listener untuk mendeteksi perubahan input
    minCreateInput.addEventListener('input', function() {
        // Menghapus karakter selain angka
        var numericValue = this.value.replace(/\D/g, '');

        // Memasukkan kembali nilai yang telah difilter
        this.value = numericValue;
    });

    // menentukan inputan min dan max hanya bisa angka
    var maxCreateInput = document.getElementById('max_edit');

    // Menambahkan event listener untuk mendeteksi perubahan input
    maxCreateInput.addEventListener('input', function() {
        // Menghapus karakter selain angka
        var numericValue = this.value.replace(/\D/g, '');

        // Memasukkan kembali nilai yang telah difilter
        this.value = numericValue;
    });
    // validasi multiple  min
    function validasiMultipleminedit(input) {
        var itemCode = $('#itemcode_edit').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('tms.warehouse.master_min_max.validasi_multiple_edit') }}",
            type: "get",
            data: {
                itemCode: itemCode
            }, // Data yang akan dikirim ke server
            dataType: 'json',
            success: function(data) {
                // console.log(data);
                $('#qty_kanban_min_edit').val(data.qty_kanban);
                // var min = $('#min_edit').val();
                // var qty_ekanban = $('#qty_kanban').val();
                var min = parseInt($('#min_edit').val()); // Konversi nilai ke integer jika belum
                var qty_ekanban = parseInt($('#qty_kanban_min_edit')
                    .val()); // Konversi nilai ke integer jika belum
                // console.log(min);
                // console.log(qty_ekanban);
                if (min % qty_ekanban === 0) {
                    // console.log("Sukses: min adalah kelipatan dari qty_ekanban");
                } else {
                    $('#min_edit').focus();
                    $('#min_edit').val("");
                    // console.log("Error: min bukan kelipatan dari qty_ekanban");
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

    // validasi multiple max
    function validasimultiplemaxedit(input) {
        var itemCode = $('#itemcode_edit').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('tms.warehouse.master_min_max.validasi_multiple_edit') }}",
            type: "get",
            data: {
                itemCode: itemCode
            }, // Data yang akan dikirim ke server
            dataType: 'json',
            success: function(data) {
                // console.log(data);
                $('#qty_kanban_max_edit').val(data.qty_kanban);
                // var min = $('#min_edit').val();
                // var qty_ekanban = $('#qty_kanban').val();
                var min = parseInt($('#max_edit').val()); // Konversi nilai ke integer jika belum
                var qty_ekanban = parseInt($('#qty_kanban_max_edit')
                    .val()); // Konversi nilai ke integer jika belum
                // console.log(min);
                // console.log(qty_ekanban);
                if (min % qty_ekanban === 0) {
                    // console.log("Sukses: min adalah kelipatan dari qty_ekanban");
                } else {
                    $('#max_edit').focus();
                    $('#max_edit').val("");
                    // console.log("Error: min bukan kelipatan dari qty_ekanban");
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

    // update master data min max

    $('.modal-footer').on('click', '.updateMasterminmax', function() {
        var chutter_edit = $('#chutter_edit').val();
        var min_edit = $('#min_edit').val();
        var max_edit = $('#max_edit').val();
        var condition = !chutter_edit || !min_edit || !max_edit;

        if (condition) {
            Swal.fire({
                icon: 'warning',
                // timer: 2000,
                title: 'Perhatikan Inputan anda, Form tidak boleh ada yang kosong!'
            });
            return false; // Form tidak valid
        } else {
            // var id_minmax = $('#id_minmax_edit').val();
            var chutter_edit = $('#chutter_edit').val();
            var itemcode_edit = $('#itemcode_edit').val();

            // console.log(chutter_edit)
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('tms.warehouse.master_min_max.validasi_chutter_edit1') }}",
                type: "get",
                data: {
                    chutter_edit: chutter_edit,
                    itemcode_edit: itemcode_edit
                }, // Data yang akan dikirim ke server
                dataType: 'json',
                success: function(response) {
                    if (response === "-") {
                        // If response is "-", directly edit
                         // Continue with edit operation
                        //  chuter belum di gunakan langusng validasi max
                         validasiMultipleMaxEdit();
                    } else if (response.success) {
                        // Parse the response data
                        var data = response.data;
                        var chutterAddress = data.chutter_address;
                        var id = data.id;

                        // Inputan lama
                        var chutter_edit_tabel = $('#chutter_address_database').val();
                        var id_minmax_edit_tabel = $('#id_minmax_edit').val();

                        if (chutterAddress == chutter_edit_tabel && id == id_minmax_edit_tabel) {
                            // Continue with edit operation
                            validasiMultipleMaxEdit();
                        } else {
                            // Display 'data beda' message if condition not met
                            Swal.fire({
                                icon: 'error',
                                title: 'Error...',
                                text: 'Chutter already exists.'
                            });
                        }
                    }
                },
                error: function(xhr, status, error) {
                    // Handle the error case
                    Swal.fire({
                        icon: 'error',
                        title: 'Ajax Error',
                        text: 'An error occurred while processing your request.'
                    });
                }
            });

            function validasiMultipleMaxEdit() {
                var itemCode = $('#itemcode_edit').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('tms.warehouse.master_min_max.validasi_multiple_edit') }}",
                    type: "get",
                    data: {
                        itemCode: itemCode
                    }, // Data yang akan dikirim ke server
                    dataType: 'json',
                    success: function(data) {
                        $('#qty_kanban_max_edit').val(data.qty_kanban);

                        // Konversi nilai ke integer jika belum
                        var min = parseInt($('#max_edit').val());
                        var qty_ekanban = parseInt($('#qty_kanban_max_edit').val());

                        if (min % qty_ekanban === 0) {
                            // min is a multiple of qty_ekanban
                            validateForm();
                        } else {
                            $('#max_edit').focus();
                            $('#max_edit').val("");
                            Swal.fire({
                                icon: 'error',
                                title: 'Error...',
                                text: 'Quantity Bukan Kelipatan Lot'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle the error case
                        Swal.fire({
                            icon: 'error',
                            title: 'Ajax Error',
                            text: 'An error occurred while processing your request.'
                        });
                    }
                });
            }


        }

        // VALIDASI FORM FOR UPDATE MASTER DATA MIN MAX
        function validateForm() {

            // alert('lanutkan untuk update');
            var id = document.getElementById('id_minmax_edit').value;
            var route = "{{ route('tms.warehouse.masterminmax_update', ':id') }}";
            route = route.replace(':id', id);
            $.ajax({
                url: route,
                type: "POST",
                data: $('#form-masterMinMaxedit').serialize(),
                success: function(data) {
                    // console.log(data);
                    // validasi_minmax_log();
                    clear_input_edit();
                    Swal.fire({
                        icon: 'success',
                        title: 'Successfully!',
                        text: 'Data berhasil di update',
                        // timer: 3000
                    }).then(function() {
                        $('#editMinmax').modal('hide');
                        $('#masterMinMax-datatables').DataTable().ajax.reload();
                        // location.reload();

                    });
                },
                error: function(data) {
                    // console.log(data);
                }
            });

        }


        // function validai min max for stocklimit_log
        // function validasi_minmax_log() {

        // }

    });
</script>
