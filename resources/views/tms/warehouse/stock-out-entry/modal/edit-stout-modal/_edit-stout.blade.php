<div class="modal fade bd-example-modal-lg modaleditStout"  style="z-index: 1041" tabindex="-1" id="editModalStout" data-backdrop="static" data-keyboard="false"  role="dialog">
    <div class="modal-dialog modal-80">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title formM" ></h4>
          {{-- <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button> --}}
        </div>
        <div class="row row-edit">
         <div class="col">
          <div class="modal-body edit-modal">
            @include('tms.warehouse.stock-out-entry.modal.edit-stout-modal._form')
            <form  id="form-stout-edit" method="post" action="javascript:void(0)" >
              @csrf
              @method('PUT')
              <input type="hidden" id="id_stout_edit" name="id_stout_edit">
              <hr>
              <div class="row">
                <div class="col-12">
                 <div class="data-tables datatable-primary">
                  <div class="table-responsive">
                  <table id="tbl-edit-stout" class="table table-bordered table-hover" width="100%" >
                    {{-- style="background-color: #D3D3D3" --}}
                    <div class="pull-left">
                      {{-- <i class="fa fa-reply" aria-hidden="true"></i> --}}
                      {{-- <button type="button" onclick="restore()" class="btn btn-warning btn-xs restore"><i class="fa fa-reply" aria-hidden="true"></i> Restore item</button> --}}
                    </div> 
                    <thead  class="text-center">
                      <tr>
                        <th width="15%">Item Code</th>
                        <th width="15%">Part No</th>
                        <th width="15%">Description</th>
                        <th width="15%">F.Unit</th>
                        <th width="15%">F.Qty</th>
                        <th width="15%">Factor</th>
                        <th width="10%">Unit</th>
                        <th width="15%">Qty</th>
                        <th width="15%">Remark</th>
                        <th width="16%">
                            {{-- <a href="#" class="btn btn-xs btn-danger addRowEdit" id="addRowEdit">
                                <i class="ti-plus"></i>
                            </a> --}}
                            {{-- ACTION --}}
                           <button type="button" data-toggle="tooltip"  data-placement="top" title="Add Item" class="btn btn-danger btn-xs addRowEdit" id="addRowEdit"> <i class="ti-plus"></i></button>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>  
                  </div>
                </div>
              </div>  
            </div>
            <br>
            <div class="modal-footer">
              {{-- <button type="button" data-toggle="tooltip"  data-placement="top" title="Add Item" class="btn btn-info addRowEdit" id="addRowEdit"> Add Item</button> --}}
              <button type="button" class="btn btn-info " data-dismiss="modal">Close</button>
              
              <button type="button" onclick="editHeader()"  class="btn btn-info updateStout" ><i class="ti-check"></i> Save</button>
              {{-- <input type="hidden" id="jml_row_edit" name="jml_row" value="{{ $stoutCount }}"> --}}
              <input type="hidden" class="jml_row_editdetail" id="jml_row_editdetail" value="">
            </form>
          </div>
          {{-- <button type="button" class="btn btn-info display">Refresh</button> --}}
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>