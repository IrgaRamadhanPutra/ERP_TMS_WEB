<div class="modal fade bd-example-modal-xl editMinMaxRm" data-backdrop="static" data-keyboard="false" style="z-index: 1041"
    tabindex="-1" id="editMinMaxRm" role="dialog">
    <div class="modal-dialog modal-xl" style="max-width: 1000px; width: 100%">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark py-2 bg-light">
                <h4 class="modal-title" style="font-size: 17px">Form Master Min Max Rm <--edit--></h4>
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body">
                        <form id="form-masterMinMaxRmEdit" method="post" action="javascript:void(0)">
                            <input id="id_raw" name="id_raw" hidden>
                            {{-- @csrf --}}
                            {{-- @method('POST') --}}
                            @include('tms.warehouse.min-max-rm.modal.edit-min-max-rm.form')
                            <input id="min_old" name="min_old" hidden>
                            <input id="max_old" name="max_old" hidden>
                        </form>
                    </div>
                    <div class="modal-footer d-flex justify-content-end py-3 bg-light">
                        {{-- <button type="button" onclick="clear_input()" data-toggle="tooltip" class="btn text-dark"
                            data-dismiss="modal">
                            <i class="ti-close"></i>
                            &nbsp;Close
                        </button> --}}
                        <button type="button" data-toggle="tooltip" data-placement="top"
                            class="btn btn-secondary btn-lg-6" data-dismiss="modal" style="height: 38px;">
                            <i class="fa fa-times-circle"></i>
                            &nbsp; Close
                        </button>
                        <button type="button" data-toggle="tooltip" data-placement="top"
                            class="btn btn-info btn-lg-6 updatemasterMinMaxRm" style="height: 38px;">
                            {{-- <i class="fas fa-check"></i> --}}
                            <i class="fa fa-check-square"></i>
                            &nbsp;&nbsp; Save
                        </button>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // ADD DISPLAY MODAL EDIT
    $(document).on('click', '.edit', function(e) {
        e.preventDefault();

        // Get the data attributes from the clicked element
        var id = $(this).attr('row-id');
        // alert(id);
        $('#id_raw').val(id);

        var itemcode = $(this).attr('data-id');

        // Optionally, log these values for debugging
        // console.log("Editing item with ID:", id);
        // console.log("Item code:", itemcode);

        // Set the ID value in the hidden input field
        // $('#id_update').val(id);

        // Show the modal
        $('#editMinMaxRm').modal('show');

        // Call the function to fetch additional data if needed
        getDataedit(id, 'EDIT');
    });

    function getDataedit(id, method) {
        var route = "{{ route('tms.warehouse.master_min_max_rm.edit_min_max_rm', ':id') }}";
        route = route.replace(':id', id);

        // console.log(route); // Ensure the route is correct
        $.ajax({
            url: route,
            method: 'get',
            dataType: 'json',
            success: function(data) {
                if (data.status === 'success') {
                    var details = data.data[0]; // Ambil objek pertama dari array data
                    // console.log(details);
                    // Populate modal fields with data
                    $('#chutter_edit').val(details.chutter_address);
                    $('#itemcode_edit').val(details.itemcode);
                    $('#partno_edit').val(details.part_number);
                    $('#partname_edit').val(details.part_name);
                    $('#part_type_edit').val(details.part_type);
                    $('#stock_type_edit').val(details.stock_type ?? '-'); // Tambahkan default jika null
                    $('#unit_edit').val(details.unit ?? '-'); // Tambahkan default jika null
                    $('#plant_edit').val(details.plant);
                    $('#cust_code_edit').val(details.cust_code);
                    $('#min_edit').val(details.min);
                    $('#min_old').val(details.min);
                    $('#max_edit').val(details.max);
                    $('#max_old').val(details.max);

                    // console.log("Data populated to modal fields:", details);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Unexpected error occurred.'
                    });
                }
            },

            error: function(xhr, status, error) {
                // Handle any errors
                console.log('Error:', error);
            }

        });

        // UPDATE DATA MASTER MIN MAX RM
        $(document).off('click', '.updatemasterMinMaxRm').on('click', '.updatemasterMinMaxRm', function(e) {
            e.preventDefault();
            // inisialiasi var for route

            var formData = $('#form-masterMinMaxRmEdit').serialize();
            // console.log(formData);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('tms.warehouse.master_min_max_rm_update') }}",
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // console.log(response);
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Successfully!',
                            text: response.message,
                        }).then(function() {
                            $('#editMinMaxRm').modal('hide');
                            $('#masterMinMaxRm-datatables').DataTable().ajax.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    // alert('An error occurred. Please try again.');
                }
            });
        });
    }
    // just number for min and maxs
    function validateNumericInput(input) {
        // Hanya izinkan angka, titik (.), dan koma (,)
        input.value = input.value.replace(/[^0-9.,]/g, '');
    }
</script>
