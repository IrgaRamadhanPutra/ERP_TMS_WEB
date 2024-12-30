<fieldset class="form">
    <div class="modal-body">
        <div class="form-group row">
            <label for="chutter_create" class="col-sm-2 col-form-label"><b>Chutter Address</b></label>
            <div class="col-sm-3">
                <div class="input-group-append">
                    <input type="text" value="" name="chutter_create"
                        class="form-control form-control-sm uppercase-input" id="chutter_create" placeholder="">

                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="itemcode_create" class="col-sm-2 col-form-label"><b>Itemcode</b></label>
            <div class="col-sm-3">
                <input type="text" value="" name="itemcode_create"
                    class="form-control form-control-sm uppercase-input" id="itemcode_create" placeholder=""
                    onchange="validasiItemcode()">
                {{-- <p class="text-danger">klik tombol untuk memilih</p> --}}
                <span id="error_message"
                    style="display: none; color: rgb(228, 56, 56);  padding: 5px; margin-top: 5px; display: none;">Itemcode
                    Already Exist.</span>
            </div>
        </div>

        <div class="form-group row">
            <label for="partno_create" class="col-sm-2 col-form-label"><b>Part No</b></label>
            <div class="col-sm-3">
                <div class="input-group-append">
                    <input type="text" value="" name="partno_create"
                        class="form-control form-control-sm uppercase-input" id="partno_create" placeholder="">
                </div>
            </div>
            <div class="colsm-3 mb-1">
                <label><b>Part Name</b></label>
            </div>
            <div class="col-4 mb-3">
                <input type="text" value="" name="partname_create"
                    class="form-control form-control-sm uppercase-input" id="partname_create" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label for="part_type_create" class="col-sm-2 col-form-label"><b>Part Type</b></label>
            <div class="col-sm-3">
                <input type="text" value="" name="part_type_create"
                    class="form-control form-control-sm uppercase-input" id="part_type_create" placeholder="">
            </div>
        </div>
        {{-- <div class="form-group row">
            <label for="lot_create" class="col-sm-2 col-form-label"><b>Lot</b></label>
            <div class="col-sm-4">
                <input type="text" value="" name="lot_create"
                    class="form-control form-control-sm uppercase-input" id="lot_create" placeholder="">
            </div>
        </div> --}}
        <div class="form-group row align-items-center">
            <label for="stock_type_create" class="col-sm-2 col-form-label"><b>Stock Type</b></label>
            <div class="col-sm-2">
                <input type="text" name="stock_type_create" class="form-control form-control-sm"
                    id="stock_type_create" value="R/M" readonly>
            </div>
        </div>
        <div class="form-group row align-items-center">
            <label for="unit_create" class="col-sm-2 col-form-label"><b>Unit</b></label>
            <div class="col-sm-2">
                <select name="unit_create" class="form-control form-control-sm" id="unit_create">
                    <option value="" selected>Choice unit</option> <!-- Dihapus disabled dan hidden -->
                    <option value="PCE">PCE</option>
                    <option value="SHEET">SHEET</option>
                    <option value="KG">KG</option>
                </select>
            </div>
        </div>
        <div class="form-group row align-items-center">
            <label for="plant_create" class="col-sm-2 col-form-label"><b>Plant</b></label>
            <div class="col-sm-2">
                <select name="plant_create" class="form-control form-control-sm" id="plant_create">
                    <option value="" selected>Choice Plant</option>
                    <option value="1701">1701</option>
                    <option value="1702">1702</option>
                </select>
            </div>

            <label for="cust_code_create"><b>Cust<br>Code</b></label>
            <div class="col-sm-2">
                <select name="cust_code_create" class="form-control form-control-sm" id="cust_code_create">
                    <option value="" selected>Choice</option>
                    <option value="CIKARANG">CIKARANG</option>
                    <option value="CIREBON">CIREBON</option>
                    <option value="PBM">PBM</option>
                </select>
            </div>
        </div>



        <div class="form-group row">
            <label for="min_create" class="col-sm-2 col-form-label"><b>Min</b></label>
            <div class="col-sm-2">
                <input type="text" value="" name="min_create"
                    class="form-control form-control-sm uppercase-input" id="min_create" placeholder=""
                    oninput="validateNumericInput(this)">
            </div>
            <div class="colsm-4 mb-1">
                <label><b>Max</b></label>
            </div>
            <div class="col-2 mb-2">
                <input type="text" value="" name="max_create"
                    class="form-control form-control-sm uppercase-input" id="max_create" placeholder=""
                    oninput="validateNumericInput(this)">
            </div>
        </div>

    </div>
    {{-- <div class="form-group row">
            <label for="desc_create" class="col-sm-2 col-form-label"><b>Kategori</b></label>
            <div class="col-sm-2">
                <input type="text" value="" name="partno" class="form-control form-control-sm uppercase-input"
                    id="partno_create" placeholder="">
            </div>
        </div> --}}
</fieldset>
