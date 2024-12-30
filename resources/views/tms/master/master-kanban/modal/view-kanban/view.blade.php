<div class="modal fade bd-example-modal-xl viewKanban" data-backdrop="static" data-keyboard="false" style="z-index: 1041"
    tabindex="-1" id="viewKanban" role="dialog">
    <div class="modal-dialog modal-xl" style="max-width: 1000px; width: 100%">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark py-2 bg-light">
                <h4 class="modal-title" style="font-size: 17px">Form Master Kanban <--view--></h4>
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body">
                        <form id="form-masterKanban" method="post" action="javascript:void(0)">
                            {{-- @csrf --}}
                            {{-- @method('POST') --}}
                            @include('tms.master.master-kanban.modal.view-kanban.form')
                        </form>
                    </div>
                    <div class="modal-footer d-flex justify-content-end py-3 bg-light">
                        <button type="button" data-toggle="tooltip" data-placement="top"
                            class="btn btn-info btn-lg-6 closeModal" data-dismiss="modal" style="height: 38px;">
                            {{-- <i class="fas fa-check"></i> --}}
                            <i class="fa fa-times-circle"></i>
                            &nbsp;&nbsp; Close
                        </button>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.view', function(e) {
        e.preventDefault();
        var id = $(this).attr('row-id'); // Make sure this contains the correct ID
        $('#viewKanban').modal('show');
        getDetail(id, 'VIEW');
    });

    function getDetail(id, method) {
        var route = "{{ route('tms.warehouse.master_kanban.view_kanban', ':id') }}";
        route = route.replace(':id', id);
        // console.log(route); // Ensure the route is correct
        $.ajax({
            url: route,
            method: 'get',
            dataType: 'json',
            success: function(data) {
                // console.log(data); // Handle response

                // Populate modal fields with data
                $('#production_line_view').val(data.production_line);
                $('#line_code_view').val(data.line_code);
                $('#itemcode_view').val(data.item_code);
                $('#kanban_view').val(data.ekanban_no);
                $('#partno_view').val(data.part_no);
                $('#partname_view').val(data.part_name);
                $('#part_type_view').val(data.model); // Assuming part_type corresponds to the model
                $('#qty_view').val(data.qty_kanban);
                $('#cust_view').val(data.customer);
                $('#kanban_type_view').val(data.kanban_type);
                $('#base_unit_view').val(data.base_unit);
                $('#branch_view').val(data.branch);
            },
            error: function(xhr, status, error) {
                // Handle any errors
                console.log('Error:', error);
            }
        });
    }
</script>
