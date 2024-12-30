<fieldset class="form">
    <div class="modal-body">
        <div class="form-group row align-items-center">
            <label for="production_line_view" class="col-sm-2 col-form-label"><b>Product Line</b></label>
            <div class="col-sm-3">
                <input type="text" name="production_line_view" class="form-control form-control-sm"
                    id="production_line_view" value="" style="height: 38px;" readonly>
            </div>
        </div>

        <div class="form-group row align-items-center">
            <label for="line_code_view" class="col-sm-2 col-form-label"><b>Line Code</b></label>
            <div class="col-sm-3">
                <input type="text" name="line_code_view" class="form-control form-control-sm" id="line_code_view"
                    value="" style="height: 38px;" readonly>
            </div>
        </div>

        <div class="form-group row">
            <label for="itemcode_view" class="col-sm-2 col-form-label"><b>Itemcode</b></label>
            <div class="col-sm-4">
                <div class="input-group-append">
                    <input type="text" value="" name="itemcode_view"
                        class="form-control form-control-sm uppercase-input" id="itemcode_view" placeholder=""
                        style="height: 38px;" readonly>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="kanban_view" class="col-sm-2 col-form-label"><b>Kanban No</b></label>
            <div class="col-sm-4">
                <div class="input-group-append">
                    <input type="text" value="" name="kanban_view"
                        class="form-control form-control-sm uppercase-input" id="kanban_view" placeholder=""
                        style="height: 38px;" readonly>
                </div>
            </div>
        </div>

        <div class="form-group row align-items-center">
            <label for="partno_view" class="col-sm-2 col-form-label pr-0"><b>Part No</b></label>
            <div class="col-sm-3">
                <input type="text" value="" name="partno_view"
                    class="form-control form-control-sm uppercase-input" id="partno_view" placeholder=""
                    style="height: 38px;" readonly>
            </div>

            <label for="partname_view"><b>Part Name</b></label>
            <div class="col-sm-4">
                <input type="text" value="" name="partname_view"
                    class="form-control form-control-sm uppercase-input" id="partname_view" placeholder=""
                    style="height: 38px;" readonly>
            </div>
        </div>

        <div class="form-group row">
            <label for="part_type_view" class="col-sm-2 col-form-label"><b>Part Type</b></label>
            <div class="col-sm-3">
                <input type="text" value="" name="part_type_view"
                    class="form-control form-control-sm uppercase-input" id="part_type_view" placeholder=""
                    style="height: 38px;" readonly>
            </div>
        </div>

        <div class="form-group row">
            <label for="qty_view" class="col-sm-2 col-form-label"><b>Qty Kanban</b></label>
            <div class="col-sm-2">
                <input type="text" value="" name="qty_view"
                    class="form-control form-control-sm uppercase-input" id="qty_view" placeholder=""
                    style="height: 38px;" oninput="this.value = this.value.replace(/[^0-9]/g, '')" readonly>
            </div>
        </div>
        {{--
        <div class="form-group row">
            <label for="cust_view" class="col-sm-2 col-form-label"><b>Customer</b></label>
            <div class="col-sm-3">
                <input type="text" value="" name="cust_view"
                    class="form-control form-control-sm uppercase-input" id="cust_view" placeholder=""
                    style="height: 38px;" readonly>
            </div>
        </div> --}}
        <div class="form-group row">
            <label for="cust_view" class="col-sm-2 col-form-label"><b>Customer</b></label>
            <div class="col-sm-2">
                <input type="text" name="cust_view" class="form-control form-control-sm" id="cust_view"
                    value="" style="height: 38px;" readonly>
            </div>
            <div>
                <label><b>Branch</b></label>
            </div>

            <div class="col-3 mb-3 sm-2">
                <input type="text" name="branch_view" class="form-control form-control-sm" id="branch_view"
                    value="" style="height: 38px; width: 100px;" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="kanban_type_view" class="col-sm-2 col-form-label"><b>Kanban Type</b></label>
            <div class="col-sm-2">
                <input type="text" name="kanban_type_view" class="form-control form-control-sm"
                    id="kanban_type_view" value="" style="height: 38px;" readonly>
            </div>
            <div>
                <label><b>Base &nbsp;&nbsp;&nbsp;&nbsp;<br>Unit</b></label>
            </div>

            <div class="col-3 mb-3 sm-2">
                <input type="text" name="base_unit_view" class="form-control form-control-sm" id="base_unit_view"
                    value="" style="height: 38px; width: 100px;" readonly>
            </div>
        </div>
    </div>
</fieldset>
