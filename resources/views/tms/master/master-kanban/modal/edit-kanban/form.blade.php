<fieldset class="form">
    <div class="modal-body">
        <div class="form-group row align-items-center">
            <label for="production_line_edit" class="col-sm-2 col-form-label"><b>Product Line</b></label>
            <div class="col-sm-3">
                <input type="text" value="" name="production_line_edit"
                    class="form-control form-control-sm uppercase-input" id="production_line_edit" placeholder=""
                    style="height: 38px;" readonly>
                {{-- <select name="production_line_edit" class="form-control form-control-sm" id="production_line_edit"
                    style="height: 38px;">
                    <option value="" disabled selected hidden>--Choice--</option>
                    <option value="INHOUSE">INHOUSE</option>
                    <option value="SUBCONT">SUBCONT</option>
                </select> --}}
            </div>
        </div>

        <div class="form-group row align-items-center">
            <label for="line_code_edit" class="col-sm-2 col-form-label"><b>Line Code</b></label>
            <div class="col-sm-3">
                <select name="line_code_edit" class="form-control form-control-sm" id="line_code_edit"
                    style="height: 38px;">
                    <!-- Options will be populated based on selection -->
                    <option value="" disabled selected hidden>--Choice--</option>
                    <option value="ASSY">ASSY</option>
                    <option value="PQA">PQA</option>
                    <option value="PRESS">PRESS</option>
                    <option value="WELDING">WELDING</option>
                    <option value="SPOT">SPOT</option>
                    <option value="QC">QC</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="itemcode_edit" class="col-sm-2 col-form-label"><b>Itemcode</b></label>
            <div class="col-sm-4">
                <div class="input-group-append">
                    <input type="text" value="" name="itemcode_edit"
                        class="form-control form-control-sm uppercase-input" id="itemcode_edit" placeholder=""
                        style="height: 38px;" readonly>
                </div>
                {{-- <p class="text-danger">Search Itemcode</p> --}}
            </div>
        </div>
        <div class="form-group row">
            <label for="kanban_edit" class="col-sm-2 col-form-label"><b>Kanban No</b></label>
            <div class="col-sm-4">
                <div class="input-group-append">
                    <input type="text" value="" name="kanban_edit"
                        class="form-control form-control-sm uppercase-input" id="kanban_edit" placeholder=""
                        style="height: 38px;" readonly>
                </div>
            </div>
        </div>
        <div class="form-group row align-items-center">
            <label for="partno_edit" class="col-sm-2 col-form-label pr-0"><b>Part No</b></label>
            <div class="col-sm-3"> <!-- Reduced width for Part No -->
                <input type="text" value="" name="partno_edit"
                    class="form-control form-control-sm uppercase-input" id="partno_edit" placeholder=""
                    style="height: 38px;" readonly>
            </div>

            <label for="partname_edit"><b>Part Name</b></label>
            <div class="col-sm-4"> <!-- Increased width for Part Name -->
                <input type="text" value="" name="partname_edit"
                    class="form-control form-control-sm uppercase-input" id="partname_edit" placeholder=""
                    style="height: 38px;" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="part_type_edit" class="col-sm-2 col-form-label"><b>Part Type</b></label>
            <div class="col-sm-3">
                <input type="text" value="" name="part_type_edit"
                    class="form-control form-control-sm uppercase-input" id="part_type_edit" placeholder=""
                    style="height: 38px;" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="qty_edit" class="col-sm-2 col-form-label"><b>Qty Kanban</b></label>
            <div class="col-sm-2">
                <input type="text" value="" name="qty_edit"
                    class="form-control form-control-sm uppercase-input" id="qty_edit" placeholder=""
                    style="height: 38px;">
            </div>
        </div>
        <div class="form-group row">
            <label for="cust_edit" class="col-sm-2 col-form-label"><b>Customer</b></label>
            <div class="col-sm-2">
                <input type="text" name="cust_edit" class="form-control form-control-sm" id="cust_edit"
                    value="" style="height: 38px;" readonly>
            </div>
            <div>
                <label><b>Branch</b></label>
            </div>

            <div class="col-3 mb-3 sm-2">
                <input type="text" name="branch_edit" class="form-control form-control-sm" id="branch_edit"
                    value="" style="height: 38px; width: 100px;" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="kanban_type_edit" class="col-sm-2 col-form-label"><b>Kanban Type</b></label>
            <div class="col-sm-2">
                <input type="text" name="kanban_type_edit" class="form-control form-control-sm"
                    id="kanban_type_edit" value="" style="height: 38px;" readonly>
            </div>
            <div>
                <label><b>Base &nbsp;&nbsp;&nbsp;&nbsp;<br>Unit</b></label>
            </div>

            <div class="col-3 mb-3 sm-2">
                <input type="text" name="base_unit_edit" class="form-control form-control-sm" id="base_unit_edit"
                    value="" style="height: 38px; width: 100px;" readonly>
            </div>
        </div>
        {{-- <div class="form-group row">
            <label for="kanban_type_edit" class="col-sm-2 col-form-label"><b>Kanban Type</b></label>
            <div class="col-sm-2">
                <select name="kanban_type_edit" class="form-control form-control-sm" id="kanban_type_edit"
                    style="height: 38px;">
                    <option value="" disabled selected hidden>--Choice--</option>
                    <option value="F/G">F/G</option>
                    <option value="WIP">WIP</option>
                    <option value="R/M">R/M</option>
                </select>

            </div>
            <div class="colsm-4 mb-1">
                <label for="base_unit_edit"><b>Base <br> Unit</b></label>
            </div>
            <div class="col-3 mb-3 sm-2">

                <select name="base_unit_edit" class="form-control form-control-sm" id="base_unit_edit"
                    style="height: 38px;width:100px">
                    <option value="" disabled selected hidden>--Choice--</option>
                    <option value="PCE">PCE</option>
                    <option value="KG">KG</option>
                    <option value="SHE">SHE</option>
                </select>
            </div>

        </div> --}}

</fieldset>
