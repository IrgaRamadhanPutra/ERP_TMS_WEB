<div class="modal fade bd-example-modal-xl createKanban" data-backdrop="static" data-keyboard="false" style="z-index: 1041"
    tabindex="-1" id="createKanban" role="dialog">
    <div class="modal-dialog modal-xl" style="max-width: 1000px; width: 100%">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark py-2 bg-light">
                <h4 class="modal-title" style="font-size: 17px">Form Master Kanban <--create--></h4>
            </div>
            <div class="row">
                <div class="col">
                    <div class="modal-body">
                        <form id="form-masterKanban" method="post" action="javascript:void(0)">
                            @csrf
                            @method('POST')
                            @include('tms.warehouse.master-kanban.modal.create-kanban.form')
                        </form>
                    </div>
                    <div class="modal-footer d-flex justify-content-end py-3 bg-light">
                        {{-- <button type="button" onclick="clear_input()" data-toggle="tooltip" class="btn text-dark"
                            data-dismiss="modal">
                            <i class="ti-close"></i>
                            &nbsp;Close
                        </button> --}}
                        <button type="button" data-toggle="tooltip" data-placement="top"
                            class="btn btn-secondary btn-lg-6" data-dismiss="modal" style="height: 38px;">
                            <i class="fa fa-times-circle"></i>
                            &nbsp; Close
                        </button>
                        <button type="button" data-toggle="tooltip" data-placement="top"
                            class="btn btn-info btn-lg-6 addMasterKanban" style="height: 38px;">
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
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        // ADD DISPLAY MODAL CREATE
        $(document).on('click', '#addModalkanban', function(e) {
            e.preventDefault();
            $('#createKanban').modal('show');
            setTimeout(function() {
                $('#production_line_create').focus();
            }, 500);
        });
        //   add data master kanban
        // $('.modal-footer').on('click', '.addMasterKanban', function() {
        //     // console.log('masuk');
        //     let isValid = true;
        //     let errorMessage = "Please fill out the following fields:\n";
        //     let groupedLabels = {};

        //     $('#form-masterKanban').find('input, select').each(function() {
        //         const $input = $(this);
        //         const $formGroup = $input.closest('.form-group');
        //         const label = $formGroup.find('label').text().trim();

        //         if ($.trim($input.val()) === "") {
        //             isValid = false;
        //             if ($formGroup.hasClass('row')) {
        //                 // Grouped inputs: add the label only if it doesn't exist
        //                 if (!groupedLabels[label]) {
        //                     groupedLabels[label] = true; // Mark this label as added
        //                     errorMessage += `- ${label}\n`;
        //                 }
        //             } else {
        //                 // Non-grouped input: add the label directly
        //                 errorMessage += `- ${label}\n`;
        //             }
        //             $input.addClass(
        //                 'is-invalid'); // Add Bootstrap 'is-invalid' class for visual feedback
        //         } else {
        //             $input.removeClass('is-invalid'); // Remove the class if field is valid
        //         }
        //     });

        //     if (!isValid) {
        //         // Show SweetAlert for errors
        //         Swal.fire({
        //             icon: 'error',
        //             title: 'Error!',
        //             text: errorMessage,
        //             confirmButtonText: 'OK'
        //         });
        //     } else {
        //         // Show success alert
        //         alert('sukses');
        //         $.ajaxSetup({
        //             headers: {
        //                 'X-CSRF-TOKEN': $(
        //                     'meta[name="csrf-token"]'
        //                 ).attr(
        //                     'content')
        //             }
        //         });
        //         $.ajax({
        //             url: "{{ route('tms.warehouse.master_kanban.store_masterkanban') }}",
        //             type: "POST",
        //             data: $('#form-masterKanban').serialize(),
        //             dataType: 'json',
        //             success: function(response) {
        //                 if (response.status === 'success') {
        //                     clearForm();
        //                     Swal.fire({
        //                         icon: 'success',
        //                         title: 'Successfully!',
        //                         text: response.message,
        //                     }).then(function() {
        //                         $('#createKanban').modal('hide');
        //                         $('#masterKanban-datatables').DataTable().ajax
        //                             .reload();
        //                     });
        //                 } else {
        //                     Swal.fire({
        //                         icon: 'error',
        //                         title: 'Error!',
        //                         text: response.message,
        //                     });
        //                 }
        //             },
        //             error: function(xhr, status, error) {
        //                 Swal.fire({
        //                     icon: 'error',
        //                     title: 'Error!',
        //                     text: 'An error occurred while processing the request.',
        //                 });
        //             }
        //         });

        //     }
        // });
        $('.modal-footer').on('click', '.addMasterKanban', function() {
            let isValid = true;
            let errorMessage = "Please fill out the following fields:\n"; // Reset errorMessage

            // Validasi input berdasarkan ID
            const idsToValidate = [
                "production_line_create",
                "branch_create",
                "line_code_create",
                "itemcode_create",
                "kanban_create",
                "partno_create",
                "partname_create",
                "part_type_create",
                "qty_create",
                "cust_create",
                "kanban_type_create",
                "base_unit_create"
            ];

            idsToValidate.forEach(id => {
                const $input = $(`#${id}`);
                const $formGroup = $input.closest('.form-group');
                const label = $formGroup.find('label').text().trim();

                if ($.trim($input.val()) === "") {
                    isValid = false;
                    errorMessage += `- ${label}\n`;
                    $input.addClass('is-invalid'); // Tambahkan kelas untuk feedback visual
                } else {
                    $input.removeClass('is-invalid'); // Hapus kelas jika valid
                }
            });

            if (!isValid) {
                // Tampilkan SweetAlert jika ada error
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage,
                    confirmButtonText: 'OK'
                });
            } else {
                // alert('Sukses! Data berhasil disubmit.');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $(
                            'meta[name="csrf-token"]'
                        ).attr(
                            'content')
                    }
                });
                $.ajax({
                    url: "{{ route('tms.warehouse.master_kanban.store_masterkanban') }}",
                    type: "POST",
                    data: $('#form-masterKanban').serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            clearForm();
                            Swal.fire({
                                icon: 'success',
                                title: 'Successfully!',
                                text: response.message,
                            }).then(function() {
                                $('#createKanban').modal('hide');
                                $('#masterKanban-datatables').DataTable().ajax
                                    .reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message,
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred while processing the request.',
                        });
                    }
                });
            }

        });


        // Function to clear form inputs
        function clearForm() {
            $('#form-masterKanban').find('input, select, textarea').each(function() {
                if ($(this).is('select')) {
                    $(this).prop('selectedIndex', 0); // Reset dropdown ke opsi pertama
                } else {
                    $(this).val(''); // Clear input dan textarea
                }
                $(this).removeClass('is-invalid'); // Hapus class error
            });
        }

        // Attach click event to the close button
        $('.btn-secondary').on('click', function() {
            clearForm(); // Call the clear form function
        });
    });

    // funtion branch
    function updateLineCodeOptions() {
        const productionLine = document.getElementById("production_line_create").value;
        const branch = document.getElementById("branch_create").value;
        const lineCodeSelect = document.getElementById("line_code_create");

        // Clear existing options
        lineCodeSelect.innerHTML = '<option value="" disabled selected hidden>--Choice--</option>';

        // Define options for branch 1701
        const inhouseOptions1701 = ["ASSY=1320", "WELDING=1320", "SPOT=1320", "PRESS=1310", "QC=1050"];
        const subcontOptions1701 = ["PQA=1080"];

        // Define options for branch 1702
        const inhouseOptions1702 = ["ASSY=2320", "WELDING=2320", "SPOT=2320", "PRESS=2310", "QC=2050"];
        const subcontOptions1702 = ["PQA=2080"];

        let options = [];

        // Choose the appropriate options based on the selected branch and production line
        if (branch === "1701") {
            if (productionLine === "INHOUSE") {
                options = inhouseOptions1701;
            } else if (productionLine === "SUBCONT") {
                options = subcontOptions1701;
            }
        } else if (branch === "1702") {
            if (productionLine === "INHOUSE") {
                options = inhouseOptions1702;
            } else if (productionLine === "SUBCONT") {
                options = subcontOptions1702;
            }
        }

        // Populate the line code dropdown with the selected options
        options.forEach((option, index) => {
            // console.log(`Processing option ${index + 1}: ${option}`); // Log setiap opsi sebelum diproses

            const opt = document.createElement("option");
            opt.value = option.trim(); // Menggunakan seluruh string sebagai value
            opt.textContent = option.trim(); // Menggunakan seluruh string sebagai label

            // console.log(
            //     `Option element created - Value: ${opt.value}, Text: ${opt.textContent}`
            //     ); // Log elemen option yang dibuat

            lineCodeSelect.appendChild(opt); // Tambahkan opsi ke dropdown
            // console.log(`Option appended to select element`); // Log bahwa opsi telah ditambahkan
        });


    }

    // Select the element by id
    const lineCodeSelect = document.getElementById('line_code_create');

    // Add the onchange event listener
    lineCodeSelect.addEventListener('change', function() {
        validasiItemcode(); // Call the function when selection changes
    });

    // validasi itemcode
    function validasiItemcode() {
        // Get the input values
        const itemcode = document.getElementById('itemcode_create').value;
        const productionLine = document.getElementById('production_line_create').value;
        const lineCodestring = document.getElementById('line_code_create').value; // Ambil nilai dari input
        const lineCode = lineCodestring.split('=')[0]; // Pisahkan berdasarkan '=' dan ambil bagian pertama
        // Check if itemcode is empty
        if (itemcode !== "") {
            // alert('Itemcode cannot be empty. Please enter a value.');
            // Set up CSRF token for AJAX request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Send AJAX request to validate item code
            $.ajax({
                url: "{{ route('tms.warehouse.master_kanban.validasi_itemcode') }}",
                type: "GET",
                data: {
                    itemcode: itemcode,
                    productionLine: productionLine,
                    lineCode: lineCode
                },
                dataType: 'json',
                success: function(response) {
                    // Check if ekanban_no is present in the response
                    if (response.ekanban_no) {
                        // Populate ekanban_no into the kanban_create input field
                        $('#kanban_create').val(response.ekanban_no);
                    } else {
                        // Clear the input field and show a Swal alert for "Proceed to the Next Step"
                        $('#kanban_create').val('');
                        // Swal.fire({
                        //     icon: 'info',
                        //     title: 'Proceed to the Next Step',
                        //     text: 'Lanjut untuk tahap selanjutnya'
                        // });
                        const itemcode = document.getElementById('itemcode_create').value;
                        const productionLine = document.getElementById('production_line_create')
                            .value;
                        const lineCodestring = document.getElementById('line_code_create')
                            .value; // Ambil nilai dari input
                        const lineCode = lineCodestring.split('=')[0];
                        // Set up CSRF token for AJAX request
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content')
                            }
                        });

                        // Send AJAX request to generate the new ekanban_no
                        $.ajax({
                            url: "{{ route('tms.warehouse.master_kanban.generate_kanban_no') }}",
                            type: "GET",
                            data: {
                                itemcode: itemcode,
                                productionLine: productionLine,
                                lineCode: lineCode
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.status === 'success' && response
                                    .ekanban_no) {
                                    // Populate #kanban_create with the new ekanban_no value
                                    $('#kanban_create').val(response.ekanban_no);
                                    $('#partno_create').focus();
                                }
                            },
                            error: function(xhr, status, error) {
                                // Display an error alert if the request fails
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'An error occurred while processing the request. Please try again.',
                                });
                            }
                        });

                    }
                },
                error: function(xhr, status, error) {
                    // Handle any unexpected errors (if necessary)
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An unexpected error occurred.',
                    });
                }
            });

            return; // Stop further execution if itemcode is empty
        }
    }
</script>
