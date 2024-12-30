<div class="modal fade bd-example-modal-xl editKanban" data-backdrop="static" data-keyboard="false" style="z-index: 1041"
    tabindex="-1" id="editKanban" role="dialog">
    <div class="modal-dialog modal-xl" style="max-width: 1000px; width: 100%">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark py-2 bg-light">
                <h4 class="modal-title" style="font-size: 17px">Form Master Kanban <--edit--></h4>
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body">
                        <form id="form-masterKanbanUpdate" method="post" action="javascript:void(0)">
                            @csrf
                            @method('POST')
                            <input name="id_update" id="id_update" hidden>
                            @include('tms.master.master-kanban.modal.edit-kanban.form')
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
                            class="btn btn-info btn-lg-6 updateMasterKanban" style="height: 38px;">
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

        // Optionally, log these values for debugging
        // console.log("Editing item with ID:", id);
        // console.log("Item code:", itemcode);

        // Set the ID value in the hidden input field
        $('#id_update').val(id);

        // Show the modal
        $('#editKanban').modal('show');

        // Call the function to fetch additional data if needed
        getDataedit(id, 'EDIT');
    });

    // GET DETAIL DATA FROM EKANBAN PARAM TO EDIT FORM
    function getDataedit(id, method) {
        var route = "{{ route('tms.warehouse.master_kanban.edit_kanban', ':id') }}";
        route = route.replace(':id', id);
        // console.log(route); // Ensure the route is correct
        $.ajax({
            url: route,
            method: 'get',
            dataType: 'json',
            success: function(data) {
                // console.log(data); // Handle
                // Populate modal fields with data
                $('#production_line_edit').val(data.production_line);
                $('#line_code_edit').val(data.line_code);
                $('#itemcode_edit').val(data.item_code);
                $('#kanban_edit').val(data.ekanban_no);
                $('#partno_edit').val(data.part_no);
                $('#partname_edit').val(data.part_name);
                $('#part_type_edit').val(data.model); // Assuming part_type corresponds to the model
                $('#qty_edit').val(data.qty_kanban);
                $('#cust_edit').val(data.customer);
                $('#kanban_type_edit').val(data.kanban_type);
                $('#base_unit_edit').val(data.base_unit);
                $('#branch_edit').val(data.branch);
            },
            error: function(xhr, status, error) {
                // Handle any errors
                console.log('Error:', error);
            }
        });

        // UPDATE DATA EKANBAN PARAM
        $(document).off('click', '.updateMasterKanban').on('click', '.updateMasterKanban', function(e) {
            e.preventDefault();
            // inisialiasi var for route
            var formData = $('#form-masterKanbanUpdate').serialize();
            $.ajax({
                url: "{{ route('tms.warehouse.master_kanban.update_masterkanban') }}",
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // console.log(response.message);
                        // alert(response.message);
                        Swal.fire({
                            icon: 'success',
                            title: 'Successfully!',
                            text: response.message,
                        }).then(function() {
                            $('#editKanban').modal('hide');
                            $('#masterKanban-datatables').DataTable().ajax.reload();
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
                    // alert('An error occurred. Please try again.');
                }
            });
        });

    }
</script>
