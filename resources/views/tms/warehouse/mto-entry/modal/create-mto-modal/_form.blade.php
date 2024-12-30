

<div class="form-row">
    <div class="col-1 mb-1">
        <label>MTO no.</label>
    </div>
    <div class="col-2 mb-1">
        <input type="number" disabled placeholder="MTO No"   name="mto_no" class="form-control form-control-sm" id="mto_no_create" aria-describedby="" placeholder="">
    </div>
    <div class="col-1 mb-1">
        <input type="text"  value="HO" name="branch" disabled class="form-control form-control-sm" id="branch_create"  placeholder="">
    </div>
    <div class="col-1 mb-1">
        <input type="text" class="form-control form-control-sm" name="warehouse" value="90" id="warehouse_create">
    </div>

    <div class="col-1 mb-1">
        <select name="types" autocomplete="off"   class="form-control form-control-sm"    id="types_create"  required>
            <option value=""></option>
            <option value="91">91 PRESSING</option>
            <option value="92">92 WELDING</option>
            <option value="93">93 SPOT WELDING</option>
            <option value="94">94 ASSY</option>
            <option value="D5">D5 PROCESS AT D5</option>
        </select>
    </div>
    <div class="col-md-3 mb-1 align-right" >
        <label>Staff</label>
    </div>
    <div class="col-3 mb-1">
        <input class="form-control form-control-sm" value="{{ Auth::user()->FullName }}" name="staff" type="text" id="staff_create" disabled>
    </div>
   
</div>
<fieldset>	
    <div class="form-row">
        <div class="col-1 mb-1">
            <label>Ref No.</label>
        </div>
        <div class="col-4 mb-1">
            <input type="text" name="ref_no" autocomplete="off"   class="form-control form-control-sm" id="ref_no_create">
        </div>
        <div class="col-md-5 mb-1 align-right" >
            <label>Printed</label>
        </div>
        <div class="col-2 mb-1">
            <input class="form-control form-control-sm" name="printed" type="text" id="printed_create" disabled>
        </div>
    </div>  
    <div class="form-row">
        <div class="col-1 mb-1">
            <label>Period/Date.</label>
        </div>
        <div class="col-2 mb-1">
            <input class="form-control form-control-sm" readonly id="setperiod" 
            type="text" name="period" id="period_create">
        </div>
        <div class="col-2 mb-1">
            <input type="text" class="form-control form-control-sm  datepicker" id="setdate"   name="written" id="written_create">
        </div>
        <div class="col-md-5 mb-1 align-right" >
            <label>Voided</label>
        </div>
        <div class="col-2 mb-1">
            <input class="form-control form-control-sm" name="voided" type="text" id="voided_create_mto" disabled>
        </div>
    </div>  
    <div class="form-row">	                                                                                                                               
        <div class="col-1 mb-1">
            <label style="font-size: 13px;">Item/Part no.</label>
        </div>
        {{--    onkeydown="keyPressed(event)" --}}
        <div class="col-2 mb-1">
            <div class="input-group">
                <input class="form-control form-control-sm" name="fin_code" type="text"   
              autocomplete="off" id="itemcode_create" readonly placeholder="Cari Itemcode">
                <span class="input-group-btn">
                    <button type="button" id="btnPopUp" onclick="clearSearch()" class="btn btn-info btn-xs" data-toggle="modal" data-target="#mtoModal"><i class="fa fa-search"></i></button>
                </span><br>
                {{-- <i style="color: red; font-size: 11px;">(*) Press Enter/Search Button</i> --}}
             </div>
        </div>
        <div class="col-2 mb-1">
            <input type="text" name="part_no" readonly class="form-control form-control-sm" autocomplete="off" onchange="validateCreateMto()" id="part_no_create" >
        </div>
        <div class="col-3 mb-1">
            <input type="text" name="descript" readonly class="form-control form-control-sm" autocomplete="off" onchange="validateCreateMto()" id="descript_create">
        </div>
        <div class="col-md-2 mb-1 align-right" >
            <label>Posted</label>
        </div>
        <div class="col-2 mb-1">
            <input class="form-control form-control-sm" name="posted" type="text" id="posted_create" disabled>
        </div>
  
    </div>

    <div class="form-row">	                                                                                                                               
        <div class="col-1 mb-1">
            <label>Qty IN/NG</label>
        </div>
        <div class="col-2 mb-1">
            <input type="number" name="quantity" 
            value="0"  class="form-control form-control-sm" 
            id="quantity_create" onchange="qtyChild()"  aria-describedby="" placeholder="Qty IN">
        </div>
        <div class="col-2 mb-1">
            <input type="number" name="qty_ng"  class="form-control form-control-sm" id="qty_ng" 
               value="0"  onchange="qtyChildNg()" aria-describedby="" placeholder="Qty NG">
        </div>
        <div class="col-1 mb-1">
            <input type="text" name="unit"  disabled onchange="validateCreateMto()"  autocomplete="off" class="form-control form-control-sm" id="unit_create">
        </div>
  
    </div>
</fieldset>	        
<div class="form-row">
    <div class="col-1 mb-1">
        <label>Remark.</label>
    </div>
    <div class="col-6 mb-1">
        <input type="text" name="remark" autocomplete="off" class="form-control form-control-sm" id="remark_create" aria-describedby="" placeholder="Remark">
    </div>
 </div>  



