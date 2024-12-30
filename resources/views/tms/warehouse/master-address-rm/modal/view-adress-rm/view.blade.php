<div class="modal fade bd-example-modal-xl viewMasterAddressRm" data-backdrop="static" data-keyboard="false"
    style="z-index: 1041" tabindex="-1" id="viewMasterAddressRm" role="dialog">
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
                            @include('tms.warehouse.master-address-rm.modal.view-adress-rm.form')
                        </form>
                    </div>
                    <div class="modal-footer d-flex justify-content-end py-3 bg-light">
                        <button type="button" data-toggle="tooltip" data-placement="top"
                            class="btn btn-info btn-lg-6 closeModal" data-dismiss="modal" style="height: 38px;">

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
        $('#viewMasterAddressRm').modal('show');
        getDetail(id, 'VIEW');
    });

    function getDetail(id, method) {
        var route = "{{ route('tms.warehouse.master_master_address_rm.view_master_address_rm', ':id') }}";
        route = route.replace(':id', id);
        // console.log(route); // Ensure the route is correct
        $.ajax({
            url: route,
            method: 'get',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success' && response.data) {
                    const data = response.data;

                    // Populate modal fields with response data
                    $('#chutter_view').val(data.chuter_address || '');
                    $('#itemcode_view').val(data.itemcode || '');
                    $('#partno_view').val(data.part_no || '');
                    $('#partname_view').val(data.part_name || '');
                    $('#part_type_view').val(data.part_type || '');
                    $('#process_code_view').val(data.process_code || '');
                    $('#supplier_view').val(data.supplier || '');
                    $('#cust_code_view').val(data.cust_code || '');
                    $('#stock_type_view').val(data.address_type || '');
                    $('#plant_view').val(data.plant || '');
                } else {
                    console.error('Invalid response format:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });

    }
</script>
