<div class="modal fade" id="modal_entryvendor" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-vendor-modal"></h5>
                    <button type="button" class="close" id="close-vendor-modal" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form method="POST"  id="addvendor_form">
            <input type="hidden" name="vendor_id" id="vendor_id" value="vendor_id">
            <div class="modal-body">
                    {{ csrf_field() }}
                    <span id="form_output">
                    <div class="form-row">
                        <div class="col-md-2 mb-3">
                            <label for="vendor-code" class="col-form-label">Vendor Code</label>
                            <input class="form-control" type="text" name="VENDCODE" id="VENDCODE">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2 mb-3">
                            <label for="vendor-type" class="col-form-label">Industry Type</label>
                            <input class="form-control" type="text" name="INDUSTRY" id="INDUSTRY">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="vendor-name" class="col-form-label">Company Name</label>
                            <input class="form-control" type="text" name="COMPANY" id="COMPANY">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="vendor-contact" class="col-form-label">Contact Person</label>
                            <input class="form-control" type="text" name="CONTACT" id="CONTACT">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="vendor-address1" class="col-form-label">Address</label>
                            <input class="form-control" type="text" name="ADDRESS1" id="ADDRESS1">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="vendor-address2" class="col-form-label"></label>
                            <input class="form-control" type="text" name="ADDRESS2" id="ADDRESS2">
                        </div> 
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="vendor-address3" class="col-form-label"></label>
                            <input class="form-control" type="text" name="ADDRESS3" id="ADDRESS3">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="vendor-phone" class="col-form-label">Phone</label>
                            <input class="form-control" type="text" name="PHONE" id="PHONE">
                        </div> 
                        <div class="col-md-3 mb-3">
                            <label for="vendor-fax" class="col-form-label">Fax</label>
                            <input class="form-control" type="text" name="FAX" id="FAX">
                        </div> 
                        <div class="col-md-3 mb-3">
                            <label for="vendor-hp" class="col-form-label">Handphone</label>
                            <input class="form-control" type="text" name="HP" id="HP">
                        </div> 
                        <div class="col-md-3 mb-3">
                            <label for="vendor-email" class="col-form-label">Email</label>
                            <input class="form-control" type="text" name="EMAIL" id="EMAIL">
                        </div> 
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="vendor-glap" class="col-form-label">GLAP</label>
                            <input class="form-control" type="text" name="GLAP" id="GLAP">
                        </div> 
                        <div class="col-md-4 mb-3">
                            <label for="vendor-npwp" class="col-form-label">NPWP</label>
                            <input class="form-control" type="text" name="NPWP" id="NPWP">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="vendor-top" class="col-form-label">Term Of Pay</label>
                            <input class="form-control" type="text" name="TERMOFPAY" id="TERMOFPAY">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="vendor-taxrate" class="col-form-label">Tax Rate</label>
                            <input class="form-control" type="text" name="TAXRATE" id="TAXRATE">
                        </div>
                    </div>
                </span>
            </div> 
           
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="hidden" name="button_action" id="button_action" value="insert" />
                <input type="submit" name="submit" id="save-button" value="Add" class="btn btn-primary button_text" />
            </div>

            </form>
        </div>
    </div>
</div>
