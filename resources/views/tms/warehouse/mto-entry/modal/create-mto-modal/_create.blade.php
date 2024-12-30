<div class="modal fade bd-example-modal-lg modalcreate"   style="z-index: 1041" tabindex="-1" id="createModal" data-target="#mtoModal" data-whatever="@createMTO"  role="dialog">
  <div class="modal-dialog modal-80">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" ></h4>
        <button type="button" class="close" data-dismiss="modal"  onclick="clear_value_create_page()"><span>&times;</span></button>
      </div>
      <div class="row">
       <div class="col">
        <div class="modal-body create-modal">
          <div class="alert alert-danger print-error-msg" role="alert" style="display: none">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <ul></ul>
          
          </div>
          <form  id="form-mto" method="post" action="javascript:void(0)">
            @csrf
            <input type="hidden" id="id_mto" name="id_mto">
            @include('tms.warehouse.mto-entry.modal.create-mto-modal._form')
            <hr>
            <div class="row">
              <div class="col-12">
                <div class="datatable datatable-primary">
                <table id="tbl-create-mto" class="table table-bordered table-hover table-create-mto" width="100%" >
                  <thead class="text-center" id="thead" style="text-transform: uppercase; font-size: 11px;" >
                    <tr>
                      <th width="20%">Item Code</th>
                      <th width="20%">Part No</th>
                      <th width="35%">Description</th>
                      <th width="10%">Unit</th>
                      <th width="10%">Qty</th>
                      <th width="11%">Qty NG</th>
                      <th width="9%">Warehouse</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>  
              </div>
            </div>  
          </div>
          <div class="modal-footer">
            <button type="button" onclick="clear_value_create_page()" class="btn btn-info " data-dismiss="modal">Close</button>
            {{-- <button type='button'  id="btn-remove" class='btn btn-info'>Delete</button> --}}
            <button type="button" class="btn btn-info add" ><i class="ti-check"></i> Save</button>
        </form>
        <input type="hidden" id="jumlah_row" value="">
        <input type="hidden" id="jmlh_row" value="">
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>