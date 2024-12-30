<div class="modal fade bd-example-modal-xl viewMinMaxRm" data-backdrop="static" data-keyboard="false" style="z-index: 1041"
    tabindex="-1" id="viewMinMaxRm" role="dialog">
    <div class="modal-dialog modal-xl" style="max-width: 1000px; width: 100%">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark py-2 bg-light">
                <h4 class="modal-title" style="font-size: 17px">Form Master Min Max Rm <--view--></h4>
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body">
                        <form id="form-masterKanban" method="post" action="javascript:void(0)">
                            {{-- @csrf --}}
                            {{-- @method('POST') --}}
                            @include('tms.warehouse.min-max-rm.modal.view-min-max-rm.form')
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
        // alert(id);
        $('#viewMinMaxRm').modal('show');
        getDetail(id, 'VIEW');
    });

    function getDetail(id, method) {
        var route = "{{ route('tms.warehouse.master_min_max_rm.view_min_max_rm', ':id') }}";
        route = route.replace(':id', id);

        console.log(route); // Ensure the route is correct
        $.ajax({
            url: route,
            method: 'get',
            dataType: 'json',
            success: function(data) {
                if (data.status === 'success') {
                    var details = data.data[0]; // Ambil objek pertama dari array data

                    // Populate modal fields with data
                    $('#chutter_view').val(details.chutter_address);
                    $('#itemcode_view').val(details.itemcode);
                    $('#partno_view').val(details.part_number);
                    $('#partname_view').val(details.part_name);
                    $('#part_type_view').val(details.part_type);
                    $('#stock_type_view').val(details.stock_type ?? '-'); // Tambahkan default jika null
                    $('#unit_view').val(details.unit ?? '-'); // Tambahkan default jika null
                    $('#plant_view').val(details.plant);
                    $('#cust_code_view').val(details.cust_code);
                    $('#min_view').val(details.min);
                    $('#max_view').val(details.max);

                    console.log("Data populated to modal fields:", details);
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
    }
</script>
