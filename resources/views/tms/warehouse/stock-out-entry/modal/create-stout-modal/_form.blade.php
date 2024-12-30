<div class="form-row">
        <div class="col-1 mb-1">
            <label>Out No.</label>
        </div>
        <div class="col-2 mb-1">
            <input type="number" disabled   name="out_no" class="form-control form-control-sm" id="out_no_create_stout" aria-describedby="" placeholder="">
        </div>
        <div class="col-1 mb-1">
            <input type="text"  value="HO" name="branch"  class="form-control form-control-sm" id="branch_create_stout"  placeholder=""> 
        </div>

        <div class="col-1 mb-1">
            <input type="text" onkeydown="keyPressedSysWarehouse(event)" placeholder="ENTER"  id="types_create_stout" name="types" class="form-control form-control-sm">
        </div>
        <div class="col-md-4 mb-1 align-right" >
            <label>Operator</label>
        </div>
        <div class="col-3 mb-1">
            <input class="form-control form-control-sm"  value="{{ Auth::user()->FullName }}"  name="operator" type="text" id="operator_create_stout" disabled>
        </div>
       
    </div>
    <fieldset>	
        <div class="form-row">
            <div class="col-1 mb-1">
                <label>Refs No.</label>
            </div>
            <div class="col-4 mb-1">
                <input type="text" name="refs_no" autocomplete="off"   class="form-control form-control-sm" id="refs_no_create_stout">
            </div>
            <div class="col-md-5 mb-1 align-right" >
                <label>Printed</label>
            </div>
            <div class="col-2 mb-1">
                <input class="form-control form-control-sm" name="printed" type="text" id="printed_create_stout" disabled>
            </div>
        </div>  
        <div class="form-row">
            <div class="col-1 mb-1">
                <label>Period/Date.</label>
            </div>
            <div class="col-2 mb-1">
                <input class="form-control form-control-sm" " 
                type="text" name="period" readonly value="{{ $getDate1 }}" id="period_create_stout" ">
            </div>
            <div class="col-2 mb-1">
                <input type="text" class="form-control form-control-sm datepicker"   name="written"   id="written_create_stout" required>
            </div>
            <div class="col-md-5 mb-1 align-right" >
                <label>Voided</label>
            </div>
            <div class="col-2 mb-1">
                <input class="form-control form-control-sm" name="voided" type="text" id="voided_create_stout" disabled> 
            </div>
        </div>  
        <div class="form-row">	                                                                                                                               
            <div class="col-1 mb-1">
                <label>Staff/Dept.</label>
            </div>
            <div class="col-2 mb-1">
                <input class="form-control form-control-sm" " 
                type="text" disabled value="{{ Auth::user()->FullName }}" name="staff" id="staff_create_stout" ">
            </div>
            <div class="col-2 mb-1">
                <input type="text" class="form-control form-control-sm" disabled  name="dept"  id="dept_create_stout" required>
            </div>
            <div class="col-md-5 mb-1 align-right" >
                <label>Posted</label>
            </div>
            <div class="col-2 mb-1">
                <input class="form-control form-control-sm" name="posted" type="text" id="posted_create_stout" disabled>
            </div>
      
        </div>
    </fieldset>	        
    <div class="form-row">
        <div class="col-1 mb-1">
            <label>Remark.</label>
        </div>
        <div class="col-4 mb-1">
            <input type="text" name="remark_header" autocomplete="off" class="form-control form-control-sm" id="remark_header_create_stout" aria-describedby="" >
        </div>
        <div class="col-md-5 mb-1 align-right" >
            <label>Total Item</label>
        </div>
        <div class="col-2 mb-1">
            <input class="form-control form-control-sm" value="0" name="total_item" type="text" id="total_item" disabled>
        </div>
     </div>  

    

