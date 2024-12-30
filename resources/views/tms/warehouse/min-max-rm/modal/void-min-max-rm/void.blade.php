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
                            @include('tms.warehouse.master-kanban.modal.void-kanban.form')
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

    // VOIDED DATA MASTER IN AJAX
    function voidedData(id, code) {
        // $('.modal-footer').on('click', '.void_submit', function() {
        $('.modal-footer').off('click', '.void_submit').on('click', '.void_submit', function() {
            // $('.void_submit').html('Saving...');
            var route = "{{ route('tms.warehouse.master_min_max_rm_void', ':id') }}";
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
                    $('#masterMinMaxRm-datatables').DataTable().ajax.reload();
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
