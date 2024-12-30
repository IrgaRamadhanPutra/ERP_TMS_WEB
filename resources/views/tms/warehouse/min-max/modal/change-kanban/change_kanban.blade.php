<div class="modal fade bd-example-modal-lg modalChangekanban" tabindex="-1" id="modalChangekanban" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom text-dark">
                <h4 class="modal-title">Change Kanban Chuter</h4>
            </div>

            <div class="modal-body">
                <form id="form-changekanban" method="post" action="javascript:void(0)">
                    @csrf

                    <!-- Input field untuk No Kanban -->
                    <div class="form-group row">
                        <label for="kanban_change" class="col-2 col-form-label">Kanban Change</label>
                        <div class="col-8">
                            <input type="text" name="kanban_change" id="kanban_change"
                                class="form-control form-control-sm" placeholder="">
                        </div>
                    </div>

                    <!-- Input field untuk Seq -->
                    <div class="form-group row">
                        <label for="kanban_new" class="col-2 col-form-label">Kanban New</label>
                        <div class="col-8">
                            <input type="text" id="kanban_new" name="kanban_new" class="form-control form-control-sm"
                                placeholder="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- Menggunakan atribut data-dismiss untuk menutup modal -->
                        <button type="button" class="btn btn-secondary" id="closeModal"
                            data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btnSavechange">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on('click', '#changeKanbanchuter', function(e) {
            e.preventDefault();
            // Show the modal
            $('#modalChangekanban').modal('show');
            $('#kanban_change').focus();

        });

        // Event listener for Enter key on #kanban_change
        $('#kanban_change').keydown(function(event) {
            if (event.keyCode === 13) { // 13 is the key code for Enter
                event.preventDefault();
                var kanbanValue = $(this).val().trim();
                if (kanbanValue !== '') {
                    // If data is available, set readonly and focus on kanban_new
                    $('#kanban_change').val(kanbanValue).prop('readonly', true);
                    $('#kanban_new').focus();
                }
            }
        });

        $('#kanban_new').keydown(function(event) {
            if (event.keyCode === 13) { // 13 is the key code for Enter
                event.preventDefault();
                var kanbanValue = $(this).val().trim();
                if (kanbanValue !== '') {
                    // If data is available, set readonly and focus on kanban_new
                    $('#kanban_new').val(kanbanValue).prop('readonly', true);
                    // $('#kanban_new').focus();
                }
            }
        });
        // btn submit
        $('#btnSavechange').on('click', function(e) {
            e.preventDefault(); // Mencegah perilaku bawaan formulir
            var kanban_change = $('#kanban_change').val();
            var kanban_new = $('#kanban_new').val();
            var condtion = !kanban_change || !kanban_new
            if (condtion) {
                Swal.fire({
                    icon: 'warning',
                    // timer: 2000,
                    title: 'The input form cannot be empty!'
                })
            } else {
                //  inisialiasi unutk kanban change
                var kanban_change = $('#kanban_change').val();
                var kanban_change_array = kanban_change.split(',');
                // get to kanban and seq

                if (kanban_change_array.length === 7) {
                    // Jika panjang array 7, ambil elemen 0 dan 1
                    var noKanban_change = kanban_change_array[0].trim();
                    var seq_change = kanban_change_array[1].trim();

                } else if (kanban_change_array.length === 8) {
                    // Jika panjang array 8, ambil elemen 0 dan 1
                    var noKanban_change = kanban_change_array[1].trim();
                    var seq_change = kanban_change_array[2].trim();

                }
                // inisiaslisasi untuk kanban new
                var kanban_new = $('#kanban_new').val();
                var kanban_new_array = kanban_new.split(',');
                // get to kanban and seq
                if (kanban_new_array.length === 7) {
                    // Jika panjang array 7
                    var noKanban_new = kanban_new_array[0].trim();
                    var seq_new = kanban_new_array[1].trim();

                } else if (kanban_new_array.length === 8) {
                    // Jika panjang array 8
                    var noKanban_new = kanban_new_array[1].trim();
                    var seq_new = kanban_new_array[2].trim();

                }
                // alert(noKanban_new ,seq_new)
                if (noKanban_new != noKanban_change) {
                    // alert('kanban tidak sama')
                    Swal.fire({
                        icon: 'warning',
                        // timer: 2000,
                        title: 'kanban No Tidak Sama!'
                    })
                    $('#kanban_change').val('').prop('readonly', false);
                    $('#kanban_new').val('').prop('readonly', false);
                    $('#kanban_change').focus();
                } else {
                    // alert('lanjut ajax ')
                    // Lakukan permintaan AJAX
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('tms.warehouse.master_minmax.changeKanban') }}",
                        data: {
                            noKanban_change: noKanban_change,
                            seq_change: seq_change,
                            noKanban_new: noKanban_new,
                            seq_new: seq_new
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        success: function(response) {
                            // Tanggapi respons dari server
                            if (response.status === 'success') {

                                swal.fire({
                                    icon: 'success',
                                    title: 'success',
                                    // timer: 2000,
                                    text: 'Kanban successfully change',
                                });
                                $('#kanban_change').val('').prop('readonly', false);
                                $('#kanban_new').val('').prop('readonly', false);
                                $('#kanban_change').focus();
                                $('#modalChangekanban').modal('hide');
                            // } else if (response.status === 'Data not found') {
                            //     // Terjadi kesalahan
                            //     Swal.fire({
                            //         icon: 'error',
                            //         title: 'Error...',
                            //         text: 'kanban Belum Scan in Chutter!',
                            //     })
                            //     $('#kanban_change').val('').prop('readonly', false);
                            //     $('#kanban_new').val('').prop('readonly', false);
                            //     $('#kanban_change').focus();
                            //     $('#modalChangekanban').modal('hide');
                                // Tambahkan logika atau tindakan lain yang sesuai dengan kesalahan update
                            } else if (response.status === 'error') {
                                // Jika respons adalah error, tampilkan alert
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error...',
                                    text: 'kanban Belum Scan in Fg',
                                })
                                $('#kanban_change').val('').prop('readonly', false);
                                $('#kanban_new').val('').prop('readonly', false);
                                $('#kanban_change').focus();
                                $('#modalChangekanban').modal('hide');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Tanggapan error dari AJAX request
                            // console.error(xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error...',
                                text: 'kanban Belum Scan in Chutter!',
                            })
                            $('#kanban_change').val('').prop('readonly', false);
                            $('#kanban_new').val('').prop('readonly', false);
                            $('#kanban_change').focus();
                            $('#modalChangekanban').modal('hide');
                        }
                    });
                }

            }

        });
        $('#closeModal').on('click', function(e) {
            e.preventDefault(); // Mencegah perilaku bawaan formulir
            $('#kanban_change').val('').prop('readonly', false);
            $('#kanban_new').val('').prop('readonly', false);


        });
    });
</script>
