<div class="modal fade bd-example-modal-lg modaledit"  tabindex="-1" id="EditModal"  role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-80">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="row">
       <div class="col">
        <div class="modal-body edit-modal">
          <form  id="form-mto-edit" method="post" action="javascript:void(0)">
            @csrf
            @method('PUT')
            <input type="hidden" id="id_mto_edit" name="id_mto" value="">
            @include('tms.warehouse.mto-entry.modal.edit-mto-modal._formedit')
            <hr>
            <div class="row">
              <div class="col-12">
                <div class="datatable datatable-primary">
                <table id="tbl-edit" class="table table-bordered table-hover tbl-edit" width="100%" >
                  <thead class="text-center" style="text-transform: uppercase; font-size: 11px;" >
                    <tr>
                      <th width="20%">Item Code</th>
                      <th width="20%">Part No</th>
                      <th width="30%">Description</th>
                      <th width="10%">Unit</th>
                      <th width="12%">Qty</th>
                      <th width="10%">Qty NG</th>
                      <th width="8%">Warehouse</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>  
              </div>
            </div>  
          </div>  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info " data-dismiss="modal">Close</button>
          {{-- <button type='button'  id="btn-remove-edit" class='btn btn-info removerowEd'>Delete</button>
          <button type="button" id="btn-addrow-edit" class="btn btn-info addrowedit">Add Item</button> --}}
          <button type="button" onclick="UpdateData()" class="btn btn-info " ><i class="ti-check"></i> Save</button>
        </form>
        <input type="hidden" id="jmlh_row_edit" value="">
      </div>
    </div>
  </div>
</div>
</div>
</div>
</div>