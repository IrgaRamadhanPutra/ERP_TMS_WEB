<fieldset class="form">
    <div class="modal-body">
        <input type="hidden" id="id_master_void" name="id_master">
        <div class="form-group row">
            <label for="type" class="col-2 col-form-label">Type</label>
            <div class="col-9">
                <input type="text" disabled value="VOID" name="void" class="form-control form-control-sm"
                    placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label for="number" class="col-2 col-form-label">Code</label>
            <div class="col-9">
                <input type="text" readonly name="code_void" class="form-control form-control-sm code_void"
                    placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label for="exception_note" class="col-2 col-form-label">Exception Note</label>
            <div class="col-9">
                <input type="text" autocomplete="off" name="note" id="not_void"
                    class="form-control form-control-sm" placeholder="">
            </div>
        </div>
        <div class="modal-footer" style="text-align: right;">
            <button type="button" class="btn btn-info btn-flat btn-sm" data-dismiss="modal" style="height: 38px;">
                <i class="fa fa-times-circle"></i>
                &nbsp; Close
            </button>

            <button type="button" data-toggle="tooltip" data-placement="top"
                class="btn btn-info btn-flat btn-sm void_submit" style="height: 38px;" id="ok-button">
                <i class="fa fa-check-square"></i>
                &nbsp;&nbsp; Save
            </button>
        </div>

</fieldset>
