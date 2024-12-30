<div class="modal fade-out bd-example-modal-lg modalImportexcel" tabindex="-1" id="modalImportexcel" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark">
                <h4 class="modal-title">Upload File Excel / Update Min Max</h4>
            </div>
            <div class="modal-body">
                <form id="form-importexcel" method="post" enctype="multipart/form-data" action="javascript:void(0)">
                    @csrf
                    <div class="form-group">
                        <label for="excel_file">Additional Field:</label>
                        <input class="form-control" type="file" id="excel_file" name="excel_file" accept=".xls,.xlsx">
                    </div>
                </form>
                <div id="loading1" class="text-center d-none">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p>Loading...</p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeModalimport" data-dismiss="modal">
                    <i class="ti-close"></i>&nbsp;Close
                </button>
                <button type="button" id="btnImport" class="btn btn-primary">
                    <i class="ti-check"></i>&nbsp;Save
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(document).on('click', '#importExcel', function(e) {
            e.preventDefault();
            // Show the modal
            $('#modalImportexcel').modal('show');

        });
     //btn clik for cancel file excel
        $(document).on('click', '#closeModalimport', function(e) {
            e.preventDefault();
            // alert('oke');
            $('#modalImportexcel').modal('hide');
            $('#excel_file').val('');


        });

        //btn clik for submit file excel
        $('#btnImport').on('click', function(e) {
                e.preventDefault(); // Mencegah perilaku bawaan formulir

                var inputFile = $('input[name="excel_file"]');
                if (inputFile.val() === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'File cannot be empty',
                    });
                } else {
                    var fileName = inputFile.val();
                    if (/\.(xls|xlsx)$/i.test(fileName)) {
                        $('#loading1').removeClass('d-none');
                        // Buat objek FormData
                        var formData = new FormData($('#form-importexcel')[0]);

                        // Lakukan permintaan AJAX
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('tms.warehouse.master_minmax.importExcel') }}",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                $('#loading1').addClass('d-none');
                                // Tanggapan sukses, lakukan sesuatu di sini
                                if (response.message === 'success') {
                                    // Kosongkan nilai input file
                                    $('#form-importexcel input[type="file"]').val('');

                                    // Sembunyikan modal setelah elemen loading1 benar-benar ditampilkan
                                    $('#modalImportexcel').modal('hide');

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: 'Data Min Max Berhasil Update',
                                    });

                                    $('#masterMinMax-datatables').DataTable().ajax.reload();
                                } else {
                                    // Tanggapan tidak sesuai dengan yang diharapkan
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error...',
                                        text: response.message || 'An error occurred',
                                    });
                                    $('#form-importexcel input[type="file"]').val('');
                                    $('#modalImportexcel').modal('hide');
                                }
                            },
                            error: function(error) {
                                $('#loading1').addClass('d-none');
                                // Tanggapan gagal, lakukan sesuatu di sini
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error...',
                                    text: 'An error occurred during the upload',
                                });
                                $('#form-importexcel input[type="file"]').val('');
                                $('#modalImportexcel').modal('hide');
                            }
                        });
                    } else {
                        $('#form-importexcel input[type="file"]').val('');
                        // Jika format file tidak sesuai, tampilkan alert error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'File is not Excel',
                        });
                    }
                }
            });


    });
</script>
