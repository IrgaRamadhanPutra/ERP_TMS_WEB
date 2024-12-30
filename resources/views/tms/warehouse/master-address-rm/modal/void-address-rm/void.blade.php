<div class="modal fade-out bd-example-modal-lg modalVoidmaster" tabindex="-1" id="ModalVoidmaster" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark  py-2">
                <span style="font-size: 20px" class="modal-title"><b> Form Master Kanban <--void--></b></span>
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body">
                        <form id="form-master-void" method="post" action="javascript:void(0)">
                            @csrf
                            @method('POST')
                            @include('tms.warehouse.master-address-rm.modal.void-address-rm.form')
                        </form>
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

    // VOIDED DATA MASTER ADDRESS RM IN AJAX

    function voidedData(id) {
        $('.modal-footer').off('click', '.void_submit').on('click', '.void_submit', function() {

            var route = "{{ route('tms.warehouse.master_address_void', ':id') }}";
            route = route.replace(':id', id);

            $.ajax({
                url: route,
                type: "POST",
                data: $('#form-master-void').serialize(),
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Data berhasil divoid',
                    });
                    $('#not_void').val(""); // Mengosongkan input
                    $('#ModalVoidmaster').modal('hide'); // Menutup modal
                    $('#masterAddressRm-datatables').DataTable().ajax.reload(); // Reload datatable
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Something went wrong!',
                    });
                }
            });
        });

    }
</script>
