<script>
    function keyPressed(e){
     if (e.keyCode == 13) { // PRESS KEYBOARD SHORTCUT ENTER FOR APPEAR DATA ITEM
         e.preventDefault();
         $('#mtoModal').modal('show');
     } else if(e.keyCode){
         e.preventDefault();
     }
 }


 $(".datepicker").datetimepicker({
        format: "YYYY-MM-DD",
        useCurrent: true
        
    });

    $("#setperiod").datetimepicker({
        date: moment(),
        format: "YYYY-MM"
    });

    $('.datepicker').on('dp.change', function(e){ 
        var getperiod = e.date.format("YYYY-MM");
        $('#setperiod').data("DateTimePicker").date(getperiod);
    });
 // ADDED NEW MTO DATA
 $(document).on('click', '#addModal', function(e) {
    e.preventDefault();
    // $('#createModal').after('#mtoModalCreate');
    $('#createModal').modal('show');
    $('.modal-title').text('Many To One Entry (New)');
    var select2 = $('.select_create').select2();
    select2.select2('focus');
 
 });


$('#createModal').on('shown.bs.modal', function () {
  $('#types_create').focus()
  var period = $('#setperiod').val();
//   console.log(period)
  checkStClose(period);
});
$('#EditModal').on('shown.bs.modal', function () {
  $('#ref_no_edit').focus()
//   var period = $('#period_edit').val();
//   console.log(period)

})


 // EDIT DATA MTO
 $(document).on('click', '.edit', function(e){
     e.preventDefault();
     var id = $(this).attr('row-id');
     var posted = $(this).attr('data-target');
     var mto_no = $(this).attr('data-id');
  
    //  alert(period);
 
     if (posted !== '') {
         Swal.fire({
             icon: 'error',
             title: 'Error',
             text: 'MTO entry no.' + mto_no + ' '+'has been posted cant edit',
         });
     } else {
         $('.modal-title').text('Many To One Entry (Edit)');
         $('#EditModal').modal('show');
         // e.preventDefault();
         EditData(id)
        //  UpdateData(mto_no)
     }  
    
   
 });
 // SHOW VIEW DATA MTO
 $(document).on('click', '.view', function(e){
     e.preventDefault();
     var id = $(this).attr('row-id');
     $('#viewModal').modal('show');
     $('.modal-title').text('Many To One Entry (View)');
     getDetail(id, 'VIEW')
 });
 // VOID THIS DATA
 $(document).on('click', '.voided', function(e){
     var id = $(this).attr('row-id');
     var mto_no = $(this).attr('data-id');
     var posted = $(this).attr('data-target');
 
     if (posted !== '') {
         Swal.fire({
             icon: 'error',
             title: 'Error...',
             text: 'MTO entry no.' + mto_no + ' '+'has been posted cant void',
         });
     } else {
         e.preventDefault();
         voidedData(id, mto_no)
     }
     
 });
 //  POSTED VIA AJAX
 $(document).on('click', '.posted', function(e){
     var id = $(this).attr('row-id');
     var mto_no = $(this).attr('data-id');
     var posted = $(this).attr('data-target');
    //  var period = $(this).attr('data-period');

    //  alert(period)
     // alert(posted);
     if(posted !== ''){
         e.preventDefault();
         $('#ModalUnPost').modal('show');
         $('.modal-title').text('MTO Entry (UN-POST)')
        $('.mto_no_unpost').val(mto_no);
        // $('#period_unpost').val(period);
        //  UnPostedMTO(id, mto_no);
        document.getElementById('id_mto_unpost').value = id;
        // alert(tes);
     } else {
         // alert(mto_no);
         e.preventDefault();
         postedMTO(id, mto_no)
     }
 });
 // LOG ACTIVITY
 $(document).on('click', '.log', function(e){
     e.preventDefault();
     var mto_no = $(this).attr('data-id');
     // alert(mto_no);
     $('#logModal').modal('show');
     $('.modal-title').text('View MTO Log');
     // call 
     var route  = "{{ route('tms.warehouse.mto-view_mto_entry_log', ':id') }}";
     route  = route.replace(':id', mto_no);
     $.ajax({
         url:      route,
         method:   'get',
         dataType: 'json',
         success:function(data){
             var detailDataset = [];
             for(var i = 0; i < data.length; i++){
                 detailDataset.push([
                     formatDate(data[i].date), data[i].time, data[i].status_change,
                     data[i].user, data[i].note
                     ]);
             }
             $('#tbl-log').DataTable().clear().destroy();
             $('#tbl-log').DataTable({
                 data: detailDataset,
                 columns: [
                 { title: 'Date'},
                 { title: 'Time'},
                 { title: 'Type'},
                 { title: 'User' },
                 { title: 'Note' }
                 ]
             });
         }, 
         error: function(){
             alert('error');
         }
     })    
 });
 
 // $(document).on('click','#checkStockItem', function(e){
 //     $('#StockModal').modal('show');
 // });
 
 // VALIDATE UN-POST BUTTON DISABLED WHEN NOT YET FILL IN EXCEPTION NOTE
 $(document).ready(function(){
     $('.ok_unpost').attr('disabled', true);
     $('#note').on('keyup',function() {
         if($(this).val() != '') {
             $('.ok_unpost').attr('disabled' , false);
         }else{
             $('.ok_unpost').attr('disabled' , true);
         }
     });
 })
 
 
 //SAVE DATA TO DATABASE FROM AJAX
 $('.modal-footer').on('click','.add', function(){
    //  $('.add').html('Saving...')
     $.ajax({
      url: "{{ route('tms.warehouse.mto-entry_store_mto_data') }}",
      type: "POST",
      data: $('#form-mto').serialize(),
      success: function(data){
            if ($.isEmptyObject(data.error)) {
                if (data.check) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning',
                        text: data.errors,
                    });
                    clear_value_create_page();
                } else {
                    clear_value_create_page();
                    $('#tbl-create-mto').DataTable({
                        "bDestroy": true,
                        paging: false
                    });
                    Swal.fire(
                        'Successfully!',
                        'add new data MTO entry!',
                        'success'
                        ).then(function(){
                            $("#createModal").modal('hide'); 
                            $('#mto-datatables').DataTable().ajax.reload();
                        });
                    }
            } else {
                printErrorMsg(data.error)
            }
      
   
      }
   })

  
 })
 function printErrorMsg(msg){
        $('.print-error-msg').find('ul').html('');
        $('.print-error-msg').css('display','block');
        $('.addStout').html('Save')
        $.each(msg, function(key, value){
            $('.print-error-msg').find('ul').append('<li style="font-size: 15px"><i class="fa fa-exclamation-circle"></i> '+value+'</ul>');
        });
    }
 // VIEW DATA SHOW DETAIL FROM AJAX
 function getDetail(id, method){
     var route  = "{{ route('tms.warehouse.mto-entry_show_view_detail', ':id') }}";
     route  = route.replace(':id', id);
     $.ajax({
         url:      route,
         method:   'get',
         dataType: 'json',
         success:function(data){
             $('#mto_no_view').val(data['header'].mto_no);
             $('#ref_no_view').val(data['header'].ref_no);
             $('#itemcode_view').val(data['header'].fin_code);
             $('#part_no_view').val(data['header'].part_no);
             $('#descript_view').val(data['header'].descript);
             $('#quantity_view').val(data['header'].quantity);
             $('#qty_ng_view').val(data['header'].qty_ng);
             $('#unit_view').val(data['header'].unit);
             $('#remark_view').val(data['header'].remark);
             $('#staff_view').val(data['header'].staff);
             $('#period_view').val(data['header'].period);
             $('#written_view').val(formatDate(data['header'].written));
             $('#branch_view').val(data['header'].branch);
             $('#types_view').val(data['header'].types);
             $('#printed_view').val(formatDate(data['header'].printed));
             $('#voided_view').val(formatDate(data['header'].voided));
             $('#posted_view').val(formatDate(data['header'].posted));
             $('#warehouse_view').val(data['header'].warehouse); 
             if (data['header'].printed == '0000-00-00') {
                $('#printed_view').val("");
             } else {
                $('#printed_view').val(formatDate(data['header'].printed));
             }
 
             var detailDataset = [];
             for(var i = 0; i < data['detail'].length; i++){
                 detailDataset.push([
                     data['detail'][i].frm_code, data['detail'][i].part_no_detail, data['detail'][i].descript_detail,
                     data['detail'][i].unit, data['detail'][i].frm_quantity, data['detail'][i].frm_qty_ng, data['detail'][i].warehouse
                     ]);
             }
             $('#tbl-detail-mto').DataTable().clear().destroy();
             $('#tbl-detail-mto').DataTable({
                "paging":  false,
                "scrollY": '250px',
                "scrollCollapse": true,
                 data: detailDataset,
                 columns: [
                 { title: 'Itemcode'},
                 { title: 'Part No.'},
                 { title: 'Description'},
                 { title: 'Unit' },
                 { title: 'Qty' },
                 { title: 'Qty NG' },
                 { title: 'Warehouse' }
                 ]
             });
         }
     });
 }
 
 // VIEW EDIT FROM EDIT
 function EditData(id){
     var route  = "{{ route('tms.warehouse.mto-entry_edit_mto_data', ':id') }}";
     route  = route.replace(':id', id);
     $.ajax({
         url:      route,
         method:   'get',
         dataType: 'json',
         success:function(data){
            //  alert(data['header'].id_mto);
            $('#mto_no_edit1').val(data['header'].mto_no);
            //
             $('#id_mto_edit').val(data['header'].id_mto);
             $('#mto_no_edit').val(data['header'].mto_no);
             $('#branch_edit').val(data['header'].branch);
             $('#warehouse_edit').val(data['header'].warehouse);
             $('#ref_no_edit').val(data['header'].ref_no);
             $('#ITEMCODE').val(data['header'].fin_code);
             $('#PART_NO').val(data['header'].part_no);
             $('#DESCRIPT').val(data['header'].descript);
             $('#quantity_edit').val(data['header'].quantity);
             $('#qty_ng_edit').val(data['header'].qty_ng);
             $('#unit_edit').val(data['header'].unit);
             $('#remark_edit').val(data['header'].remark);
             $('#written_edit').val(data['header'].written);
             $('#period_edit').val(data['header'].period);
             $('#types_edit').val(data['header'].types);
             $('#staff_edit').val(data['header'].staff);
             $('#voided_edit').val(formatDate(data['header'].voided));
             $('#posted_edit').val(formatDate(data['header'].posted));
            checkStClose(data['header'].period)
            if (data['header'].printed == "0000-00-00") {
                $('#printed_edit').val("");
            } else {
                $('#printed_edit').val(formatDate(data['header'].printed));
            }
             var detailDataset = [];
             var counter = 1;
             for(var i = 0; i < data['detail'].length; i++){
                var qty = "qty_edit_child"+counter;
                var factor = "factor_edit_child"+counter;
                var qty_ng = "qty_ng_edit_child"+counter;
                 detailDataset.push([
                    "<tr>"+
                    "<td><input type='hidden' name='id_mto[]' value='"+data['detail'][i].id_mto+"'></td>"+
                    "<td>"+data['detail'][i].frm_code+"</td>"+
                    "<td><input type='hidden' name='frm_code[]' value='"+data['detail'][i].frm_code+"'></td>", 
                    "<td>"+data['detail'][i].part_no_detail+"</td>"+
                    "<td><input type='hidden' name='part_no_detail[]' value='"+data['detail'][i].part_no_detail+"'></td>", 
                    "<td>"+data['detail'][i].descript_detail+"</td>"+
                    "<td><input type='hidden' name='descript_detail[]' value='"+data['detail'][i].descript_detail+"''></td>",
                    "<td>"+data['detail'][i].unit+"</td>"+
                    "<td><input type='hidden' name='unit[]' value='"+data['detail'][i].unit+"'></td>", 
                    "<td><input type='text' readonly id='"+qty+"' name='frm_quantity[]' class='form-control form-control-sm  qty_child_edit_' value='"+data['detail'][i].frm_quantity+"'></td>"+
                    "<td><input type='hidden' id='"+factor+"' name='factor[]' value='"+data['detail'][i].factor+"'></td>", 
                    "<td><input type='text' class='form-control form-control-sm qty_ng_child_edit_' name='frm_qty_ng[]' id='"+qty_ng+"' value='"+data['detail'][i].frm_qty_ng+"'></td>", 
                    "<td><input type='text' class='wh_edit_detail' readonly value='"+data['detail'][i].warehouse+"'></td>"+
                    "<td><input type='hidden' name='mto_no' value="+data['header'].mto_no+"></td> "+
                    "<td><input type='hidden' name='printed[]' value='"+data['detail'][i].printed+"'></td> ",
                    // "<td><td><button type='button' class='btn btn-danger btn-danger removerowEditDetail2'><i class='ti-trash'></button>"+
                    "</tr>"
                     ]);
                     counter++;
                    // 
             }
             $('#tbl-edit').DataTable().clear().destroy();
            var tableEditt= $('#tbl-edit').DataTable({
                "paging":  false,
                "scrollY": '250px',
                "scrollCollapse": true,
                 data: detailDataset
             });
             var tableeee = tableEditt.rows().count();
             document.getElementById('jmlh_row_edit').value = tableeee; 
         }, 
         error: function(){
             alert('error');
         }
     });
 
 
 }
 

 // UPDATE MTO FROM EDIT
 function UpdateData(){
        //  $('.modal-footer').on('click','.update', function(){
            var id = document.getElementById('mto_no_edit1').value;
            var route  = "{{ route('tms.warehouse.mto-entry_update_mto_entry', ':id') }}";
                route  = route.replace(':id', id);
             $.ajax({
                 url: route,
                 type: "POST",
                 data: $('#form-mto-edit').serialize(),
                 success: function(data){
                        if (data.check) {
                                Swal.fire({
                                icon: 'warning',
                                title:data.errors
                            });
                            // $("#EditModal").modal('hide');
                        } else {
                            $("#EditModal").modal('hide'); 
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil diupdate',
                            }).then(function(){
                                $('#mto-datatables').DataTable().ajax.reload();
                            });
                        }
                     }, 
                     error: function(){
                         Swal.fire({
                             icon: 'error',
                             title: 'Error...',
                             text: 'hubungi admin!',
                         })
                     }
                 });
        //  });
         
     }

   $('.modal-footer').on('click','.update', function(){
    var id = document.getElementById('id_mto_edit').value;
    var route  = "{{ route('tms.warehouse.mto_entry_add_row_edit_page', ':id') }}";
        route  = route.replace(':id', id); 
    // $('.updateStout').html('Saving...');
    $.ajax({
        url: route,
        type: "POST",
        data: $('#form-mto-edit').serialize(),
        success: function(data){
            // alert(data.itemcode);
            $("#EditModal").modal('hide'); 
            // location.reload();
            },
            error:function(data){
                console.log(data);
            }
        });
   });

 // CALL TOKEN FOR VOIDED THIS DATA FROM AJAX
 $.ajaxSetup({
     headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     }
 });
 function voidedData(id, mto_no){
     Swal.fire({
         title: 'Are you sure?',
         text: "void mto no." + mto_no + " " + "now?",
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Yes, void it!'
     }).
     then((willVoided) => {
         var route  = "{{ route('tms.warehouse.mto-entry_voided_mto_data', ':id') }}";
         route  = route.replace(':id', id);
         if(willVoided.value){
             $.ajax({
                 url: route,
                 type: "POST",
                 data : {
                     '_method' : 'POST'
                 },
                 success: function(data){   
                     Swal.fire(       
                         'Void!',
                         'Data has been void',
                         'success'
                         ).then(function(){
                            $('#mto-datatables').DataTable().ajax.reload();
                         });
 
                     }, 
                     error: function(){
                         Swal.fire({
                             icon: 'error',
                             title: 'Error...',
                             text: 'Something went wrong!',
                         })
                     }
                 })
         } else {
             console.log(`data MTO was dismissed by ${willVoided.dismiss}`);
         }
 
         
     })
 }
 // method POSTED 
 $.ajaxSetup({
     headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     }
 });
 function postedMTO(id, mto_no){
     Swal.fire({
         title: 'Are you sure Post?',
         text: "this data mto no." + mto_no,
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Yes, Post it!'
     }).
     then((willPosted) => {
         var route  = "{{ route('tms.warehouse.mto-entry_posted_mto_entry_data', ':id') }}";
         route  = route.replace(':id', id);
         if(willPosted.value){
             $.ajax({
                 url: route,
                 type: "POST",
                 data : {
                     '_method' : 'POST'
                 },
                 success: function(data){   
                     if (data.check) {
                        Swal.fire({
                        icon: 'warning',
                        title: data.errors
                        });
                     } else {
                        Swal.fire(       
                         'Succesfully!',
                         'Data has been Posted.',
                         'success'
                         ).then(function(){
                            $('#mto-datatables').DataTable().ajax.reload();
                         });
                      }
                    
                     }
                 })
         } else {
             console.log(`data MTO was dismissed by ${willPosted.dismiss}`);
         }
 
         
     })
 }
 
//  function UnPostedMTO(id, mto_no){
   
         //
$('.modal-footer').on('click','.ok_unpost', function(){
    var id = document.getElementById('id_mto_unpost').value;
    // alert(period)
    var route  = "{{ route('tms.warehouse.mto-entry_posted_mto_entry_data', ':id') }}";
        route  = route.replace(':id', id);
    //  $('.ok_unpost').html('Saving...');
        $.ajax({
            url: route,
            type: "POST",
            data: $('#form-mto-un-post').serialize(),
            success: function(data){
                if (data.check) {
                    Swal.fire({
                        icon: 'warning',
                        title: data.errors
                    });
                    $('#note').val("");
                } else {
                    $("#ModalUnPost").modal('hide'); 
                    Swal.fire(
                        'Successfully!',
                        'Data has been UN-POSTED no.',
                        'success'
                        ).then(function(){
                            $('#note').val("");
                            $('#mto-datatables').DataTable().ajax.reload();
                        });
                }

                }, 
                error: function(){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Something went wrong!',
                    })
                }
            });
    });
         

 function formatDate (input) {
     if (input !== null) {
         var datePart = input.match(/\d+/g),
         year = datePart[0].substring(0),
         month = datePart[1], day = datePart[2];
         return day+'/'+month+'/'+year;
     } else {
         return null;
     }
 }
 

function checkBom(item){
    var route = '{{ route("tms.warehouse.mto-mto_check_bom",":item") }}';
        route = route.replace(':item', item);
        $('#tbl-create-mto').DataTable().clear().destroy();
        $.ajax({
            url: route,
            type: 'GET',
            dataType: 'JSON',
            success: function(data){
                if (data.msg) {
                    Swal.fire({
                        icon: 'warning',
                        title:  data.msg,
                    });
                    $('#itemcode_create').val("");
                    $('#part_no_create').val("");
                    $('#descript_create').val("");   
                    var tableBOM =  $('.table-create-mto').DataTable({
                        paging:false,
                        // "bFilter": false
                    }); 
                                  
                } else {
                        for (let i = 0; i < data.length; i++) {
                        var table = $('#tbl-create-mto').DataTable();
                        var counter = table.rows().count();
                        var jml_row = Number(counter)+1;

                        var frm_code = "frm_code"+jml_row;
                        var part_no = "part_no1"+jml_row;
                        var descript = 'descript1'+jml_row;
                        var unit = 'unit'+jml_row;
                        var qty = 'qty_child'+jml_row;
                        var qty_ng = 'frm_qty_ng_child'+jml_row;
                        var wh = 'warehouse_'+jml_row;
                        var factor = 'factor'+jml_row;
                        //  document.getElementById('jumlah_row').value = i;
                    var table1=  $('#tbl-create-mto').DataTable( {
                        "bDestroy": true,
                        paging: false,
                        // "bFilter": false,
                        scrollCollapse: true,
                        scrollY: "250px"
                        // processing: true
                        });
                    table1.row.add([
                            "<tr>"+
                            "<td>"+data[i].FRM_CODE+"</td>"+
                            "<td><input type='hidden' readonly name='frm_code[]' id="+frm_code+" class='form-control form-control-sm frm_code_'  value="+data[i].FRM_CODE+"></td>",
                            "<td>"+data[i].PART_NO+"</td>"+
                            "<td><input type='hidden' readonly name='part_no_detail[]' id="+part_no+" class='form-control form-control-sm part_no_' value="+data[i].PART_NO+"></td>",
                            "<td>"+data[i].FRM_DESC+"</td>"+
                            "<td><input type='hidden' readonly name='descript_detail[]' id="+descript+" class='form-control form-control-sm frm_desc_' value='"+data[i].FRM_DESC+"'></td>",
                            "<td>"+data[i].FRM_UNIT+"</td>"+
                            "<td><input type='hidden' readonly name='unit[]' id="+unit+" class='form-control form-control-sm frm_unit_' value="+data[i].FRM_UNIT+">",
                            "<td><input type='text' readonly name='frm_quantity[]' id="+qty+" class='form-control form-control-sm qty_mto_create_'></td>"+
                            "<td><input type='hidden' name='factor[]' id="+factor+" value="+data[i].FRM_FAC+" readonly>",
                            "<td><input type='text' readonly name='frm_qty_ng[]' value='0' id="+qty_ng+" class='form-control form-control-sm qty_ng_' readonly></td>",
                            "<td><input type='text' readonly id="+wh+" value='90' class='form-control form-control-sm wh_' readonly></td>"+
                            "</tr>"
                            // "<td><button type='button' class='btn btn-danger btn-danger removerowEdit'><i class='ti-trash'></button>"+"</tr>"
                        ]).draw();
                      
                        var itung = table1.rows().count();
                        document.getElementById('jumlah_row').value = itung;
                       
                    }
                }
              
            }
        })

      
}

$(document).on('click','.removerowEdit', function(){
    var tableBOM =  $('#tbl-create-mto').DataTable();


    $('#tbl-create-mto tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) { 
            $(this).removeClass('selected'); 
        } else { 
            tableBOM.$('tr.selected').removeClass('selected');
            $(this).addClass('selected'); 
        } 
    });
    var index = tableBOM.row('.selected').indexes();
    tableBOM.row(index).remove().draw(false);
    var jml_row = document.getElementById("jmlh_row").value.trim();
        jml_row = Number(jml_row) + 1;
        nextRow = tableBOM.rows().count() + 1;
    for($i = nextRow; $i <= jml_row; $i++) {
        var frm_code = "frm_code_create_mto" + $i;
        var frm_code_new = "frm_code_create_mto-" + ($i-1);
        $(frm_code).attr({"id":frm_code_new});

        var part_no = "part_no_create_mto" + $i;
        var part_no_new = "part_no_create_mto" + ($i-1);
        $(part_no).attr({"id":part_no_new});

        var descript = "descript_create_mto" + $i;
        var descript_new = "descript_create_mto" + ($i-1);
        $(descript).attr({"id":descript_new, "name":descript_new});

        var unit = "#unit_create_mto" + $i;
        var unit_new = "unit_create_mto" + ($i-1);
        $(unit).attr({"id":unit_new});

        var qty = "#qty_create_mto" + $i;
        var qty_new = "qty_create_mto" + ($i-1);
        $(qty).attr({"id":qty_new});

        var qty_ng = "#qty_ng_create_mto" + $i;
        var qty_ng_new = "qty_create_mto" + ($i-1);
        $(qty_ng).attr({"id":qty_ng_new});

        var warehouse = "#warehouse_create_mto" + $i;
        var warehouse_new = "warehouse_create_mto" + ($i-1);
        $(warehouse).attr({"id":warehouse_new});

    }
});

$(document).on('click','.removerow', function(){
    var tableBOM =  $('.table-create-mto').DataTable();
    // var index = tableBOM.row('tr:last').indexes();
    $('.table-create-mto tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) { 
            $(this).removeClass('selected'); 
        } else { 
            tableBOM.$('tr.selected').removeClass('selected');
            $(this).addClass('selected'); 
        } 
    });
    var index = tableBOM.row('.selected').indexes();
    tableBOM.row(index).remove().draw(false);
    var jml_row = document.getElementById("jmlh_row").value.trim();
        jml_row = Number(jml_row) + 1;
        nextRow = tableBOM.rows().count() + 1;
    for($i = nextRow; $i <= jml_row; $i++) {
        var frm_code = "frm_code_create_mto" + $i;
        var frm_code_new = "frm_code_create_mto-" + ($i-1);
        $(frm_code).attr({"id":frm_code_new});

        var part_no = "part_no_create_mto" + $i;
        var part_no_new = "part_no_create_mto" + ($i-1);
        $(part_no).attr({"id":part_no_new});

        var descript = "descript_create_mto" + $i;
        var descript_new = "descript_create_mto" + ($i-1);
        $(descript).attr({"id":descript_new, "name":descript_new});

        var unit = "#unit_create_mto" + $i;
        var unit_new = "unit_create_mto" + ($i-1);
        $(unit).attr({"id":unit_new});

        var qty = "#qty_create_mto" + $i;
        var qty_new = "qty_create_mto" + ($i-1);
        $(qty).attr({"id":qty_new});

        var qty_ng = "#qty_ng_create_mto" + $i;
        var qty_ng_new = "qty_create_mto" + ($i-1);
        $(qty_ng).attr({"id":qty_ng_new});

        var warehouse = "#warehouse_create_mto" + $i;
        var warehouse_new = "warehouse_create_mto" + ($i-1);
        $(warehouse).attr({"id":warehouse_new});

    }
});
//

$(document).on('click','.removerowEditDetail', function(){
    var tableBOM =  $('#tbl-edit').DataTable();


    $('#tbl-edit tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) { 
            $(this).removeClass('selected'); 
        } else { 
            tableBOM.$('tr.selected').removeClass('selected');
            $(this).addClass('selected'); 
        } 
    });
    var index = tableBOM.row('.selected').indexes();
    tableBOM.row(index).remove().draw(false);
    var jml_row = document.getElementById("jmlh_row_edit").value.trim();
        jml_row = Number(jml_row) + 1;
        nextRow = tableBOM.rows().count() + 1;
    for($i = nextRow; $i <= jml_row; $i++) {
        var frm_code = "frmCode" + $i;
        var frm_code_new = "frmCode-" + ($i-1);
        $(frm_code).attr({"id":frm_code_new});

        var part_no = "part_no_editdetaillpage" + $i;
        var part_no_new = "part_no_editdetaillpage" + ($i-1);
        $(part_no).attr({"id":part_no_new});

        var descript = "descript_editdetaillpage" + $i;
        var descript_new = "descript_editdetaillpage" + ($i-1);
        $(descript).attr({"id":descript_new, "name":descript_new});

        var unit = "#unit_editdetaillpage" + $i;
        var unit_new = "unit_editdetaillpage" + ($i-1);
        $(unit).attr({"id":unit_new});

        var qty = "#qty_editdetaillpage" + $i;
        var qty_new = "qty_editdetaillpage" + ($i-1);
        $(qty).attr({"id":qty_new});

        var qty_ng = "#qty_ng_editdetaillpage" + $i;
        var qty_ng_new = "qty_editdetaillpage" + ($i-1);
        $(qty_ng).attr({"id":qty_ng_new});

        var warehouse = "#warehouse_editdetaillpage" + $i;
        var warehouse_new = "warehouse_editdetaillpage" + ($i-1);
        $(warehouse).attr({"id":warehouse_new});

    }
});

$(document).on('click','.removerowEditDetail2', function(){
    var tableBOM =  $('.tbl-edit').DataTable();


    $('.tbl-edit tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) { 
            $(this).removeClass('selected'); 
        } else { 
            tableBOM.$('tr.selected').removeClass('selected');
            $(this).addClass('selected'); 
        } 
    });
    var index = tableBOM.row('.selected').indexes();
    tableBOM.row(index).remove().draw(false);
    var jml_row = document.getElementById("jmlh_row_edit").value.trim();
        jml_row = Number(jml_row) + 1;
        nextRow = tableBOM.rows().count() + 1;
    for($i = nextRow; $i <= jml_row; $i++) {
  

        var qty = "#qty_child_edit" + $i;
        var qty_new = "qty_child_edit" + ($i-1);
        $(qty).attr({"id":qty_new});

        var qty_ng = "#qty_ng_child_edit" + $i;
        var qty_ng_new = "qty_ng_child_edit" + ($i-1);
        $(qty_ng).attr({"id":qty_ng_new});

        var warehouse = "#warehouse_edit" + $i;
        var warehouse_new = "warehouse_edit" + ($i-1);
        $(warehouse).attr({"id":warehouse_new});

    }
});




function qtyChild(){
    var jml = document.getElementById('jumlah_row').value;
    for (var i = 0; i <= jml; i++) {
         var qty = $('#quantity_create').val();
         var qty_ng = $('#qty_ng').val();
         var fact = $('#factor'+i).val();
         var kalk = parseInt(qty)+ parseInt(qty_ng);
        //  alert(kalk)
        var jumlh = parseInt(kalk)*parseInt(fact);
         $('#qty_child'+i).val(jumlh);
        // alert(fact)
        
    }
        // alert(fact)
}
function qtyChildNg(){
    var jml = document.getElementById('jumlah_row').value;
    for (var i = 0; i <= jml; i++) {
         var qty = $('#quantity_create').val();
         var qty_ng = $('#qty_ng').val();
         var fact = $('#factor'+i).val();
         var kalk = parseInt(qty)+ parseInt(qty_ng);
        //  alert(kalk)
        var jumlh = parseInt(kalk)*parseInt(fact);
        // alert(jmlh)
        //  var kalktambah = 
         $('#qty_child'+i).val(jumlh);
        //  $('#qty_ng_child'+i).val(qty_ng);
        
    }
}


function qtyChildEdit(){
    var jml = document.getElementById('jmlh_row_edit').value;
    for (var i = 0; i <= jml; i++) {
         var qty = $('#quantity_edit').val();
         var qty_ng = $('#qty_ng_edit').val();
         var fact = $('#factor_edit_child'+i).val();
         var kalk = parseInt(qty) + parseInt(qty_ng);
         var jumlh = parseInt(kalk)*parseInt(fact);
         $('#qty_edit_child'+i).val(jumlh);
         $('#qty_ng_edit_child'+i).val("0");
    }
        // alert(fact)
}
function qtyChildNgEdit(){
    var jml = document.getElementById('jmlh_row_edit').value;
    for (var i = 0; i <= jml; i++) {
        var qty = $('#quantity_edit').val();
         var fact = $('#factor_edit_child'+i).val();
         var qty_ng = $('#qty_ng_edit').val();
         var kalk = qty * fact;
         var jumlh = parseInt(kalk)+parseInt(qty_ng);
         $('#qty_edit_child'+i).val(jumlh);
         $('#qty_ng_edit_child'+i).val("0");
        
    }
}


function clearQtyInQtyNg(){
    $('#quantity_create').val("0");
    $('#qty_ng').val("0");
}

function clear_value_create_page(){
    $('#itemcode_create').val("");
    $('#part_no_create').val("");
    $('#descript_create').val("");
    $('#ref_no_create').val("");
    $('#types_create').val("");
    $('#quantity_create').val("0");
    $('#qty_ng').val("0");
    $('#remark_create').val("");
    var table = $('#tbl-create-mto').DataTable();
        table.rows().remove().draw();

}


function clearSearch(){
    $('input[type=search').val("");
    $('#mtoModal').on('shown.bs.modal', function () {
        $('input[type=search').focus();
    })
 
}

$(document).ready(function(){
    $('#itemcode_create').on('keyup', function(){
        if ($(this).val() != '') {
             Swal.fire({
                icon: 'warning',
                title: 'Perhatikan inputan anda tekan tombol button search untuk menampilkan Itemcode yang akan diinput',
            })
            $('#itemcode_create').val("");
        } 
    })
})


function checkStClose(period){    
    // var period = $('#period_edit').val();
    // alert(period)
    var route = "{{ route('tms.warehouse.mto_check_stclose',':id') }}";
        route = route.replace(':id', period);
        // $('#tbl-create-mto').DataTable().clear().destroy();
        $.ajax({
            url: route,
            type: 'GET',
            dataType: 'JSON',
            success: function(data){
                // if (data.msg) {
                    Swal.fire({
                        icon: 'warning',
                        title:  data.msg,
                    });                   
                } 
              
            
        })
}
// $(document).ready(function(){
//      $('#setdate').on('keyup',function() {
//         var tes = $('#setperiod').val();
//         alert(tes)
//      });
//  })

// function displayModalItem(e){
//     if(e.keyCode == 13){
//         e.preventDefault();
//         $('#datatablesItem').modal('show');

//     }
// }


// function displayModalItemEdit(e){
//     if(e.keyCode == 13){
//         // e.preventDefault();
//         $('#datatablesItem2').modal('show');

//     }
// }

 // function validateQtyInQtyNG(){
 //     var qty_in = document.getElementById('quantity_create').value;
 //     var qty_ng = document.getElementById('qty_ng').value;
 
 //    var result = qty_ng > qty_in;
 //    if(result){
 //         Swal.fire({
 //                 icon: 'error',
 //                 title: 'Error',
 //                 text: 'Qty NG Cannot be more than Qty IN'
 //             })
 
 //    } else {
 //         $('#remark_create').focus();
 //    }
 
 // //    alert(hasil)
 
     
 // }

 // function validateQtyInQtyNGEdit(){
 //    var qty_in = document.getElementById('quantity_edit').value;
 //    var qty_ng = document.getElementById('qty_ng_edit').value;
 
 //    var result = qty_ng > qty_in;
 //    if(result){
 //         Swal.fire({
 //                 icon: 'error',
 //                 title: 'Error',
 //                 text: 'Qty NG Cannot be more than Qty IN'
 //             })
 
 //    } else {
 //         $('#remark_edit').focus();
 //    }
 // }
//SERVER SIDE EDITDETAIL PAGE
  // for (let i = 0; i < data['detail'].length; i++) {
            //        var table = $('#tbl-edit').DataTable();
            //        var counter = table.rows().count();
            //        var jml_row = Number(counter)+1;
                    
            //        var qty = 'qty_child_edit'+jml_row;
            //        var qty_ng = 'qty_ng_child_edit'+jml_row;
            //        var wh = 'warehouse_edit'+jml_row;
            //        var factor = 'factor_edit'+jml_row;
            //        document.getElementById('jmlh_row_edit').value = jml_row;
            //     //    $('#tbl-edit').DataTable().fnClearTable();
            //        var table1=  $('#tbl-edit').DataTable( {
            //            "bDestroy": true,
            //            paging: false,
            //            scrollCollapse: true,
            //            scrollY: "250px"
            //         //   processing: true
            //            });
            //         // $('#tbl-edit').dataTable().fnClearTable();
            //        table1.row.add([
            //                "<tr>"+
            //                "<td>"+data['detail'][i].frm_code+"</td>",
            //                "<td>"+data['detail'][i].part_no_detail+"</td>",
            //                "<td>"+data['detail'][i].descript_detail+"</td>",
            //                "<td>"+data['detail'][i].unit+"</td>",
            //                "<td><input type='text' readonly name='frm_quantity[]' value="+data['detail'][i].frm_quantity+" id="+qty+" class='form-control form-control-sm qty_mto_edit_'></td>"+
            //                "<td><input type='hidden' name='factor[]' id="+factor+" value="+data['detail'][i].factor+" readonly>",
            //                "<td><input type='text' readonly name='qty_ng[]' value='0' id="+qty_ng+" class='form-control form-control-sm qty_ng_' readonly></td>",
            //                "<td><input type='text' readonly id="+wh+" value='90' class='form-control form-control-sm wh_' readonly></td>",
            //                "<td><button type='button' class='btn btn-danger btn-danger removerowEditDetail2'><i class='ti-trash'></button>"+"</tr>"
            //        ]).draw();
            // }

            //  var route =   "";
            //     get_mto_no = data['detail'].mto_no;
            //     route  = route.replace(':id', get_mto_no);
            // $('#tbl-edit').DataTable().clear().destroy();
            // $('#tbl-edit').DataTable({
            //     paging:  false,
            //     scrollY: '250px',
            //     scrollCollapse: true, 
            //     serverSide: true,
            //     processing: true,
            //     ajax: route,
            //     columns: [
            //         {  data: 'frm_code', name: 'frm_code'},
            //         {  data: 'part_no_detail', name: 'part_no_detail'},
            //         {  data: 'descript_detail', name: 'descript_detail'},
            //         {  data: 'unit', name: 'unit' },
            //         {  data: 'frm_quantity', name: 'frm_quantity' },
            //         {  data: 'qty_ng', name: 'qty_ng' },
            //         {  data: 'warehouse', name: 'warehouse'},
            //         {  data: null, 'render': function(data){
            //            var action= '<a href="#" id="removedetail" row-id='+data['frm_code']+' class="btn btn-danger btn-sm"  data-id='+data['id_mto']+'><i class="ti-trash"></i></a>'+
            //                         "<input type='hidden'  name='factor[]' value='"+data['factor']+"'>"
            //            return action
            //         }}

                        
            //      ],
               
            //     // processing: true,
            //     // serverSide: true
            // });
 // function validateExceptionNoteToLogActivity(){
 //     var note = document.getElementById('note').value;
 //     if(note == ''){
 //         Swal.fire({
 //             icon: 'error',
 //             title: 'Error',
 //             text: 'Note should not be blanks',
 //         })
 
         
 //     }
 
     
 // }
 //    //  }
 // function validateCreateMto(){
 //     var part_no = document.getElementById('part_no_create').value;
 //     descript = document.getElementById('descript_create').value;
 //     types = document.getElementById('types_create').value;
 //     unit = document.getElementById('unit_create').value;
 //     // itemcode = document.getElementById('itemcode_create').value;
 //     if (part_no !== '' || descript !== '' || unit !== '') {
 //         Swal.fire({
 //             icon: 'error',
 //             title: 'not valid',
 //             text: 'please press enter or button search at itemcode input',
 //         })
 //     } else if(types == ''){
 //         Swal.fire({
 //             icon: 'warning',
 //             title: 'please fill in choice type',
 //         })
 //     }
 // }

// $('.addrowedit').click(function(){
//     var tableBomEdit = $('#tbl-edit').DataTable();
//     var counter =  tableBomEdit.rows().count();
//     var itung   = Number(counter)+1;
//     document.getElementById('jmlh_row_edit').value = itung;
//     var frmCode = "frm_code_edit"+itung;
//     var partNo = "part_no_detail_edit"+itung;
//     var Descript = 'descript_detail_edit'+itung;
//     var Unit = 'unit_edit'+itung;
//     var Qty = 'qty_child_edit'+itung;
//     var QtyNG = 'qty_ng_child_edit'+itung;
//     var WH = 'warehouse_edit'+itung;
//     tableBomEdit.row.add([
//         "<tr>"+
//         "<td><input type='text' readonly name='frm_code[]' id="+frmCode+" class='form-control form-control-sm'></td>",
//         "<td><input type='text' readonly name='part_no_detail[]' id="+partNo+" class='form-control form-control-sm'></td>",
//         "<td><input type='text' readonly name='descript_detail[]' id="+Descript+" class='form-control form-control-sm'></td>",
//         "<td><input type='text' readonly name='unit[]' id="+Unit+" class='form-control form-control-sm'></td>",
//         "<td><input type='text' readonly name='quantity[]' id="+Qty+" class='form-control form-control-sm'></td>",
//         "<td><input type='text' readonly name='qty_ng[]' value='0' id="+QtyNG+" class='form-control form-control-sm' readonly></td>",
//         "<td><input type='text' readonly id="+WH+" value='90' class='form-control form-control-sm' readonly></td>"+"</tr>"
//     ]).draw(false);
//     // dtatable.row('tr:last').indexes();


// });
// $(document).ready(function() {
    
    
    
//     $('.addrowedit').on( 'click', function () {
//         var tableBomEdit = $('#tbl-edit').DataTable();
//         var counter =  tableBomEdit.rows().count();
//         var itung   = Number(counter)+1;
//         document.getElementById('jmlh_row_edit').value = itung;
//         var frmCode = "frm_code_edit"+itung;
//     var partNo = "part_no_detail_edit"+itung;
//     var Descript = 'descript_detail_edit'+itung;
//     var Unit = 'unit_edit'+itung;
//     var Qty = 'qty_child_edit'+itung;
//     var QtyNG = 'qty_ng_child_edit'+itung;
//     var WH = 'warehouse_edit'+itung;
//         tableBomEdit.row.add([
//         "<tr>"+
//         "<td><input type='text' readonly name='frm_code[]' id="+frmCode+" class='form-control form-control-sm'></td>",
//         "<td><input type='text' readonly name='part_no_detail[]' id="+partNo+" class='form-control form-control-sm'></td>",
//         "<td><input type='text' readonly name='descript_detail[]' id="+Descript+" class='form-control form-control-sm'></td>",
//         "<td><input type='text' readonly name='unit[]' id="+Unit+" class='form-control form-control-sm'></td>",
//         "<td><input type='text' readonly name='quantity[]' id="+Qty+" class='form-control form-control-sm'></td>",
//         "<td><input type='text' readonly name='qty_ng[]' value='0' id="+QtyNG+" class='form-control form-control-sm' readonly></td>",
//         "<td><input type='text' readonly id="+WH+" value='90' class='form-control form-control-sm' readonly></td>"+"</tr>"
//     ]).draw(false);
 
     
//     } );
 
//     // Automatically add a first row of data
//     // $('#addRow').click();
// } );
// function validateItemSameMto(item){
//     var url = "{{ route('tms.warehouse.mto-check_validate_same_item_mto_entry', 'item') }}";
//         url = url.replace('item', item);
//     $.ajax({
//         url: url,
//         type: "GET",
//         dataType: "JSON",
//         success: function(data){
//             if (data.check) {
//                 Swal.fire({
//                     icon: 'warning',
//                     title: data.error
//                 });
//                 clear_value_create_page()
//             } else {
//                 alert('Not Found');
//             }
//         }
//     })
// }

// $(document).ready(function(){
//     var jmlh_row = document.getElementById('jmlh_row').value;
//     // alert(jmlh_row)
//     $('#frm_code_'+jmlh_row).on('keyup', function(){
//         if ($(this).val() != '') {
//              Swal.fire({
//                 icon: 'warning',
//                 title: 'Warning!',
//                 text: 'Perhatikan inputan anda tekan ENTER untuk menampilkan select warehouse',
//             })
//             $('#frm_code_'+jmlh_row).val("");
//         } 
//     })
// })


//  function kalkulasi(){
// DELETE EDIT DETAIL
// $(document).on('click','#removedetail', function(e){
//     e.preventDefault()
//     var get_id = $(this).attr('data-id');
//     var get_frm_code = $(this).attr('row-id');
//     Swal.fire({
//         title: 'Apakah anda yakin ingin menghapus data ini?',
//         text: get_frm_code,
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         confirmButtonText: 'Yes, Deleted it!'
//     }).
//     then((willDelete) => {
//         var route  = "";
//         route  = route.replace(':id', get_id);
//         if(willDelete.value){
//             $.ajax({
//                 url: route,
//                 type: "POST",
//                 data : {
//                     '_method' : 'DELETE'
//                 },
//                 success: function(data){   
//                     console.log(data);
//                     $('#tbl-edit').DataTable().ajax.reload();
//                     $('#mto-datatables').DataTable().ajax.reload();

//                     }, 
//                     error: function(){
//                         Swal.fire({
//                             icon: 'error',
//                             title: 'Error...',
//                             text: 'Error Hub Admin!',
//                         })
//                     }
//                 })
//         } else {
//             console.log(`data Stock Out Entry was dismissed by ${willDelete.dismiss}`);
//         }

        
//     })
    
    
// });

// ADD ROW IN EDITPAGE DETAIL
// $(document).ready(function(){
// $('.addRowEditMto').click(function(e){
//     e.preventDefault();
//     var table = $('#tbl-edit').DataTable({
//        stateSave: true,
//        "bDestroy": true,
//        paging: false,
//        scrollY: "250px",
//        scrollCollapse: true
       
//     });
//     var counter = table.rows().count();
//     var itung = Number(counter)+1;
//     document.getElementById('jmlh_row_edit').value = counter;
//     var frmCode = "frmCode"+itung;
//         partNo = "part_no_editdetaillpage"+itung;
//         descript = "descript_editdetaillpage"+itung;
//         unit = "unit_editdetaillpage"+itung;
//         quantity = "frm_quantity_editdetaillpage"+itung;
//         qty_ng = "qty_ng_editdetaillpage"+itung;
//         WH = "wh_editdetailpage"+itung;
     

//     table.row.add([
//         "<tr>"+
//         "<td><input type='text' name='frm_code[]' onkeydown='displayModalItemEdit(event)' id='"+frmCode+"' class='form-control form-control-sm '></td>",
//         "<td><input type='text'readonly  name='part_no_detail[]' id='"+partNo+"' class='form-control form-control-sm '></td>",
//         "<td><input type='text'readonly  name='descript_detail[]' id='"+descript+"' class='form-control form-control-sm '></td>",
//         "<td><input type='text'readonly  name='unit[]' id='"+unit+"' class='form-control form-control-sm '></td>",
//         "<td><input type='text'  name='frm_quantity[]' id='"+quantity+"' class='form-control form-control-sm'></td>",
//             // "<input type='hidden' id="+factor+" value="" >"
//         "<td><input type='text'  name='qty_ng[]' value='0' id='"+qty_ng+"' class='form-control form-control-sm' ></td>",
//         "<td><input type='text' readonly id='"+WH+"'  class='form-control form-control-sm' readonly></td>",
//         "<td><button type='button'  id='btn-remove' class='btn btn-danger btn-sm removerowEditDetail'><i class='ti-trash'></i></button></td>"+
//         "</tr>"
//     ]).draw(false);
//     $("#frmCode"+itung).on('keyup', function(){
//         if ($(this).val() != '') {
//             Swal.fire({
//                 icon: 'warning',
//                 title: 'Perhatikan inputan anda tekan ENTER untuk menampilkan item yang akan diinput',
//             })
//             $("#frmCode"+itung).val("");
//         } 
//     })
//   }); 
// })
//  }

 </script>