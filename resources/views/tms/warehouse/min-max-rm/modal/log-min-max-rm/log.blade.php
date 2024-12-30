<div class="modal fade bd-example-modal-xl logMasterminmax" data-backdrop="static" data-keyboard="false"
    style="z-index: 1041" tabindex="-1" id="logMasterminmax" role="dialog">
    <div class="modal-dialog modal-xl" style="max-width: 1000px; width: 100%">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark">
                <h4 class="modal-title"> Form Master Min Max RM --Log--</h4>

                {{-- <button type="button" class="close" data-dismiss="modal" onclick="clear_value_create_page()"><span>&times;</span></button> --}}
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="datatable datatable-primary">
                                    <table id="tblMasterMinMaxRm" class="table table-bordered table-hover"
                                        width="100%">

                                        <thead class="text-center">
                                            {{-- style="background-color: #D3D3D3" --}}
                                            <tr>
                                                <th width="10%">Chutter Address</th>
                                                <th width="10%">Item Code</th>
                                                <th width="10%">Action </th>
                                                <th width="10%">Colum Name</th>
                                                <th width="10%">Old Value</th>
                                                <th width="10%">New Value</th>
                                                <th width="25%">User</th>
                                                <th width="25%">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" data-dismiss="modal">Esc</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    // ADD DISPLAY MODAL LOG
    $(document).ready(function() {
        $(document).on('click', '.log', function(e) {
            e.preventDefault();
            var id_minmax = $(this).attr('row-id');
            // alert(id_minmax);
            $('#logMasterminmax').modal('show');

            var route = "{{ route('tms.warehouse.master_min_max_rm_log', ':id') }}";
            route = route.replace(':id', id_minmax);
            $.ajax({
                url: route,
                method: 'get',
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    var detailDataset = [];

                    for (var i = 0; i < data.length; i++) {
                        var rowData = data[i];
                        // console.log(rowData);
                        var detailData = [
                            rowData.chutter_address,
                            rowData.itemcode,
                            rowData.action_name,
                            rowData.column_name,
                            rowData.old_value,
                            rowData.new_value,
                            rowData.action_user,
                            rowData.action_date,
                            // Tambahkan kolom lain sesuai kebutuhan
                        ];

                        detailDataset.push(detailData);
                    }
                    // console.log(detailDataset);
                    $('#tblMasterMinMaxRm').DataTable().clear().destroy();
                    $('#tblMasterMinMaxRm').DataTable({
                        data: detailDataset,
                        columns: [{
                                data: 0
                            }, // Chutter Address
                            {
                                data: 1
                            }, // Item Code
                            {
                                data: 2
                            }, // Action Name
                            {
                                data: 3
                            }, // Column Name
                            {
                                data: 4
                            }, // Old Value
                            {
                                data: 5
                            }, // New Value
                            {
                                data: 6
                            }, // Action User
                            {
                                data: 7
                            } // Action Date

                        ]
                    });
                },
            })


        });

    });
</script>
