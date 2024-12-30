<div class="modal fade-out bd-example-modal-lg modalVoidmaster" tabindex="-1" id="ModalVoidmaster" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark">
                <h4 class="modal-title"> Form Master Min Max --Void--</h4>

                {{-- <button type="button" class="close" data-dismiss="modal" onclick="clear_value_create_page()"><span>&times;</span></button> --}}
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body">
                        <form id="form-master-void" method="post" action="javascript:void(0)">
                            @csrf
                            @method('POST')
                            <input type="hidden" id="id_master_void" name="id_master">
                            <div class="form-group row">
                                <label for="type" class="col-2 col-form-label">Type</label>
                                <div class="col-9">
                                    <input type="text" disabled value="VOID" name="void"
                                        class="form-control form-control-sm" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="number" class="col-2 col-form-label">Code</label>
                                <div class="col-9">
                                    <input type="text" disabled name="code_void"
                                        class="form-control form-control-sm code_void" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="exception_note" class="col-2 col-form-label">Exception Note</label>
                                <div class="col-9">
                                    <input type="text" autocomplete="off" name="note" id="not_void"
                                        class="form-control form-control-sm" placeholder="">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info btn-flat btn-sm "
                                    data-dismiss="modal">Cancel</button>
                                <button type="button" id="ok-button" disabled
                                    class="btn btn-info btn-flat btn-sm void_submit"><i class="ti-check"></i>
                                    Ok</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        $(document).on('click', '.voided', function(e) {
            e.preventDefault();
            var id = $(this).attr('row-id');
            var code = $(this).attr('data-id');

            $('#id_master_void').val(id);
            // $('.modal-title').html('Master Data Pointsheet (VOID)')
            $('.code_void').val(code);
            $('#ModalVoidmaster').modal('show');
            $('#not_void').focus();
            voidedData(id);
        });
    });

    var input1 = document.getElementById("not_void");
    var okButton = document.getElementById("ok-button");

    function checkInputs() {
        if (input1.value) {
            okButton.disabled = false;
        } else {
            okButton.disabled = true;
        }
    }
    input1.addEventListener("input", checkInputs);


    // VOIDED DATA MASTER IN AJAX
    function voidedData(id) {
        // $('.modal-footer').on('click', '.void_submit', function() {
        $('.modal-footer').off('click', '.void_submit').on('click', '.void_submit', function() {
            // $('.void_submit').html('Saving...');
            var route = "{{ route('tms.warehouse.master_minmax_void', ':id') }}";
            route = route.replace(':id', id);
            $.ajax({
                url: route,
                type: "POST",
                data: $('#form-master-void').serialize(),
                success: function(data) {
                    swal.fire({
                        icon: 'success',
                        title: 'success',
                        // timer: 2000,
                        text: 'Data berhasil divoid',
                    });
                    $('#not_void').val("");
                    $('#ModalVoidmaster').modal('hide');
                    $('#masterMinMax-datatables').DataTable().ajax.reload();
                    // location.reload();
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Something went wrong!',
                    })
                }
            });

        });
    }
</script>
