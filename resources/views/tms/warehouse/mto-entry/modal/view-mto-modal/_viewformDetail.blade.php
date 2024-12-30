
    <div class="form-row">
        <div class="col-1 mb-1">
            <label>MTO no.</label>
        </div>
        <div class="col-2 mb-1">
            <input type="number" disabled placeholder="MTO No"   name="mto_no" class="form-control form-control-sm" id="mto_no_view" aria-describedby="" placeholder="">
        </div>
        <div class="col-1 mb-1">
            <input type="text"  value="HO" name="branch" disabled class="form-control form-control-sm" id="branch_view"  placeholder="">
        </div>
        <div class="col-1 mb-1">
            <input type="text" class="form-control form-control-sm" disabled name="warehouse" value="90" id="warehouse_view">
        </div>
    
        <div class="col-1 mb-1">
            <input type="text" class="form-control form-control-sm" name="types"  disabled id="types_view"  >
        </div>
        <div class="col-md-3 mb-1 align-right" >
            <label>Staff</label>
        </div>
        <div class="col-3 mb-1">
            <input class="form-control form-control-sm" value="{{ Auth::user()->FullName }}" name="staff" type="text" id="staff_view" disabled>
        </div>
       
    </div>
    <fieldset>	
        <div class="form-row">
            <div class="col-1 mb-1">
                <label>Ref No.</label>
            </div>
            <div class="col-4 mb-1">
                <input type="text" name="ref_no" disabled   class="form-control form-control-sm" id="ref_no_view">
            </div>
            <div class="col-md-5 mb-1 align-right" >
                <label>Printed</label>
            </div>
            <div class="col-2 mb-1">
                <input class="form-control form-control-sm" name="printed" type="text" id="printed_view" disabled>
            </div>
        </div>  
        <div class="form-row">
            <div class="col-1 mb-1">
                <label>Period/Date.</label>
            </div>
            <div class="col-2 mb-1">
                <input disabled class="form-control form-control-sm"  value="{{ $getDate1 }}" 
                type="text" name="period" id="period_view">
            </div>
            <div class="col-2 mb-1">
                <input type="text"  class="form-control form-control-sm" disabled  name="written" value="{{ $getDate }}" id="written_view">
            </div>
            <div class="col-md-5 mb-1 align-right" >
                <label>Voided</label>
            </div>
            <div class="col-2 mb-1">
                <input class="form-control form-control-sm" name="voided" type="text" id="voided_voided" disabled>
            </div>
        </div>  
        <div class="form-row">	                                                                                                                               
            <div class="col-1 mb-1">
                <label style="font-size: 13px;">Item/Part no.</label>
            </div>
            <div class="col-2 mb-1">
                <div class="input-group">
                    <input class="form-control form-control-sm" name="fin_code" type="text"   
                    onkeydown="keyPressed(event)" disabled autocomplete="off" id="itemcode_view" onchange="validateCreateMto()" placeholder="Press Enter">
                    <span class="input-group-btn">
                        <button type="button" disabled id="btnPopUp3" class="btn btn-info btn-xs" data-toggle="modal"><i class="fa fa-search"></i></button>
                    </span><br>
                    {{-- <i style="color: red; font-size: 11px;">(*) Press Enter/Search Button</i> --}}
                 </div>
            </div>
            <div class="col-2 mb-1">
                <input type="text" name="part_no" readonly class="form-control form-control-sm" autocomplete="off" onchange="validateCreateMto()" id="part_no_view" >
            </div>
            <div class="col-3 mb-1">
                <input type="text" name="descript" readonly class="form-control form-control-sm" autocomplete="off" onchange="validateCreateMto()" id="descript_view">
            </div>
            <div class="col-md-2 mb-1 align-right" >
                <label>Posted</label>
            </div>
            <div class="col-2 mb-1">
                <input class="form-control form-control-sm" name="posted" type="text" id="posted_view" disabled>
            </div>
      
        </div>
    
        <div class="form-row">	                                                                                                                               
            <div class="col-1 mb-1">
                <label>Qty IN/NG</label>
            </div>
            <div class="col-2 mb-1">
                <input type="number" name="" 
                value="0"  class="form-control form-control-sm" 
                id="quantity_view" onchange="qtyChild()" disabled  aria-describedby="" placeholder="Qty IN">
            </div>
            <div class="col-2 mb-1">
                <input type="number" name="" disabled class="form-control form-control-sm" id="qty_ng_view" 
                   value="0"  onchange="qtyChildNg()" aria-describedby="" placeholder="Qty NG">
            </div>
            <div class="col-1 mb-1">
                <input type="text" name="unit"  disabled onchange="validateCreateMto()"  autocomplete="off" class="form-control form-control-sm" id="unit_view">
            </div>
      
        </div>
    </fieldset>	        
    <div class="form-row">
        <div class="col-1 mb-1">
            <label>Remark.</label>
        </div>
        <div class="col-6 mb-1">
            <input type="text" disabled name="remark" autocomplete="off" class="form-control form-control-sm" id="remark_view" aria-describedby="" placeholder="Remark">
        </div>
     </div>  
