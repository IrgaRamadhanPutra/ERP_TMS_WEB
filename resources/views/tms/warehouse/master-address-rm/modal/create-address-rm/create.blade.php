<div class="modal fade bd-example-modal-xl createMasterAddressRm" data-backdrop="static" data-keyboard="false"
    style="z-index: 1041" tabindex="-1" id="createMasterAddressRm" role="dialog">
    <div class="modal-dialog modal-xl" style="max-width: 1000px; width: 100%">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark  py-2 bg-light">
                <span style="font-size: 22px" class="modal-title"><b> Master Address RM --Create-- </b></span>
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body">
                        <form id="form-masterAddressRm" method="post" action="javascript:void(0)">
                            @csrf
                            @method('POST')
                            @include('tms.warehouse.master-address-rm.modal.create-address-rm.form')
                        </form>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <div>
                            {{-- <button type="button" class="btn btn-success btn-flat btn-lg" >
                                <i class="fa fa-file-excel-o"></i>
                            </button> --}}
                            <button type="button" data-toggle="tooltip" title="Import" id="showImport"
                                data-placement="top" class="btn btn-success btn-lg-6" style="height: 38px;">
                                <i class="fa fa-file-excel-o"></i>
                                Import Excel
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-secondary btn-lg-6" data-dismiss="modal"
                                style="height: 38px;" onclick="resetForm()">
                                <i class="fa fa-times-circle"></i>
                                &nbsp; Close
                            </button>

                            <button type="button" data-toggle="tooltip" data-placement="top"
                                class="btn btn-info btn-lg-6 addMasterAddressRm" style="height: 38px;">
                                {{-- <i class="fas fa-check"></i> --}}
                                <i class="fa fa-check-square"></i>
                                &nbsp;&nbsp; Save
                            </button>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // ADD DISPLAY MODAL CREATE
    $(document).ready(function() {
        $(document).on('click', '#addModalMasterAddressRm', function(e) {
            e.preventDefault();
            // alert('masuk');
            $('#createMasterAddressRm').modal('show');
            setTimeout(function() {
                $('#chutter_create').focus();
            }, 500);
        });

    });
    // reset form
    function resetForm() {
        // Reset semua input text di form, kecuali yang memiliki ID 'stock_type_create'
        $('#form-masterAddressRm input').not('#stock_type_create').val('');

        // Reset semua select ke default (pilihan pertama)
        $('#form-masterAddressRm select').prop('selectedIndex', 0);

        // Hapus pesan error jika ada
        $('.error-message').remove();

    }
    // Fungsi untuk memeriksa jika ada input yang kosong
    function validateInputs() {
        let isValid = true; // Flag validasi
        let errorMessage = "Please fill out the following fields:\n"; // Inisialisasi pesan error

        // Daftar ID input yang akan divalidasi
        const fieldsToValidate = [
            "chutter_create",
            "itemcode_create",
            "partno_create",
            "partname_create",
            "part_type_create",
            "process_code_create",
            "supplier_create",
            "stock_type_create",
            "plant_create"
        ];

        fieldsToValidate.forEach(id => {
            const $input = $(`#${id}`);
            const $formGroup = $input.closest('.form-group'); // Cari elemen group form
            const label = $formGroup.find('label').text().trim(); // Ambil teks label

            if ($.trim($input.val()) === "") { // Jika input kosong
                isValid = false;
                errorMessage += `- ${label}\n`; // Tambahkan label field yang kosong ke pesan error
                $input.addClass('is-invalid'); // Tambahkan kelas visual error
            } else {
                $input.removeClass('is-invalid'); // Hapus kelas jika valid
            }
        });

        if (!isValid) {
            // Tampilkan SweetAlert jika ada field yang kosong
            Swal.fire({
                icon: 'error',
                title: 'Validation Error!',
                text: errorMessage,
                confirmButtonText: 'OK'
            });
        }

        return isValid; // Return hasil validasi
    }

    // Melakukan AJAX untuk mengirim data
    $(".addMasterAddressRm").on("click", function() {
        if (validateInputs()) {
            var formData = $("#form-masterAddressRm").serialize();
            $.ajax({
                url: "{{ route('tms.warehouse.master_address_rm.store_master_address_rm') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Menyertakan CSRF token
                },
                data: formData,
                success: function(response) {
                    // Cek jika status adalah "success"
                    if (response.status === 'success') {
                        // Tampilkan pesan sukses menggunakan SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        }).then(function() {
                            // Sembunyikan modal setelah berhasil
                            $('#createMasterAddressRm').modal('hide');

                            // Reset nilai form
                            resetForm();

                            // Reload tabel data
                            $('#masterAddressRm-datatables').DataTable().ajax.reload();
                        });
                    } else if (response.status === 'error') {
                        // Tangani jika status error diterima dari server
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message,
                        });
                    } else {
                        // Tangani jika status tidak seperti yang diharapkan
                        Swal.fire({
                            icon: 'warning',
                            title: 'Unexpected Response',
                            text: "Unexpected status: " + response.status,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Tangani kesalahan dengan SweetAlert
                    let errorMessage =
                        xhr.responseJSON && xhr.responseJSON.message ?
                        xhr.responseJSON.message :
                        "An error occurred. Please try again.";
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage,
                    });

                    // Log error untuk debugging
                    console.error("Error details:", {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        error: error,
                    });
                }
            });
        }
    });
</script>
