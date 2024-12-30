<div class="modal fade bd-example-modal-xs modalEditDetail" style="z-index: 1041" tabindex="-1" id="modalEditDetail"   role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title-detail" ></h4>
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body edit-modal-detail">
                        <form  id="form-edit-detail-stout" method="post" action="javascript:void(0)">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="id_stout_editdetail2" name="id_stout">
                            <div class="form-group row">
                                <label for="itemcode_editdetail"  class="col-2 col-form-label">Itemcode</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                    <input class="form-control form-control-sm" type="text" name="itemcode"  onkeydown="keyPressedEdit(event)"  id="itemcode_editdetail2">
                                    <span class="input-group-btn">
                                      <button type="button" id="btnPopUpStout3" class="btn btn-info btn-xs" data-toggle="modal" data-target="#stoutModal3"><i class="fa fa-search"></i></button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="part_no_editdetail2"   class="col-2 col-form-label">Part No.</label>
                                <div class="col-10">
                                    <input class="form-control form-control-sm" readonly  type="text" name="part_no"  id="part_no_editdetail2">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="descript_editdetail2"   class="col-2 col-form-label">Description</label>
                                <div class="col-10">
                                    <input class="form-control form-control-sm" readonly type="text" name="descript"  id="descript_editdetail2">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fac_unit_editdetail2"  class="col-2 col-form-label">Fac Unit</label>
                                <div class="col-10">
                                    <input class="form-control form-control-sm" readonly type="text" name="fac_unit"  id="fac_unit_editdetail2">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fac_qty_editdetail2"  class="col-2 col-form-label">Fac Qty</label>
                                <div class="col-10">
                                    <input class="form-control form-control-sm" onchange="autoFillJumlahQtyKaliEdit()" type="text" name="fac_qty"  id="fac_qty_editdetail2">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="factor_editdetail2"  class="col-2 col-form-label">Factor</label>
                                <div class="col-10">
                                    <input class="form-control form-control-sm" readonly type="text" name="factor"  id="factor_editdetail2">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="unit_editdetail2"  class="col-2 col-form-label">Unit</label>
                                <div class="col-10">
                                    <input class="form-control form-control-sm" type="text" name="unit" readonly  id="unit_editdetail2">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="qty_editdetail2"  class="col-2 col-form-label">Qty</label>
                                <div class="col-10">
                                    <input class="form-control form-control-sm" readonly onchange="autoFillJumlahQtyBagiEdit()" name="quantity" type="text" id="qty_editdetail2">
                                </div>
                            </div>
                               <div class="form-group row">
                                <label for="remark_editdetail2"  class="col-2 col-form-label">Remark</label>
                                <div class="col-10">
                                    <input class="form-control form-control-sm" name="remark_detail" type="text" id="remark_detail_editdetail2">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-info " data-dismiss="modal">Close</button>
                    <button type="button"  class="btn btn-sm btn-info btn-sm editItemcode" ><i class="ti-check"></i> Save</button>
                </div>
  
            </form>
            
        </div>
    </div>
</div>
</div>
</div>
</div>
