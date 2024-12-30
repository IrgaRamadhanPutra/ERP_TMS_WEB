<div class="modal fade bd-example-modal-lg viewMto"   id="viewModal"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-80">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title view"></h4>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="row">
       <div class="col">
        <div class="modal-body view-modal">
          <form  method="POST">
            @csrf
            @include('tms.warehouse.mto-entry.modal.view-mto-modal._viewformDetail')
            <hr>
            <div class="row">
              <div class="col-12">
                <div class="datatable datatable-primary">
                <table id="tbl-detail-mto" class="table table-bordered table-hover" width="100%" >
                  <thead class="text-center" style="text-transform: uppercase; font-size: 11px;" >
                    <tr>
                      <th width="20%">Item Code</th>
                      <th width="20%">Part No</th>
                      <th width="30%">Description</th>
                      <th width="10%">Unit</th>
                      <th width="10%">Qty</th>
                      <th width="10%">Qty NG</th>
                      <th width="10%">Warehouse</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>  
              </div>
            </div>  
          </div>  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" data-dismiss="modal">Done</button>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</div>