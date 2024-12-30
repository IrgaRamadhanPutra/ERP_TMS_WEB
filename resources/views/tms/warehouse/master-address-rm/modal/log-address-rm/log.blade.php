<div class="modal fade bd-example-modal-xl logMasteraddress" data-backdrop="static" data-keyboard="false"
    style="z-index: 1041" tabindex="-1" id="logMasteraddress" role="dialog">
    <div class="modal-dialog modal-xl" style="max-width: 1000px; width: 100%">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark">
                <h4 class="modal-title"> Form Master Kanban --Log--</h4>

                {{-- <button type="button" class="close" data-dismiss="modal" onclick="clear_value_create_page()"><span>&times;</span></button> --}}
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="datatable datatable-primary">
                                    <table id="tblMasteraddress" class="table table-bordered table-hover"
                                        width="100%">

                                        <thead class="text-center">
                                            {{-- style="background-color: #D3D3D3" --}}
                                            <tr>
                                                <th width="10%">Itemcode</th>
                                                <th width="15%">Chuter Address</th>
                                                <th width="10%">Status</th>
                                                <th width="5%">Created Date</th>
                                                <th width="5%">Created By</th>
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
<script>
    // ADD DISPLAY MODAL LOG
    $(document).ready(function() {
        $(document).on('click', '.log', function(e) {
            e.preventDefault();
            var itemcode = $(this).attr('data-id');
            // alert(id);
            $('#logMasteraddress').modal('show');

            var route =
                "{{ route('tms.warehouse.master_address_rm.master_address_rm_log', ':itemcode') }}";
            route = route.replace(':itemcode', itemcode);
            $.ajax({
                url: route,
                method: 'get',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    var detailDataset = [];

                    for (var i = 0; i < data.length; i++) {
                        var rowData = data[i];
                        console.log(rowData);
                        var detailData = [
                            rowData.itemcode,
                            rowData.chuter_address,
                            rowData.status_change,
                            rowData.create_user,
                            rowData.create_date,
                            // Tambahkan kolom lain sesuai kebutuhan
                        ];

                        detailDataset.push(detailData);
                    }
                    console.log(detailDataset);
                    $('#tblMasteraddress').DataTable().clear().destroy();
                    $('#tblMasteraddress').DataTable({
                        data: detailDataset,
                        columns: [{
                                data: 0
                            },
                            {
                                data: 1
                            },
                            {
                                data: 2
                            },
                            {
                                data: 3
                            },
                            {
                                data: 4
                            }
                        ]
                    });
                },
            })


        });

    });
</script>
