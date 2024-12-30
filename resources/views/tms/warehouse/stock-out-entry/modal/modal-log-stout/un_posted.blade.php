<div class="modal fade-out bd-example-modal-lg modalUnPostStout"  tabindex="-1" id="ModalUnPostStout"  role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" ></h4>
        </div>
        <div class="row">
         <div class="col">
          <div class="modal-body">
            <form  id="form-stout-un-post" method="post" action="javascript:void(0)">
              @csrf
              @method('POST')
              <input type="hidden" id="out_no_unpost" name="out_no" value="">
              <div class="form-group row">
                <label for="type"   class="col-2 col-form-label">Type</label>
                <div class="col-9">
                  <input type="text" disabled  value="UN-POST"  name="out_no" class="form-control form-control-sm" placeholder="">
                </div>
            </div>
            <div class="form-group row">
                <label for="number"   class="col-2 col-form-label">Number</label>
                <div class="col-9">
                  <input type="text" disabled  name="out_no" class="form-control form-control-sm out_no_unpost" placeholder="">
                </div>
            </div>
            <div class="form-group row">
                <label for="exception_note"  class="col-2 col-form-label">Exception Note</label>
                <div class="col-9">
                  <input type="text"  autocomplete="off" name="note" id="note" class="form-control form-control-sm" placeholder="">
                </div>
            </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-info btn-flat btn-sm " data-dismiss="modal">Cancel</button>
                <button type="button"  class="btn btn-info btn-flat btn-sm ok_unpost_stout" ><i class="ti-check"></i> Ok</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>