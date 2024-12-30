 <form  id="form-stout-edit-header" method="post" action="javascript:void(0)" >
  @csrf
  @method('PUT')
  <input type="hidden" id="id_stout_edit_stout">
  <div class="form-row">
    <div class="col-1 mb-1">
        <label>Out No.</label>
    </div>
    <div class="col-2 mb-1">
        <input type="number" disabled   name="out_no" class="form-control form-control-sm" id="out_no_edit_stout" aria-describedby="" placeholder="">
    </div>
    <div class="col-1 mb-1">
        <input type="text"  value="HO" name="branch"  class="form-control form-control-sm" id="branch_edit_stout"  placeholder=""> 
    </div>

    <div class="col-1 mb-1">
        <input type="text" onkeydown="keyPressedSysWarehouseEdit(event)"  placeholder="ENTER"  id="types_edit_stout" name="types" class="form-control form-control-sm">
    </div>
    <div class="col-md-4 mb-1 align-right" >
        <label>Operator</label>
    </div>
    <div class="col-3 mb-1">
        <input class="form-control form-control-sm"  value="{{ Auth::user()->FullName }}"  name="operator" type="text" id="operator_edit_stout" disabled>
    </div>
   
</div>
<fieldset>	
    <div class="form-row">
        <div class="col-1 mb-1">
            <label>Refs No.</label>
        </div>
        <div class="col-4 mb-1">
            <input type="text" name="refs_no" autocomplete="off"   class="form-control form-control-sm" id="refs_no_edit_stout">
        </div>
        <div class="col-md-5 mb-1 align-right" >
            <label>Printed</label>
        </div>
        <div class="col-2 mb-1">
            <input class="form-control form-control-sm" name="printed" type="text" id="printed_edit_stout" disabled>
        </div>
    </div>  
    <div class="form-row">
        <div class="col-1 mb-1">
            <label>Period/Date.</label>
        </div>
        <div class="col-2 mb-1">
            <input class="form-control form-control-sm" " 
            type="text" name="period" readonly  id="period_edit_stout" ">
        </div>
        <div class="col-2 mb-1">
            <input type="text" class="form-control form-control-sm" disabled  name="written"   id="written_edit_stout" required>
        </div>
        <div class="col-md-5 mb-1 align-right" >
            <label>Voided</label>
        </div>
        <div class="col-2 mb-1">
            <input class="form-control form-control-sm" name="voided" type="text" id="voided_edit_stout" disabled> 
        </div>
    </div>  
    <div class="form-row">	                                                                                                                               
        <div class="col-1 mb-1">
            <label>Staff/Dept.</label>
        </div>
        <div class="col-2 mb-1">
            <input class="form-control form-control-sm" " 
            type="text" disabled  name="staff" id="staff_edit_stout" ">
        </div>
        <div class="col-2 mb-1">
            <input type="text" class="form-control form-control-sm" disabled  name="dept"  id="dept_edit_stout" required>
        </div>
        <div class="col-md-5 mb-1 align-right" >
            <label>Posted</label>
        </div>
        <div class="col-2 mb-1">
            <input class="form-control form-control-sm" name="posted" type="text" id="posted_edit_stout" disabled>
        </div>
  
    </div>
</fieldset>	        
<div class="form-row">
    <div class="col-1 mb-1">
        <label>Remark.</label>
    </div>

    <div class="col-4 mb-1">
        <input type="text" name="remark_header" autocomplete="off" class="form-control form-control-sm" id="remark_header_edit_stout" aria-describedby="" >
    </div>
</form>
    <div class="col-md-5 mb-1 align-right" >
        <label>Total Item</label>
    </div>
    <div class="col-2 mb-1">
        <input class="form-control form-control-sm" name="total_item" type="text" id="total_item_edit" disabled>
    </div>
 </div>  


