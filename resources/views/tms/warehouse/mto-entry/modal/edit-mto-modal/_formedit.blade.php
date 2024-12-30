  <div class="form-row">
        <div class="col-1 mb-1">
            <label>MTO no.</label>
        </div>
        <div class="col-2 mb-1">
           {{-- <input type="hidden" id="id_mto_edit"> --}}
           <input type="hidden" id="mto_no_edit1" name="mto_no">
                <input type="number" readonly   name="mto_no" class="form-control form-control-sm" id="mto_no_edit" aria-describedby="" placeholder="">
        </div>
        <div class="col-1 mb-1">
            <input type="text"  value="HO" name="branch" readonly class="form-control form-control-sm" id="branch_edit"  placeholder="">
        </div>
        <div class="col-1 mb-1">
            <input type="text" class="form-control form-control-sm" readonly name="warehouse" value="90" id="warehouse_edit">
        </div>
    
        <div class="col-1 mb-1">
            <input type="text" class="form-control form-control-sm" name="types"  readonly id="types_edit"  >
        </div>
        <div class="col-md-3 mb-1 align-right" >
            <label>Staff</label>
        </div>
        <div class="col-3 mb-1">
            <input class="form-control form-control-sm" value="{{ Auth::user()->FullName }}" name="staff" type="text" id="staff_edit" readonly>
        </div>
       
    </div>
    <fieldset>	
        <div class="form-row">
            <div class="col-1 mb-1">
                <label>Ref No.</label>
            </div>
            <div class="col-4 mb-1">
                <input type="text" name="ref_no"   class="form-control form-control-sm" id="ref_no_edit">
            </div>
            <div class="col-md-5 mb-1 align-right" >
                <label>Printed</label>
            </div>
            <div class="col-2 mb-1">
                <input class="form-control form-control-sm" name="" type="text" id="printed_edit" readonly>
            </div>
        </div>  
        <div class="form-row">
            <div class="col-1 mb-1">
                <label>Period/Date.</label>
            </div>
            <div class="col-2 mb-1">
                <input readonly class="form-control form-control-sm"  value="" 
                type="text" name="period" id="period_edit">
            </div>
            <div class="col-2 mb-1">
                <input type="text"  class="form-control form-control-sm" readonly  name="written" value="" id="written_edit">
            </div>
            <div class="col-md-5 mb-1 align-right" >
                <label>Voided</label>
            </div>
            <div class="col-2 mb-1">
                <input class="form-control form-control-sm" name="voided" type="text" id="voided_edit_mto" readonly>
            </div>
        </div>  
        <div class="form-row">	                                                                                                                               
            <div class="col-1 mb-1">
                <label style="font-size: 13px;">Item/Part no.</label>
            </div>
            <div class="col-2 mb-1">
                <div class="input-group">
                    {{-- <input type='hidden' name='fin_code' --}}
                    <input class="form-control form-control-sm" readonly name="fin_code" type="text" id="ITEMCODE" onchange="validateCreateMto()" placeholder="Press Enter">
                    <span class="input-group-btn">
                        <button type="button" disabled id="btnPopUp2" class="btn btn-info btn-xs" data-toggle="modal"><i class="fa fa-search"></i></button>
                    </span><br>
                    {{-- <i style="color: red; font-size: 11px;">(*) Press Enter/Search Button</i> --}}
                 </div>
            </div>
            <div class="col-2 mb-1">
                <input type="text" name="part_no" readonly class="form-control form-control-sm" autocomplete="off" onchange="validateCreateMto()" id="PART_NO" >
            </div>
            <div class="col-3 mb-1">
                <input type="text" name="descript" readonly class="form-control form-control-sm" autocomplete="off" onchange="validateCreateMto()" id="DESCRIPT">
            </div>
            <div class="col-md-2 mb-1 align-right" >
                <label>Posted</label>
            </div>
            <div class="col-2 mb-1">
                <input class="form-control form-control-sm" name="posted" type="text" id="posted_edit" readonly>
            </div>
      
        </div>
    
        <div class="form-row">	                                                                                                                               
            <div class="col-1 mb-1">
                <label>Qty IN/NG</label>
            </div>
            <div class="col-2 mb-1">
                <input type="number" name="quantity" 
                value="0"  class="form-control form-control-sm" 
                id="quantity_edit" onchange="qtyChildEdit()" autocomplete="off"  aria-describedby="" placeholder="Qty IN">
            </div>
            <div class="col-2 mb-1">
                <input type="number" name="qty_ng"  class="form-control form-control-sm" id="qty_ng_edit" 
                     onchange="qtyChildNgEdit()" aria-describedby="" placeholder="Qty NG">
            </div>
            <div class="col-1 mb-1">
                <input type="text" name="unit"  readonly onchange="validateCreateMto()"  autocomplete="off" class="form-control form-control-sm" id="unit_edit">
            </div>
      
        </div>
    </fieldset>	        
    <div class="form-row">
        <div class="col-1 mb-1">
            <label>Remark.</label>
        </div>
        <div class="col-6 mb-1">
            <input type="text"  name="remark" autocomplete="off" class="form-control form-control-sm" id="remark_edit" aria-describedby="" placeholder="Remark">
        </div>
     </div>  
