<fieldset class="form">
    <div class="modal-body">
        <div class="form-group row">
            <label for="chutter_create" class="col-sm-2 col-form-label"><b>Chutter Address</b></label>
            <div class="col-sm-4">
                <div class="input-group-append">
                    <input type="text" value="" name="chutter_create" onchange="validasiChutter()"
                        class="form-control form-control-sm uppercase-input" id="chutter_create" placeholder="">

                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="itemcode_create" class="col-sm-2 col-form-label"><b>Itemcode</b></label>
            <div class="col-sm-4">
                <div class="input-group-append">
                    <input type="text" value="" name="itemcode_create"
                        class="form-control form-control-sm uppercase-input" id="itemcode_create" placeholder="">
                    <button class="btn btn-danger" id="ekanbanparamButton">
                        <i class="fa fa-search pointer-events-none"></i></button>
                </div>
                <p class="text-danger">klik tombol untuk memilih</p>
            </div>
        </div>
        {{-- inputan hidden for value and validasi itemcode tablr --}}
        <input class="" type="hidden" name="item_code_check" id="item_code_check">
        <div class="form-group row">
            <label for="partno_create" class="col-sm-2 col-form-label"><b>Partn No</b></label>
            <div class="col-sm-4">
                <div class="input-group-append">
                    <input type="text" value="" name="partno_create"
                        class="form-control form-control-sm uppercase-input" id="partno_create" placeholder="" readonly>
                </div>
            </div>
            <div class="colsm-4 mb-1">
                <label><b>Part Name</b></label>
            </div>
            <div class="col-3 mb-3">
                <input type="text" value="" name="partname_create"
                    class="form-control form-control-sm uppercase-input" id="partname_create" placeholder="" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="part_type_create" class="col-sm-2 col-form-label"><b>Part Type</b></label>
            <div class="col-sm-4">
                <input type="text" value="" name="part_type_create"
                    class="form-control form-control-sm uppercase-input" id="part_type_create" placeholder="" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="lot_create" class="col-sm-2 col-form-label"><b>Lot</b></label>
            <div class="col-sm-4">
                <input type="text" value="" name="lot_create"
                    class="form-control form-control-sm uppercase-input" id="lot_create" placeholder="" readonly>
            </div>
        </div>
        <div class="form-group row align-items-center">
            <label for="stock_type_create" class="col-sm-2 col-form-label"><b>Stock Type</b></label>
            <div class="col-sm-4">
                <select name="stock_type_create" class="form-control form-control-sm " id="stock_type_create">
                    <option value="" disabled selected hidden>Pilih Stock Type</option>
                    <option value="F/G">F/G</option>
                    <option value="WIP">WIP</option>
                    <option value="R/M">R/M</option>
                </select>
            </div>
        </div>
        <div class="form-group row align-items-center">
            <label for="plant_create" class="col-sm-2 col-form-label"><b>Plant</b></label>
            <div class="col-sm-4">
                <select name="plant_create" class="form-control form-control-sm " id="plant_create">
                    <option value="" disabled selected hidden>Pilih Plant</option>
                    <option value="1701">1701</option>
                    <option value="1702">1702</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="min_create" class="col-sm-2 col-form-label"><b>Min</b></label>
            <div class="col-sm-2">
                <input type="text" value="" name="min_create" onchange="validasimultiplemin(this)"
                    class="form-control form-control-sm uppercase-input" id="min_create" placeholder="">
            </div>
            <div class="colsm-4 mb-1">
                <label><b>Max</b></label>
            </div>
            <div class="col-3 mb-3">
                <input type="text" value="" name="max_create"
                    class="form-control form-control-sm uppercase-input" onchange="validasimultiplemax(this)"
                    id="max_create" placeholder="">
            </div>

        </div>
        <input type="hidden" value="" name="cust_code" id="cust_code" placeholder="">
        <input type="hidden" value="" name="qty_kanban_min" id="qty_kanban_min" placeholder="">
        <input type="hidden" value="" name="qty_kanban_max" id="qty_kanban_max" placeholder="">
    </div>
    {{-- <div class="form-group row">
            <label for="desc_create" class="col-sm-2 col-form-label"><b>Kategori</b></label>
            <div class="col-sm-2">
                <input type="text" value="" name="partno" class="form-control form-control-sm uppercase-input"
                    id="partno_create" placeholder="">
            </div>
        </div> --}}
</fieldset>
