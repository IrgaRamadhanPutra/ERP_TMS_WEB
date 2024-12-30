<div class="modal fade bd-example-modal-xl logMasterkanban" data-backdrop="static" data-keyboard="false"
    style="z-index: 1041" tabindex="-1" id="logMasterkanban" role="dialog">
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
                                    <table id="tblMasterkanban" class="table table-bordered table-hover" width="100%">

                                        <thead class="text-center">
                                            {{-- style="background-color: #D3D3D3" --}}
                                            <tr>
                                                <th width="10%">Kanban No</th>
                                                <th width="10%">Itemcode</th>
                                                <th width="15%">Part No</th>
                                                <th width="20%">Part Name</th>
                                                <th width="5%">Part Type</th>
                                                <th width="5%">Cust</th>
                                                <th width="5%">Qty</th>
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
            // alert(itemcode);
            $('#logMasterkanban').modal('show');

            var route = "{{ route('tms.warehouse.master_kanban.master_kanban_log', ':itemcode') }}";
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
                        // console.log(rowData);
                        var detailData = [
                            rowData.ekanban_no,
                            rowData.item_code,
                            rowData.part_no,
                            rowData.part_name,
                            rowData.model,
                            rowData.customer,
                            rowData.qty_kanban,
                            rowData.creation_date,
                            rowData.created_by,
                            // Tambahkan kolom lain sesuai kebutuhan
                        ];

                        detailDataset.push(detailData);
                    }
                    console.log(detailDataset);
                    $('#tblMasterkanban').DataTable().clear().destroy();
                    $('#tblMasterkanban').DataTable({
                        data: detailDataset,
                        columns: [{
                                data: 0
                            }, // ekanban_no
                            {
                                data: 1
                            }, // Item Code
                            {
                                data: 2
                            }, // part_no
                            {
                                data: 3
                            }, // part_name
                            {
                                data: 4
                            }, // model
                            {
                                data: 5
                            }, // customer
                            {
                                data: 6
                            }, // qty_kanban
                            {
                                data: 7
                            }, // created_by
                            {
                                data: 8
                            } // creation_date

                        ]
                    });
                },
            })


        });

    });
</script>
