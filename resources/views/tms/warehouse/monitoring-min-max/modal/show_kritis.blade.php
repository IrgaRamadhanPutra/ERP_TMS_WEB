<!-- Modal untuk menampilkan detail -->
<div class="modal fade bd-example-modal-lg detailModalkritis" data-backdrop="static" data-keyboard="false" style="z-index: 1041"
    tabindex="-1" id="detailModalkritis" role="dialog">
    <div class="modal-dialog modal-lg" style="max-width: 1000px; width: 100%">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark">
                <h4 class="modal-title">Monitoring Min Max --Kritis--</h4>
                <span aria-label="Close" class="close" data-dismiss="modal" style="position: absolute; top: 10px; right: 10px;">
                    <span aria-hidden="true">&times;</span>
                </span>
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body create-modal">
                        <div class="modal-body">
                            <table id="tbl-data-kritis" class="table table-striped table-bordered table-hover" width="100%">
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
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
