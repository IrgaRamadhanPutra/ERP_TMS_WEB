<div class="modal fade bd-example-modal-xl editAddress" data-backdrop="static" data-keyboard="false" style="z-index: 1041"
    tabindex="-1" id="editAddress" role="dialog">
    <div class="modal-dialog modal-xl" style="max-width: 1000px; width: 100%">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark py-2 bg-light">
                <h4 class="modal-title" style="font-size: 17px">Form Master Kanban <--Edit--></h4>
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body">
                        <form id="form-masterAddressRmEdit" method="post" action="javascript:void(0)">
                            @csrf
                            @method('POST')
                            <input name="id_update" id="id_update" hidden>
                            @include('tms.warehouse.master-address-rm.modal.edit-address-rm.form')
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
                            class="btn btn-info btn-lg-6 updateMasterAddressRm" style="height: 38px;">
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
        var itemcode = $(this).attr('data-id');

        // Set the ID value in the hidden input field
        $('#id_update').val(id);

        // Show the modal
        $('#editAddress').modal('show');

        // Call the function to fetch additional data if needed
        getDataedit(id, 'EDIT');
    });

    function getDataedit(id, method) {
        var route = "{{ route('tms.warehouse.master_master_address_rm.edit_master_address_rm', ':id') }}";
        route = route.replace(':id', id);
        // console.log(route); // Ensure the route is correct
        $.ajax({
            url: route,
            method: 'get',
            dataType: 'json',
            success: function(response) {
                // console.log(response); // Debug untuk melihat respons
                var data = response.data; // Akses objek data dalam respons

                // Populate modal fields dengan data
                $('#chutter_edit').val(data.chuter_address || '');
                $('#itemcode_edit').val(data.itemcode || '');
                $('#partno_edit').val(data.part_no || '');
                $('#partname_edit').val(data.part_name || '');
                $('#part_type_edit').val(data.part_type || '');
                $('#process_code_edit').val(data.process_code || '');
                $('#supplier_edit').val(data.supplier || '');
                $('#cust_code_edit').val(data.cust_code || '');
                $('#stock_type_edit').val(data.address_type || '');
                $('#plant_edit').val(data.plant || '');

                // Fokus pada input chutter_edit
                $('#chutter_edit').focus();
            },
            error: function(xhr, status, error) {
                console.log('Error:', error); // Menampilkan error di console
            }
        });


        // UPDATE DATA EKANBAN PARAM
        $(document).off('click', '.updateMasterAddressRm').on('click', '.updateMasterAddressRm', function(e) {
            e.preventDefault();

            // Ambil data form
            var formData = $('#form-masterAddressRmEdit').serialize();

            $.ajax({
                url: "{{ route('tms.warehouse.master_master_address_rm.update_master_address_rm') }}",
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF Token
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        }).then(function() {
                            $('#editAddress').modal('hide');
                            $('#masterAddressRm-datatables').DataTable().ajax.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while processing the request.'
                    });
                }
            });
        });
    }
</script>
