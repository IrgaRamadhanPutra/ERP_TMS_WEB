<fieldset class="form">
    <div class="modal-body">
        <div class="form-group row align-items-center">
            <!-- Label for Production Line -->
            <label for="production_line_create" class="col-sm-2 col-form-label">
                <b>Product Line</b>
            </label>
            <div class="col-sm-3">
                <!-- Dropdown for Production Line -->
                <select name="production_line_create" class="form-control form-control-sm" id="production_line_create"
                    style="height: 38px;" onchange="updateLineCodeOptions()">
                    <option value="" disabled selected hidden>--Choice--</option>
                    <option value="INHOUSE">INHOUSE</option>
                    <option value="SUBCONT">SUBCONT</option>
                </select>
            </div>

            <!-- Label for Branch -->
            <label class="col-sm-auto col-form-label">
                <b>Branch</b>
            </label>
            <div class="col-sm-3 mb-3">
                <!-- Dropdown for Branch -->
                <br>
                <select name="branch_create" class="form-control form-control-sm" id="branch_create"
                    style="height: 38px; width: 100px;" onchange="updateLineCodeOptions()">
                    <option value="" disabled selected hidden>--Choice--</option>
                    <option value="1701">1701</option>
                    <option value="1702">1702</option>
                </select>
            </div>
        </div>

        <div class="form-group row align-items-center">
            <label for="line_code_create" class="col-sm-2 col-form-label"><b>Line Code</b></label>
            <div class="col-sm-3">
                <select name="line_code_create" class="form-control form-control-sm" id="line_code_create"
                    style="height: 38px;">
                    <!-- Options will be populated based on selection -->
                    <option value="" disabled selected hidden>--Choice--</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="itemcode_create" class="col-sm-2 col-form-label"><b>Itemcode</b></label>
            <div class="col-sm-4">
                <div class="input-group-append">
                    <input type="text" value="" name="itemcode_create"
                        class="form-control form-control-sm uppercase-input" id="itemcode_create" placeholder=""
                        style="height: 38px;" onchange="validasiItemcode()">
                </div>
                <p class="text-danger">Search Itemcode</p>
            </div>
        </div>
        <div class="form-group row">
            <label for="kanban_create" class="col-sm-2 col-form-label"><b>Kanban No</b></label>
            <div class="col-sm-4">
                <div class="input-group-append">
                    <input type="text" value="" name="kanban_create"
                        class="form-control form-control-sm uppercase-input" id="kanban_create" placeholder=""
                        style="height: 38px;" readonly>
                </div>
            </div>
        </div>
        <div class="form-group row align-items-center">
            <label for="partno_create" class="col-sm-2 col-form-label pr-0"><b>Part No</b></label>
            <div class="col-sm-3"> <!-- Reduced width for Part No -->
                <input type="text" value="" name="partno_create"
                    class="form-control form-control-sm uppercase-input" id="partno_create" placeholder=""
                    style="height: 38px;">
            </div>

            <label for="partname_create"><b>Part Name</b></label>
            <div class="col-sm-4"> <!-- Increased width for Part Name -->
                <input type="text" value="" name="partname_create"
                    class="form-control form-control-sm uppercase-input" id="partname_create" placeholder=""
                    style="height: 38px;">
            </div>
        </div>
        <div class="form-group row">
            <label for="part_type_create" class="col-sm-2 col-form-label"><b>Part Type</b></label>
            <div class="col-sm-3">
                <input type="text" value="" name="part_type_create"
                    class="form-control form-control-sm uppercase-input" id="part_type_create" placeholder=""
                    style="height: 38px;">
            </div>
        </div>
        <div class="form-group row">
            <label for="qty_create" class="col-sm-2 col-form-label"><b>Qty Kanban</b></label>
            <div class="col-sm-2">
                <input type="text" value="" name="qty_create"
                    class="form-control form-control-sm uppercase-input" id="qty_create" placeholder=""
                    style="height: 38px;" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            </div>
        </div>

        {{-- <div class="form-group row">
            <label for="cust_create" class="col-sm-2 col-form-label"><b>Customer</b></label>
            <div class="col-sm-3">
                <input type="text" value="" name="cust_create"
                    class="form-control form-control-sm uppercase-input" id="cust_create" placeholder=""
                    style="height: 38px;">
            </div>
        </div> --}}
        <div class="form-group row">
            <label for="cust_create" class="col-sm-2 col-form-label"><b>Customer</b></label>
            <div class="col-sm-2">
                <input type="text" value="" name="cust_create"
                    class="form-control form-control-sm uppercase-input" id="cust_create" placeholder=""
                    style="height: 38px;">
            </div>
            {{-- <label><b>Branch</b></label>
            <div class="col-3 mb-3 sm-2">
                <select name="branch_create" class="form-control form-control-sm" id="branch_create"
                    style="height: 38px;width:100px">
                    <option value="" disabled selected hidden>--Choice--</option>
                    <option value="1701">1701</option>
                    <option value="1702">1702</option>
                </select>
            </div> --}}

        </div>
        <div class="form-group row">
            <label for="kanban_type_create" class="col-sm-2 col-form-label"><b>Kanban Type</b></label>
            <div class="col-sm-2">
                <select name="kanban_type_create" class="form-control form-control-sm" id="kanban_type_create"
                    style="height: 38px;">
                    <option value="" disabled selected hidden>--Choice--</option>
                    <option value="F/G">F/G</option>
                    <option value="WIP">WIP</option>
                    <option value="R/M">R/M</option>
                </select>

            </div>
            <div class="colsm-4 mb-1">
                <label for="base_unit_create"><b>Base&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                        Unit</b></label>
            </div>
            <div class="col-3 mb-3 sm-2">

                <select name="base_unit_create" class="form-control form-control-sm" id="base_unit_create"
                    style="height: 38px;width:100px">
                    <option value="" disabled selected hidden>--Choice--</option>
                    <option value="PCE">PCE</option>
                    <option value="KG">KG</option>
                    <option value="SHE">SHE</option>
                </select>
            </div>

        </div>

</fieldset>
