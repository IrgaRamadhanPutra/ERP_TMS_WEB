<div class="modal fade bd-example-modal-xl createMinMaxRm" data-backdrop="static" data-keyboard="false"
    style="z-index: 1041" tabindex="-1" id="createMinMaxRm" role="dialog">
    <div class="modal-dialog modal-xl" style="max-width: 1000px; width: 100%">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark  py-2 bg-light">
                <span style="font-size: 22px" class="modal-title"><b> Master Min Max RM --Create-- </b></span>
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body">
                        <form id="form-masterMinMaxRm" method="post" action="javascript:void(0)">
                            @csrf
                            @method('POST')
                            @include('tms.warehouse.min-max-rm.modal.create-min-max-rm.form')
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
                                class="btn btn-info btn-lg-6 addMasterMinMaxRm" style="height: 38px;">
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
        $(document).on('click', '#addModalminmaxrm', function(e) {
            e.preventDefault();
            // alert('masuk');
            $('#createMinMaxRm').modal('show');
            setTimeout(function() {
                $('#chutter_create').focus();
            }, 500);
        });

    });
    // reset form
    function resetForm() {
        // Reset semua input text di form, kecuali yang memiliki ID 'stock_type_create'
        $('#form-masterMinMaxRm input').not('#stock_type_create').val('');

        // Reset semua select ke default (pilihan pertama)
        $('#form-masterMinMaxRm select').prop('selectedIndex', 0);

        // Hapus pesan error jika ada
        $('.error-message').remove();

    }

    // just number for min and maxs
    function validateNumericInput(input) {
        // Hanya izinkan angka, titik (.), dan koma (,)
        input.value = input.value.replace(/[^0-9.,]/g, '');
    }

    // validasi itemcode
    function validasiItemcode() {
        // Ambil nilai dari input
        var itemcode = $('#itemcode_create').val();

        // Pastikan nilai tidak kosong
        if (!itemcode) {
            // alert('Itemcode tidak boleh kosong!');
            return;
        }

        // AJAX untuk mengirim nilai ke server
        $.ajax({
            url: "{{ route('tms.warehouse.master_min_max_rm.validasi_itemcode') }}",
            type: 'GET',
            data: {
                itemcode: itemcode
            },
            success: function(response) {
                if (response.status === "null") {
                    // Itemcode tidak ada
                    $('#error_message').hide();
                } else if (response.status === "not_null") {
                    // Tampilkan pesan error jika itemcode sudah ada
                    $('#error_message').show(); // Tampilkan span error
                    $('#itemcode_create').val(''); // Reset input
                    $('#itemcode_create').focus(); // Fokuskan kembali ke input

                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                // alert('Terjadi kesalahan saat memvalidasi itemcode.');
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat memvalidasi itemcode',
                });
            }
        });


    }

    // Fungsi untuk memeriksa jika ada input yang kosong
    function validateInputs() {
        let isValid = true; // Flag validasi
        const emptyFields = []; // Menyimpan ID field yang kosong

        // Daftar ID input yang akan divalidasi
        const fieldsToValidate = [
            "chutter_create",
            "itemcode_create",
            "partno_create",
            "partname_create",
            "part_type_create",
            "unit_create",
            "plant_create",
            "cust_code_create",
            "min_create",
            "max_create"
        ];

        fieldsToValidate.forEach((fieldId) => {
            const field = document.getElementById(fieldId); // Ambil elemen berdasarkan ID
            const value = field.value.trim(); // Ambil nilai input, hilangkan spasi
            const isSelect = field.tagName.toLowerCase() === "select"; // Cek apakah elemen adalah select

            // Cek jika field kosong
            if (value === "") {
                isValid = false;
                emptyFields.push(fieldId);

                // Tambahkan pesan error jika belum ada
                if (!field.nextElementSibling || !field.nextElementSibling.classList.contains(
                        "error-message")) {
                    const errorMessage = document.createElement("div");
                    errorMessage.className = "error-message";
                    errorMessage.style.color = "red";
                    errorMessage.textContent = isSelect ?
                        "Please select an option" :
                        "This field is required";
                    field.after(errorMessage);
                }
            } else {
                // Hapus pesan error jika field sudah terisi
                if (field.nextElementSibling && field.nextElementSibling.classList.contains("error-message")) {
                    field.nextElementSibling.remove();
                }
            }
        });

        // Debugging: Log field yang kosong
        console.log("Empty fields:", emptyFields);

        return isValid; // Return hasil validasi
    }


    // Ketika tombol save diklik
    // Melakukan AJAX untuk mengirim data
    $(".addMasterMinMaxRm").on("click", function() {
        if (validateInputs()) {
            var formData = $("#form-masterMinMaxRm").serialize();

            $.ajax({
                url: "{{ route('tms.warehouse.master_min_max_rm.store_master_min_max_rm') }}",
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
                            $('#createMinMaxRm').modal('hide');

                            // Reset nilai form
                            resetForm();

                            // Reload tabel data
                            $('#masterMinMaxRm-datatables').DataTable().ajax.reload();
                        });

                        // Log data yang disimpan (opsional)
                        // console.log("Saved Data:", response.data);
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
                },

            });
        }
    });
</script>
