<div class="modal fade bd-example-modal-xl modalViewminmaxchuter" data-backdrop="static" data-keyboard="false"
    style="z-index: 1041" tabindex="-1" id="modalViewminmaxchuter" role="dialog">
    <div class="modal-dialog modal-xl" style="max-width: 1000px; width: 100%">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark">
                <h4 class="modal-title"> Form Master Min Max --View--</h4>

                {{-- <button type="button" class="close" data-dismiss="modal" onclick="clear_value_create_page()"><span>&times;</span></button> --}}
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body">
                        <form method="POST">
                            @csrf
                            @include('tms.warehouse.chuter-minmax.modal.view-chuter-minmax.form')
                            {{-- <hr> --}}
                    </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" data-dismiss="modal">Done</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.view', function(e) {
        e.preventDefault();
        var id = $(this).attr('row-id');
        alert(id);
        $('#modalViewminmaxchuter').modal('show');
        getDetail(id, 'VIEW')
    });

    function getDetail(id, method) {
        var route = "{{ route('tms.warehouse.master_min_max.show_view_masterminmax_chuter', ':id') }}";
        route = route.replace(':id', id);
        console.log(route);
        $.ajax({
            url: route,
            method: 'get',
            dataType: 'json',
            success: function(data) {
                console.log(data);

                $('#chutter_view').val(data['getView'].chutter_address);
                $('#itemcode_view').val(data['getView'].itemcode);
                $('#partno_view').val(data['getView'].part_number);
                $('#partname_view').val(data['getView'].part_name);
                $('#part_type_view').val(data['getView'].part_type);
                $('#lot_view').val(data['getView'].qty_kanban);
                // $('#min_view').val(data['getView'].min);
                // $('#max_view').val(data['getView'].max);
                // $('#stok_view').val(data['totalQty']);
                $('#box_view_min').val(data['min_boxes']);
                $('#box_view_max').val(data['max_boxes']);
                var stokViewValue = parseInt(data['totalQty']);
                var minViewValue = parseInt(data['getView'].min);
                var maxViewValue = parseInt(data['getView'].max);

                $('#min_view').val(minViewValue);
                $('#max_view').val(maxViewValue);
                $('#stok_view').val(stokViewValue);


                // Update the value of stok_view input
                $('#stok_view').val(stokViewValue);

                // Add a class to stok_view based on the condition
                // Remove any existing status text span
                // $('#stok_view .status-text').remove();

                // Hide both spans initially
                $('#kritis, #over').hide();

                if (stokViewValue < minViewValue) {
                    // Apply red color for minViewValue condition
                    $('#stok_view').css({
                        'border-color': 'red',
                        'color': '' // Reset color style
                    });
                    // Show the "kritis" span
                    $('#kritis').show();
                } else if (stokViewValue > maxViewValue) {
                    // Apply yellow color for maxViewValue condition
                    $('#stok_view').css({
                        'border-color': 'yellow',
                        'color': '' // Reset color style
                    });
                    // Show the "over" span
                    $('#over').show();
                } else {
                    // Reset styles if neither condition is met
                    $('#stok_view').css({
                        'color': '',
                        'border-color': ''
                    });
                    // Hide both spans if neither condition is met
                    $('#kritis, #over').hide();
                }
            }
        });
    }
</script>
