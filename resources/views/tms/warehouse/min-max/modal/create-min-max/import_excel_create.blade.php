<div class="modal fade" id="importExcelcreate" tabindex="-1" aria-labelledby="importExcelcreateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="importExcelcreateLabel">Import Create Min Max</h4>
                <span aria-label="Close" class="close" data-dismiss="modal"
                    style="position: absolute; top: 10px; right: 10px;">
                    <span aria-hidden="true">&times;</span>
            </div>
            <div class="modal-body">
                <form id="importExcelForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="importFile" class="form-label">Upload Excel File</label>
                        <input class="form-control" type="file" id="importFile" name="importFile"
                            accept=".xls,.xlsx">
                    </div>
                </form>
                <div id="loading" class="text-center d-none">
                    <div class="spinner-container">
                        {{-- <i class="fa fa-repeat"></i> --}}
                    </div>
                    <p>Loading...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeModal" data-bs-dismiss="modal"> <i
                        class="ti-close"></i>
                    &nbsp;Close</button>
                <button type="button" id="btnSave" class="btn btn-primary">
                    <i class="ti-check"></i>
                    &nbsp;Save</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // click btn import excel
        $(document).on('click', '#showImport', function(e) {
            e.preventDefault();
            // alert('oke');
            $('#importExcelcreate').modal('show');
            $('#createMinMax').modal('hide');
            // $('#createMinMax').modal('show');
        });

        // clik close modal
        $(document).on('click', '#closeModal', function(e) {
            e.preventDefault();
            // alert('oke');
            $('#importExcelcreate').modal('hide');
            $('#importFile').val('');


        });
        // clik btn save
        $(document).on('click', '#btnSave', function(e) {
            e.preventDefault();
            var inputFile = $('input[name="importFile"]');
            if (inputFile.val() === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error...',
                    text: 'File cannot be empty',
                })
            } else {
                $('#loading').removeClass('d-none');

                var formData = new FormData($('#importExcelForm')[0]);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('tms.warehouse.master_min_max.import_excel_create') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#loading').addClass('d-none');
                        // Tanggapan sukses, lakukan sesuatu di sini
                        if (response.message === 'success') {
                            // Tampilkan alert sukses
                            // Kosongkan nilai input file
                            $('#importExcelForm input[type="file"]').val('');

                            // Sembunyikan modal setelah elemen loading benar-benar ditampilkan
                            $('#importExcelcreate').modal('hide');


                            swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: 'Data berhasil di upload',
                            });

                            $('#masterMinMax-datatables').DataTable().ajax.reload();
                            // Additional handling for success, if needed
                        } else {
                            // Tanggapan tidak sesuai dengan yang diharapkan
                            // alert('Unknown response format');

                            Swal.fire({
                                icon: 'error',
                                title: 'Error...',
                                // text: 'Quantity Bukan Kelipatan Lot'
                            });
                            $('#importExcelForm input[type="file"]').val('');
                        }
                    },
                    error: function(error) {
                        $('#loading').addClass('d-none');
                        // Extract error message from response
                        let errorMessage = error.responseJSON ? error.responseJSON.error :
                            '';

                        // Show the error message using SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: errorMessage
                        });
                        $('#importExcelForm input[type="file"]').val('');
                    }
                });
            }

        });

    });
</script>
