<div class="modal fade bd-example-modal-lg modalviewStout" data-backdrop="static" data-keyboard="false" style="z-index: 1041" tabindex="-1" id="viewModalStout"   role="dialog">
    <div class="modal-dialog modal-80">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" ></h4>
          {{-- <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button> --}}
        </div>
        <div class="row">
         <div class="col">
          <div class="modal-body">
            <form  method="POST">
              @csrf
              @include('tms.warehouse.stock-out-entry.modal.view-stout-modal._form')
              <hr>
              <div class="row">
                <div class="col-12">
                 <div class="data-tables datatable-primary">
                  <table id="tbl-detail-stout-view" class="table table-bordered table-hover" width="100%" >
                    {{-- <thead style="background-color: #D3D3D3"> --}}
                      <thead class="text-center">
                      <tr>
                        <th>Item Code</th>
                        <th>Part No</th>
                        <th>Description</th>
                        <th>Fac Unit</th>
                        <th>Fac Qty</th>
                        <th>Factor</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Remark</th>
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