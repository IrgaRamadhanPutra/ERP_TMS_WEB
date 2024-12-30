<fieldset class="form">
    <div class="modal-body">
        <div class="form-group row">
            <label for="chutter_edit" class="col-sm-2 col-form-label"><b>Chutter Address</b></label>
            <div class="col-sm-4">
                <div class="input-group-append">
                    <input type="text" value="" name="chutter_edit"
                        class="form-control form-control-sm uppercase-input" id="chutter_edit" placeholder="">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="itemcode_edit" class="col-sm-2 col-form-label"><b>Itemcode</b></label>
            <div class="col-sm-4">
                <div class="input-group-append">
                    <input type="text" value="" name="itemcode_edit"
                        class="form-control form-control-sm uppercase-input" id="itemcode_edit" placeholder="" readonly>
                </div>
                {{-- <p class="text-danger">klik tombol untuk memilih</p> --}}
            </div>
        </div>
        <div class="form-group row">
            <label for="partno_edit" class="col-sm-2 col-form-label"><b>Partn No</b></label>
            <div class="col-sm-4">
                <div class="input-group-append">
                    <input type="text" value="" name="partno_edit"
                        class="form-control form-control-sm uppercase-input" id="partno_edit" placeholder="" readonly>
                </div>
            </div>
            <div class="colsm-4 mb-1">
                <label><b>Part Name</b></label>
            </div>
            <div class="col-3 mb-3">
                <input type="text" value="" name="partname_edit"
                    class="form-control form-control-sm uppercase-input" id="partname_edit" placeholder="" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="part_type_edit" class="col-sm-2 col-form-label"><b>Part Type</b></label>
            <div class="col-sm-4">
                <input type="text" value="" name="part_type_edit"
                    class="form-control form-control-sm uppercase-input" id="part_type_edit" placeholder="" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="lot_edit" class="col-sm-2 col-form-label"><b>Lot</b></label>
            <div class="col-sm-4">
                <input type="text" value="" name="lot_edit"
                    class="form-control form-control-sm uppercase-input" id="lot_edit" placeholder="" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="stock_type_edit" class="col-sm-2 col-form-label"><b>Stock Type</b></label>
            <div class="col-sm-4">
                <input type="text" value="" name="stock_type_edit"
                    class="form-control form-control-sm uppercase-input" id="stock_type_edit" placeholder="" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="plant_edit" class="col-sm-2 col-form-label"><b>Plant</b></label>
            <div class="col-sm-4">
                <input type="text" value="" name="plant_edit"
                    class="form-control form-control-sm uppercase-input" id="plant_edit" placeholder="" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="stock_edit" class="col-sm-2 col-form-label"><b>Stock</b></label>
            <div class="col-sm-4">
                <input type="text" value="" name="stock_edit"
                    class="form-control form-control-sm uppercase-input" id="stock_edit" placeholder="" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="min_edit" class="col-sm-2 col-form-label"><b>Min</b></label>
            <div class="col-sm-2">
                <input type="text" value="" name="min_edit" onchange="validasiMultipleminedit(this)"
                    class="form-control form-control-sm uppercase-input" id="min_edit" placeholder="">
            </div>
            <div class="colsm-4 mb-1">
                <label><b>Max</b></label>
            </div>
            <div class="col-3 mb-3">
                <input type="text" value="" name="max_edit" onchange="validasimultiplemaxedit(this)"
                    class="form-control form-control-sm uppercase-input" id="max_edit" placeholder="">
            </div>
        </div>
        <input type="hidden" value="" name="cust_code" id="cust_code" placeholder="">
        <input type="hidden" value="" name="qty_kanban_min_edit" id="qty_kanban_min_edit" placeholder="">
        <input type="hidden" value="" name="qty_kanban_max" id="qty_kanban_max_edit" placeholder="">
    </div>
    {{-- <div class="form-group row">
            <label for="desc_edit" class="col-sm-2 col-form-label"><b>Kategori</b></label>
            <div class="col-sm-2">
                <input type="text" value="" name="partno" class="form-control form-control-sm uppercase-input"
                    id="partno_edit" placeholder="">
            </div>
        </div> --}}
</fieldset>
