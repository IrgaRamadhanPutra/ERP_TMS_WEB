<!-- Modal untuk menampilkan detail -->
<div class="modal fade bd-example-modal-xl detailModalover" data-backdrop="static" data-keyboard="false" style="z-index: 1041"
    tabindex="-1" id="detailModalover" role="dialog">
    <div class="modal-dialog modal-xl" style="max-width: 1000px; width: 100%">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark">
                <h4 class="modal-title">Monitoring Min Max --Over--</h4>
                <span aria-label="Close" class="close" data-dismiss="modal" style="position: absolute; top: 10px; right: 10px;">
                    <span aria-hidden="true">&times;</span>
                </span>
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body create-modal">
                        <div class="modal-body">
                            <div style="max-height: 400px; overflow-y: auto; overflow-x: hidden;">
                                <table id="tbl-data-over" class="table table-striped table-bordered table-hover" width="100%">
                                    <thead style="text-transform: uppercase; font-size: 11px;">
                                        <tr>
                                            {{-- <th>Chuter Address</th> --}}
                                            <th>Itemcode</th>
                                            <th>Part no</th>
                                            <th>Part name</th>
                                            <th>Stock</th>
                                            <th>Min</th>
                                            <th>Max</th>
                                            {{-- <th>Over Flow</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Add your table rows here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
