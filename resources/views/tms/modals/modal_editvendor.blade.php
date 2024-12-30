<div class="modal fade" id="modal_editvendor" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Vendor</h5>
                    <button type="button" class="close" id="close-vendor-modal" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form method="POST" id="editvendor_form" action="javascript:void(0)">
            <div class="modal-body">
                    {{ csrf_field() }}
                    <span id="edit-vendor-output">
                    <div class="form-row">
                        <div class="col-md-2 mb-3">
                            <input class="form-control" type="text" name="vendor-code" id="VENDCODE-edit" placeholder="Vendor Code" disabled>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2 mb-3">
                            <input class="form-control" type="text" name="vendor-type" id="INDUSTRY-edit" placeholder="Industry">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input class="form-control" type="text" name="vendor-name" id="COMPANY-edit" placeholder="Company Name">
                        </div>
                        <div class="col-md-4 mb-3">
                            <input class="form-control" type="text" name="vendor-contact" id="CONTACT-edit" placeholder="Contact Person">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <input class="form-control" type="text" name="vendor-address1" id="ADDRESS1-edit" placeholder="Address">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <input class="form-control" type="text" name="vendor-address2" id="ADDRESS2-edit" placeholder="Address detail">
                        </div> 
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <input class="form-control" type="text" name="vendor-address3" id="ADDRESS3-edit" placeholder="Address detail">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <input class="form-control" type="text" name="vendor-phone" id="PHONE-edit" placeholder="Phone">
                        </div> 
                        <div class="col-md-3 mb-3">
                            <input class="form-control" type="text" name="vendor-fax" id="FAX-edit" placeholder="Fax">
                        </div> 
                        <div class="col-md-3 mb-3">
                            <input class="form-control" type="text" name="vendor-hp" id="HP-edit" placeholder="Handphone">
                        </div> 
                        <div class="col-md-3 mb-3">
                            <input class="form-control" type="text" name="vendor-email" id="EMAIL-edit" placeholder="Email">
                        </div> 
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <input class="form-control" type="text" name="vendor-glap" id="GLAP-edit" placeholder="GLAP">
                        </div> 
                        <div class="col-md-4 mb-3">
                            <input class="form-control" type="text" name="vendor-npwp" id="NPWP-edit" placeholder="NPWP">
                        </div>
                        <div class="col-md-2 mb-3">
                            <input class="form-control" type="text" name="vendor-npwp" id="TERMOFPAY-edit" placeholder="Term of pay">
                        </div>
                        <div class="col-md-2 mb-3">
                            <input class="form-control" type="text" name="vendor-taxrate" id="TAXRATE-edit" placeholder="Tax rate">
                        </div>
                    </div>
                </span>
            </div> 
           
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success update" id="save-edit">Save</button>
            </div>

            </form>
        </div>
    </div>
</div>
