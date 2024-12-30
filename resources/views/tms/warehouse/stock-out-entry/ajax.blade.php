<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
// ADD DISPLAY MODAL CREATE
$(document).on('click', '#addModalStout', function(e) {
    e.preventDefault();
    $('#createModalStout').modal('show');
    $('.modal-title').text('Stock Out Entry (New)');
});

$('#createModalStout').on('shown.bs.modal', function () {
  $('#types_create_stout').focus()
})
$('#editModalStout').on('shown.bs.modal', function () {
  $('#refs_no_edit_stout').focus()
})


function keyPressed(e){
    if (e.keyCode == 13) { // PRESS KEYBOARD SHORTCUT ENTER FOR APPEAR DATA ITEM
        e.preventDefault();
        $('#btnPopUpStout').click();
    } else if(e.keyCode){
        e.preventDefault();
    }
}
function keyPressedAddnewItem(e){
    if (e.keyCode == 13) { // PRESS KEYBOARD SHORTCUT ENTER FOR APPEAR DATA ITEM
        e.preventDefault();
        $('#btnPopUpStout2').click();
    } else if(e.keyCode){
        e.preventDefault();
    }
}

function keyPressedEdit(e){
    if (e.keyCode == 13) { // PRESS KEYBOARD SHORTCUT ENTER FOR APPEAR DATA ITEM
        e.preventDefault();
        $('#btnPopUpStout3').click();
    } else if(e.keyCode){
        e.preventDefault();
    }
}

function keyPressedSysWarehouse(e){
    if (e.keyCode == 13) {
        e.preventDefault();
        $('#SysWarehouseModal').modal('show');
    }
}
function keyPressedSysWarehouseEdit(e){
    if (e.keyCode == 13) {
        e.preventDefault();
        $('#SysWarehouseModal2').modal('show');
    }
}
$(".datepicker").datetimepicker({
        format: "YYYY-MM-DD",
        useCurrent: true
        
    });
// view detail
$(document).on('click', '.view', function(e){
e.preventDefault();
    var id = $(this).attr('row-id');
    $('#viewModalStout').modal('show');
    $('.modal-title').text('Stock Out Entry (View)');
    getDetail(id, 'VIEW')
});


//   ADD ROW 
$(document).ready(function(){
    $('#addRow').click(function(){
        // e.preventDefault();
        var table = $('#tbl-detail-stout-create').DataTable();
        var counter = table.rows().count();
        var jml_row = Number(counter)+1;
        // var jml_row = document.getElementById('jml_row').value;
        // jml_row = Number(jml_row) + 1;
        document.getElementById('jml_row').value = jml_row;
        var itemcode = "itemcode"+jml_row;
            part_no = "part_no"+jml_row;
            descript = "descript"+jml_row;
            fac_unit = "fac_unit"+jml_row;
            fac_qty  = "fac_qty"+jml_row;
            factor = "factor"+jml_row;
            unit = "unit"+jml_row;
            quantity = "quantity"+jml_row;
            remark = "remark"+jml_row;
            btn_delete = "remove"+jml_row;
            //
            qty = "qty"+jml_row;



        table.row.add([
                    '<div class="input-group">'+
                        '<input type="text" autofocus="on"  placeholder="Cari Itemcode" autocomplete="off"'+
                        'id="'+ itemcode +'" name="itemcode[]" required  class="form-control form-control-sm itemcode">'+
                        '<span class="input-group-btn">'+
                            '<button type="button" id="btnPopUpStout" onclick="clearSearch()"  data-toggle="modal" data-target="#stoutModal" class="btn btn-info btn-xs">'+
                            '<i class="ti-search"></i>'+
                            '</button>'+
                        '</span>'+
                    '</div>',
                    '<input type="text" readonly name="part_no[] " id="'+ part_no +'"  class="form-control form-control-sm">',
                    '<input type="text" readonly name="descript[]"  id="'+ descript +'" class="form-control form-control-sm">',
                    '<input type="number" readonly name="fac_unit[]"  id="'+fac_unit+'" class="form-control form-control-sm">',
                    '<input type="number"  name="fac_qty[]" value="0"   id="'+fac_qty+'" onchange="autoFillKali('+ jml_row +')" class="form-control form-control-sm fac_qty">',
                    '<input type="text" readonly name="factor[]" id="'+ factor +'" class="form-control form-control-sm factor_create">',
                    '<input type="text" readonly name="unit[]"  id="'+ unit +'" class="form-control form-control-sm unit">',
                    '<input type="number"  name="quantity[]"  id="'+ quantity +'" class="form-control form-control-sm qty">',
                    '<input type="text"  name="remark_detail[]" id="'+ remark +'"  class="form-control form-control-sm">',
                    '<a href="#" class="btn btn-xs btn-danger destroy"><i class="ti-trash remove"></i></a>',
        ]).draw(false);
        $(document).ready(function(){
        $('#itemcode'+jml_row).on('keyup', function(){
        if ($(this).val() != '') {
             Swal.fire({
                icon: 'warning',
                title: 'Perhatikan inputan anda tekan tombol button search untuk menampilkan Itemcode yang akan diinput',
            })
            $('#itemcode'+jml_row).val("");
        } 
    })
})


        // alert(fac_qty)
        

    })


// DELETE ROW IN ADD ITEM PAGE
// $(document).on('click', '.destroy', function(e){  

//     var btn = $('#body tr').length;
//     if(btn){
//         $(this).parent().parent().remove();
//         var jml_row = document.getElementById('jml_row').value;
//         jml_row = Number(jml_row)-1;
//     }
// });


// DELETE ROW IN ADD ITEM PAGE
$(document).on('click','.destroy', function(){
var table = $('#tbl-detail-stout-create').DataTable();
$('#tbl-detail-stout-create tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass('selected') ) { 
        $(this).removeClass('selected'); 
    } else { 
        table.$('tr.selected').removeClass('selected');
        $(this).addClass('selected'); 
    } 
});
var index = table.row('.selected').indexes();
    table.row(index).remove().draw(false);
var jml_row = document.getElementById("jml_row").value.trim();
    jml_row = Number(jml_row) + 1;
    nextRow = table.rows().count() + 1;
for($i = nextRow; $i <= jml_row; $i++) {
    var itemcode = "itemcode" + $i;
    var itemcode_new = "itemcode" + ($i-1);
    $(itemcode).attr({"id":itemcode_new});

    var part_no = "part_no" + $i;
    var part_no_new = "part_no" + ($i-1);
    $(part_no).attr({"id":part_no_new});

    var descript = "descript" + $i;
    var descript_new = "descript" + ($i-1);
    $(descript).attr({"id":descript_new, "name":descript_new});

    var fac_unit = "#fac_unit" + $i;
    var fac_unit_new = "fac_unit" + ($i-1);
    $(fac_unit).attr({"id":fac_unit_new});

    var fac_qty = "#fac_qty" + $i;
    var fac_qty_new = "fac_qty" + ($i-1);
    $(fac_qty).attr({"id":fac_qty_new});

    var factor = "#factor" + $i;
    var factor_new = "factor" + ($i-1);
    $(factor).attr({"id":factor_new});

    var unit = "#unit" + $i;
    var unit_new = "unit" + ($i-1);
    $(unit).attr({"id":unit_new});

    var quantity = "#quantity" + $i;
    var quantity_new = "quantity" + ($i-1);
    $(quantity).attr({"id":quantity_new});

    var remark = "#remark" + $i;
    var remark_new = "remark" + ($i-1);
    $(remark).attr({"id":remark_new});
}
});


});
    
//  INSERT VIA AJAX 
$('.modal-footer').on('click','.addStout', function(){
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
    url: "{{ route('tms.warehouse.stock_out_entry_storeStockOut') }}",
    type: "POST",
    data: $('#form-stout').serialize(),
    dataType: 'json',
    success: function(data){
        if($.isEmptyObject(data.error)){
                if(data.checkdata){
                    Swal.fire({
                        icon: 'warning',
                        title: data.errors
                    });
                    clear_store_stout();
                    // clear_table_when_input_data_same();
                } else {
                    $('.addStout').html('Saving...')
                    clear_store_stout();
                        Swal.fire(
                        'Successfully!',
                        'Menambahkan data baru Stock Out entry!',
                        'success'
                    ).then(function(){
                        $('#createModalStout').modal('hide');
                        $('#stout-entry-datatables').DataTable().ajax.reload();
                    })
                }
        } else {
        //     Swal.fire({
        //     icon: 'warning',
        //     title: 'Warning',
        //     text: 'Perhatikan Inputan Anda! Form Tidak Boleh Ada Yang Kosong',
        //   });
            printErrorMsg(data.error)
        }

        }
    })

    function printErrorMsg(msg){
        $('.print-error-msg').find('ul').html('');
        $('.print-error-msg').css('display','block');
        $('.addStout').html('Save')
        $.each(msg, function(key, value){
            $('.print-error-msg').find('ul').append('<li style="font-size: 15px"><i class="fa fa-exclamation-circle"></i> '+value+'</ul>');
        });
    }
})

// EDIT PAGE
$(document).on('click', '.edit', function(e){
    e.preventDefault();
    document.getElementById('refs_no_edit_stout').focus();
    var id = $(this).attr('row-id');
    var posted = $(this).attr('data-target');
    var out_no = $(this).attr('data-id');
    var select2 = $('.select2').select2();
        select2.select2('focus');
    if (posted !== '') {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: 'Stock Out Entry no.' + out_no + ' '+'telah di posted tidak bisa diedit',
        });
    } else {
        $('#editModalStout').modal('show');
        $('.modal-title').text('Stock Out Entry (Edit)');
        EditData(id)
        // updateStout(id)        
    }   
});


// VOIDED STOCK OUT ENTRY
$(document).on('click', '.voided', function(e){
    e.preventDefault();
    var id = $(this).attr('row-id');
    var out_no = $(this).attr('data-id');
    var posted = $(this).attr('data-target'); 
    if (posted !== '') {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: 'Stock Out Entry no.' + out_no + ' '+'telah di posted tidak bisa divoid',
        });
    } else {  // 
        $('.modal-title').html('Stock Out Entry (VOID)')
        $('.out_no_void').val(out_no);
        $('#ModalVoidStout').modal('show');
        voidedData(out_no);
    }
    
});




// POSTED DATA STOUT
$(document).on('click', '.posted', function(e){
    e.preventDefault();
    var id = $(this).attr('row-id');
    var out_no = $(this).attr('data-id');
    var posted = $(this).attr('data-target');
    // alert(posted);
    if(posted !== ''){
        $('#ModalUnPostStout').modal('show');
        $('.modal-title').text('Stock Out Entry (UN-POST)')
        $('.out_no_unpost').val(out_no);
        document.getElementById('out_no_unpost').value = out_no;
    } else {
        postedSTOUT(out_no)
    }
});

// LOG ACTIVITY
$(document).on('click', '.log', function(e){
    e.preventDefault();
    var out_no = $(this).attr('data-id');
    $('#logModalStout').modal('show');
    $('.modal-title').text('View Stock Out Entry Log');
    var route  = "{{ route('tms.warehouse.stock_out_entry_log', ':id') }}";
    route  = route.replace(':id', out_no);
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
            $('#tbl-log-stout').DataTable().clear().destroy();
            $('#tbl-log-stout').DataTable({
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

// VALIDATE UN-POST BUTTON DISABLED WHEN NOT YET FILL IN EXCEPTION NOTE
$(document).ready(function(){
    $('.ok_unpost_stout').attr('disabled', true);
    $('#note').on('keyup',function() {
        if($(this).val() != '') {
            $('.ok_unpost_stout').attr('disabled' , false);
        }else{
            $('.ok_unpost_stout').attr('disabled' , true);
        }
    });
})

// VALIDATE UN-POST BUTTON DISABLED WHEN NOT YET FILL IN EXCEPTION NOTE (VOID)
$(document).ready(function(){
    $('.void_submit').attr('disabled', true);
    $('#note_void').on('keyup',function() {
        if($(this).val() != '') {
            $('.void_submit').attr('disabled' , false);
        }else{
            $('.void_submit').attr('disabled' , true);
        }
    });
})

// validasi types create
$(document).ready(function(){
    $('#types_create_stout').on('keyup', function(){
        if ($(this).val() != '') {
             Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: 'Perhatikan inputan anda tekan ENTER untuk menampilkan select warehouse',
            })
            $('#types_create_stout').val("");
        } 
    })
})

// validasi types EDIT
$(document).ready(function(){
    $('#types_edit_stout').on('keyup', function(){
        if ($(this).val() != '') {
             Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: 'Perhatikan inputan anda tekan ENTER',
            })
        } else {
            alert('Not found');
        }
    })
})

// VOIDED DATA STOUT

function voidedData(out_no){
    $('.modal-footer').on('click','.void_submit', function(){
        $('.void_submit').html('Saving...');
        var route  = "{{ route('tms.warehouse.stock_out_entry_void', ':id') }}";
            route  = route.replace(':id', out_no);
            $.ajax({
                url: route,
                type: "POST",
                data : $('#form-stout-void').serialize(),
                success: function(data){   
                    console.log(data)
                    $('#ModalVoidStout').modal('hide');
                    Swal.fire(       
                        'Void!',
                        'Data berhasil divoid',
                        'success'
                        ).then(function(){
                            
                            $('#stout-entry-datatables').DataTable().ajax.reload();
                        });

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
}
//  VIEW DATA SHOW DETAIL FROM AJAX
function getDetail(id, method){
    var route  = "{{ route('tms.warehouse.show_view_stock_out_entry', ':id') }}";
    route  = route.replace(':id', id);
    $.ajax({
        url:      route,
        method:   'get',
        dataType: 'json',
        success:function(data){
        
            $('#out_no_view_stout').val(data['header'].out_no);
            $('#branch_view_stout').val(data['header'].branch);
            $('#types_view_stout').val(data['header'].types);
            $('#refs_no_view_stout').val(data['header'].refs_no);
            $('#period_view_stout').val(data['header'].period);
            $('#written_view_stout').val(formatDate(data['header'].written));
            $('#staff_view_stout').val(data['header'].staff);
            $('#dept_view_stout').val(data['header'].dept);
            $('#operator_view_stout').val(data['header'].operator);
            $('#printed_view_stout').val(formatDate(data['header'].printed));
            $('#voided_view_stout').val(formatDate(data['header'].voided));
            $('#posted_view_stout').val(formatDate(data['header'].posted));
            $('#remark_header_view_stout').val(data['header'].remark_header);
            $('#total_item_view').val(data.count);

        

            var detailDataset = [];
            for(var i = 0; i < data['detail'].length; i++){
                detailDataset.push([
                    data['detail'][i].itemcode, 
                    data['detail'][i].part_no, 
                    data['detail'][i].descript,
                    data['detail'][i].fac_unit, 
                    data['detail'][i].fac_qty, 
                    data['detail'][i].factor.toFixed(2), 
                    data['detail'][i].unit, 
                    data['detail'][i].quantity,
                    data['detail'][i].remark_detail
               ]);
            }
            $('#tbl-detail-stout-view').DataTable().clear().destroy();
            $('#tbl-detail-stout-view').DataTable({
                "paging":  false,
                "scrollY": '250px',
                "scrollCollapse": true, 
                data: detailDataset,
                columns: [
                { title: 'Itemcode'},
                { title: 'Part No.'},
                { title: 'Description'},
                { title: 'Fac Unit' },
                { title: 'Fac Qty' },
                { title: 'Factor' },
                { title: 'Unit' },
                { title: 'Qty' },
                { title: 'Remark'}
                ]
            });
        }
    });
}

// DISPLAY EDIT 
function EditData(id){
    var route  = "{{ route('tms.warehouse.stock_out_entry_edit', ':id') }}";
         route  = route.replace(':id', id);
    $.ajax({
        url:      route,
        method:   'get',
        dataType: 'json',
        success:function(data){
            // if (data['out_no']) 
            // if(data['out_no']){
            $('#id_stout_edit_stout').val(data['header'].id_stout);
            $('#out_no_edit_stout').val(data['header'].out_no);
            $('#branch_edit_stout').val(data['header'].branch);
            $('#types_edit_stout').val(data['header'].types);
            $('#refs_no_edit_stout').val(data['header'].refs_no);
            $('#period_edit_stout').val(data['header'].period);
            $('#written_edit_stout').val(formatDate(data['header'].written));
            $('#staff_edit_stout').val(data['header'].staff);
            $('#dept_edit_stout').val(data['header'].dept);
            $('#operator_edit_stout').val(data['header'].operator);
            $('#printed_edit_stout').val(formatDate(data['header'].printed));
            $('#voided_edit_stout').val(data['header'].voided);
            $('#remark_header_edit_stout').val(data['header'].remark_header);
            $('#posted_edit_stout').val(formatDate(data['header'].posted));
            $('#total_item_edit ').val(data.count);
        

            var route =   "{{ route('tms.warehouse.stock_out_dashboard_edit_detail', ':id') }}";
                get_outno = data['detail'].out_no;
                route  = route.replace(':id', get_outno);
            $('#tbl-edit-stout').DataTable().clear().destroy();
            $('#tbl-edit-stout').DataTable({
                paging:  false,
                scrollY: '250px',
                scrollCollapse: true, 
                serverSide: true,
                processing: true,
                ajax: route,
                columns: [
                    {  data: 'itemcode', name: 'itemcode'},
                    {  data: 'part_no', name: 'part_no'},
                    {  data: 'descript', name: 'descript'},
                    {  data: 'fac_unit', name: 'fac_unit' },
                    {  data: 'fac_qty', name: 'fac_qty' },
                    {  data: 'factor', name: 'factor' },
                    {  data: 'unit', name: 'unit'},
                    {  data: 'quantity',name: 'quantity' },
                    {  data: 'remark_detail', name: 'remark_detail'},
                    {  data: null, 'render': function(data){
                       var action= '<a href="#" id="editDetail" row-id='+data['itemcode']+'  data-id='+data['id_stout']+' class="editDetail"><i class="ti-pencil-alt"></i></a>'+
                                   '<a href="#" id="destroyDetail" row-id='+data['itemcode']+' data-id='+data['id_stout']+' class="destroyDetail"><i class="ti-trash"></i></a>'
                       return action
                    }}

                        
                 ],
               
                // processing: true,
                // serverSide: true
            });
            // alert(data.out_no);
        //     $('.restore').attr('disabled', false);
        //     } else {
        //         Swal.fire({
        //         icon: 'warning',
        //         title:data.msg,
        //       })
        //       $('#tbl-edit-stout').DataTable().clear().destroy();
        //       $('#out_no_edit_stout').val("");
        //       $('#branch_edit_stout').val("");
        //       $('#types_edit_stout').val("");
        //       $('#period_edit_stout').val("");
        //       $('#written_edit_stout').val("");
        //       $('#staff_edit_stout').val("");
        //       $('#staff_edit_stout').val("");
        //       $('#total_item_edit').val("");
        //       $('#printed_edit_stout').val("");
        //       $('#remark_header_edit_stout').val("");
        //       $('#refs_no_edit_stout').val("");
        //       $('.restore').attr('disabled', true);
        //      $('#tbl-edit-stout').DataTable({
        //         stateSave: true,
        //         "bDestroy": true,
        //         paging: false,
        //         scrollY: "250px",
        //         scrollCollapse: true
                
        //         });
        //     }
        }
           
        
    });
    

}


// EDIT DETAIL CLICK
$(document).on('click','.editDetail', function(event){
    event.preventDefault()
    var get_id = $(this).attr('data-id');
    var get_itemcode = $(this).attr('row-id');
    $('.modalEditDetail').modal('show');
    $('.title-detail').html('Edit Itemcode');
    var route  = "{{ route('tms.warehouse.stock_out_entry_edit_detail_page', ':id') }}";
        route  = route.replace(':id', get_id);
    $.ajax({
        url:      route,
        method:   'get',
        dataType: 'json',
        success:function(data){
            $('#id_stout_editdetail2').val(data.id_stout);
            $('#itemcode_editdetail2').val(data.itemcode);
            $('#part_no_editdetail2').val(data.part_no);
            $('#descript_editdetail2').val(data.descript);
            $('#fac_unit_editdetail2').val(data.fac_unit);
            $('#fac_qty_editdetail2').val(data.fac_qty);
            $('#factor_editdetail2').val(data.factor);
            $('#unit_editdetail2').val(data.unit);
            $('#qty_editdetail2').val(data.quantity);
            $('#remark_detail_editdetail2').val(data.remark_detail);
           
        }, 
        error: function(){
            alert('error');
        }
    });

});

// ADD ROW/ITEM CLICK IN EDIT PAGE
$(document).ready(function(){
$('.addRowEdit').click(function(e){
    e.preventDefault();
    var table = $('#tbl-edit-stout').DataTable({
       stateSave: true,
       "bDestroy": true,
       paging: false,
       scrollY: "250px",
       scrollCollapse: true
       
    });
    var counter = table.rows().count();
    var itung = Number(counter)+1;
    document.getElementById('jml_row_editdetail').value = counter;
    var itemcode = "itemcode_editdetaill"+itung;
        part_no = "part_no_editdetaill"+itung;
        descript = "descript_editdetaill"+itung;
        fac_unit = "fac_unit_editdetaill"+itung;
        fac_qty  = "fac_qty_editdetaill"+itung;
        factor = "factor_editdetaill"+itung;
        unit = "unit_editdetaill"+itung;
        quantity = "quantity_editdetaill"+itung;
        remark = "remark_editdetaill"+itung;
     

    table.row.add([
        "<div class='input-group'>"+
        "<input type='text'  placeholder='Cari Itemcode'\
            id='"+itemcode+"' name='itemcode[]' readonly required class='form-control form-control-sm'>"+
            "<span class='input-group-btn'>"+
        "<button type='button'  id='btnPopUpStout2' onclick='clearSearch()' data-toggle='modal' data-target='#stoutModal2' class='btn btn-xs btn-info'>"+
        "<i class='ti-search'></i>"+
            "<span>"+
            "</div>",
            "<input type='text' readonly name='part_no[] ' id='"+ part_no +"' class='form-control form-control-sm'>",
            "<input type='text' readonly name='descript[] ' id='"+ descript +"' class='form-control form-control-sm'>",
            "<input type='text' readonly name='fac_unit[] ' id='"+ fac_unit +"' class='form-control form-control-sm'>",
            "<input type='number'  name='fac_qty[] ' id='"+ fac_qty +"' onchange='autoFillJumlahQtyKaliAddRowEdit("+itung+")' class='form-control form-control-sm fac_qty_addrowEdit'>",
            "<input type='text' readonly name='factor[] ' id='"+ factor +"' class='form-control form-control-sm factor_addrowEdit'>",
            "<input type='text' readonly name='unit[] ' id='"+ unit +"' class='form-control form-control-sm unit_addrowEdit'>",
            "<input type='text'  name='quantity[] ' readonly id='"+ quantity +"'  class='form-control form-control-sm qty_addrowEdit'>",
            "<input type='text' name='remark_detail[] ' id='"+ remark +"' class='form-control form-control-sm'>",
            '<a href="#"  class="btn btn-xs btn-danger destroy2"><i class="ti-trash remove"></i></a>'
    ]).draw(false);
    $(document).ready(function(){
        $('#itemcode_editdetaill'+itung).on('keyup', function(){
        if ($(this).val() != '') {
             Swal.fire({
                icon: 'warning',
                title: 'Perhatikan inputan anda tekan tombol button search untuk menampilkan Itemcode yang akan diinput',
            })
            $('#itemcode_editdetaill'+itung).val("");
        } 
    })
})

  }); 
})



// DELETE DETAIL CLICK IN EDIT PAGE
$(document).on('click','.destroyDetail', function(){
    var get_id = $(this).attr('data-id');
    var get_itemcode = $(this).attr('row-id');
    Swal.fire({
        title: 'Apakah anda yakin ingin menghapus data ini?',
        text: get_itemcode,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Deleted it!'
    }).
    then((willDelete) => {
        var route  = "{{ route('tms.warehouse.stock_out_delete_detail_page', ':id') }}";
        route  = route.replace(':id', get_id);
        if(willDelete.value){
            $.ajax({
                url: route,
                type: "POST",
                data : {
                    '_method' : 'DELETE'
                },
                success: function(data){   
                    console.log(data);
                    $('#tbl-edit-stout').DataTable().ajax.reload();
                    $('#stout-entry-datatables').DataTable().ajax.reload();

                    }, 
                    error: function(){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'Error Hub Admin!',
                        })
                    }
                })
        } else {
            console (`data Stock Out Entry was dismissed by ${willDelete.dismiss}`);
        }

        
    })
    
    
});


// DELETE ROW CLICK
$(document).on('click','.destroy2', function(){

    var table = $('#tbl-edit-stout').DataTable();
    $('#tbl-edit-stout tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) { 
            $(this).removeClass('selected'); 
        } else { 
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected'); 
        } 
    });
    var index = table.row('.selected').indexes();
    table.row(index).remove().draw(false);
    var jml_row = document.getElementById("jml_row_editdetail").value.trim();
        jml_row = Number(jml_row) + 1;
        nextRow = table.rows().count() + 1;
    for($i = nextRow; $i <= jml_row; $i++) {
        var itemcode = "itemcode_editdetail" + $i;
        var itemcode_new = "itemcode_editdetail-" + ($i-1);
        $(itemcode).attr({"id":itemcode_new});

        var part_no = "part_no_editdetail" + $i;
        var part_no_new = "part_no_editdetail" + ($i-1);
        $(part_no).attr({"id":part_no_new});

        var descript = "descript_editdetail" + $i;
        var descript_new = "descript_editdetail" + ($i-1);
        $(descript).attr({"id":descript_new, "name":descript_new});

        var fac_unit = "#fac_unit_editdetail" + $i;
        var fac_unit_new = "fac_unit_editdetail" + ($i-1);
        $(fac_unit).attr({"id":fac_unit_new});

        var fac_qty = "#fac_qty_editdetail" + $i;
        var fac_qty_new = "fac_qty_editdetail" + ($i-1);
        $(fac_qty).attr({"id":fac_qty_new});

        var factor = "#factor_editdetail" + $i;
        var factor_new = "factor_editdetail" + ($i-1);
        $(factor).attr({"id":factor_new});

        var unit = "#unit_editdetail" + $i;
        var unit_new = "unit_editdetail" + ($i-1);
        $(unit).attr({"id":unit_new});

        var quantity = "#quantity_editdetail" + $i;
        var quantity_new = "quantity_editdetail" + ($i-1);
        $(quantity).attr({"id":quantity_new});

        var remark = "#remark_editdetail" + $i;
        var remark_new = "remark_editdetail" + ($i-1);
        $(remark).attr({"id":remark_new});
    }
});

 
// POST STOCK OUT ENTRY FUNCTION
function postedSTOUT(out_no){
    Swal.fire({
        title: 'Anda Yakin ingin mem post?',
        text: " data ini out no." + out_no,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Post it!'
    }).
    then((willPosted) => {
        var route  = "{{ route('tms.warehouse.stock_out_entry_post', ':id') }}";
        route  = route.replace(':id', out_no);
        if(willPosted.value){
            $.ajax({
                url: route,
                type: "POST",
                data : {
                    '_method' : 'POST'
                },
                success: function(data){  
                    console.log(data); 
                    Swal.fire(       
                        'Succesfully!',
                        'Data berhasil dipost.',
                        'success'
                        ).then(function(){
                            $('#stout-entry-datatables').DataTable().ajax.reload();
                        });

                    }, 
                    error: function(){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'Error Hub Admin!',
                        })
                    }
                })
        } else {
            console.log(`data MTO was dismissed by ${willPosted.dismiss}`);
        }

        
    })
}


// UN-POST STOCK-OUT ENTRY FUNCTION
// function UnPostedSTOUT(out_no){
  
        //
$('.modal-footer').on('click','.ok_unpost_stout', function(){
    var out_no = document.getElementById('out_no_unpost').value;
    // alert(out_no)
    var route  = "{{ route('tms.warehouse.stock_out_entry_post', ':id') }}";
        route  = route.replace(':id', out_no);
    $.ajax({
        url: route,
        type: "POST",
        data: $('#form-stout-un-post').serialize(),
        success: function(data){
            $("#ModalUnPostStout").modal('hide'); 
            console.log(data)
            Swal.fire(
                'Successfully!',
                'berhasil UN-POSTED out no.' + out_no,
                'success'
                ).then(function(){
                    $('#note').val("");
                    $('#stout-entry-datatables').DataTable().ajax.reload();
                });

            }, 
            error: function(){
                Swal.fire({
                    icon: 'error',
                    title: 'Error...',
                    text: 'Error Hub Admin!',
                })
            }
        });
    });
    
    

// date FORMAT
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

// UPDATE STOCK-OUT HEADER IN EDIT PAGE FUNCTION

   
$('.modal-footer').on('click','.updateStout', function(){
    var id = document.getElementById('id_stout_edit_stout').value;
    var route  = "{{ route('tms.warehouse.stock_out_entry_update', ':id') }}";
        route  = route.replace(':id', id); 
    // $('.updateStout').html('Saving...');
    $.ajax({
        url: route,
        type: "POST",
        data: $('#form-stout-edit').serialize(),
        success: function(data){
            // alert(data.itemcode);
            // $("#editModalStout").modal('hide'); 
            // location.reload();
            },
            error:function(data){
                console.log(data);
            }
        });
    // return false;
});


// LOGIC UPDATE DETAIL IN EDIT PAGE FUNCTION

$('.modal-footer').on('click','.editItemcode', function(){
    var id = document.getElementById('id_stout_editdetail2').value;
    var route_update =  "{{ route('tms.warehouse.stock_out_entry_update_detail', ':id') }}";
        route_update = route_update.replace(':id', id);
    $.ajax({
        url: route_update,
        type: "POST",
        data: $('#form-edit-detail-stout').serialize(),
        success: function(data){
                $("#modalEditDetail").modal('hide');
                // var jml_ = document.getElementById('jml_row_editdetail').value;
                // alert(jml_)
                var tbl = $('#tbl-edit-stout').DataTable();
                tbl.draw()
                
            },
            error:function(data){
                alert('error');
            }

            
        });
    
});
        


// LOGIC UPDATE HEADER REALTIME FUNCTION
function editHeader(){
    let out_no = document.getElementById('out_no_edit_stout').value;
    let route  = "{{ route('tms.warehouse.stock_out_update_header', ':id') }}";
        route  = route.replace(':id', out_no);
    $.ajax({    
    url: route,
    type: "POST",
    data: $('#form-stout-edit-header').serialize(),
    success: function(data){
          console.log(data)
             $('#editModalStout').modal('hide');
           Swal.fire({
                title: 'Successfully',
                icon: 'success',
                timer:500
            }).then(function(){
                var tbl = $('#tbl-edit-stout').DataTable();
                tbl.draw()
                
            })
                
        },
        error: function(){
            alert('error');
        }
    });
    
}    
// ONCHANGE
function editTypes(){
    let out_no = document.getElementById('out_no_edit_stout').value;
    let route  = "{{ route('tms.warehouse.stock_out_update_header', ':id') }}";
        route  = route.replace(':id', out_no);
    $.ajax({
    url: route,
    type: "POST",
    data: $('#form-stout-edit-header').serialize(),
    success: function(data){
          console.log(data)
        },
        error: function(){
            alert('error');
        }
    });
    
} 

// function SaveLogEdit(){

// }


function autoFillKali(jml_row){
    let qty2 = $('#quantity'+jml_row).val();
    let factor = $('#factor'+jml_row).val();
    let fac_qty = $('#fac_qty'+jml_row).val();
    let jumlahKali = fac_qty*factor;
    $('#quantity'+jml_row).val(jumlahKali)
}

function autoFillJumlahQtyKaliEdit(){
        let fac_qty = $('#fac_qty_editdetail2').val();
        let factor = $('#factor_editdetail2').val();
        let qty2 = $('#qty_editdetail2').val();
        let unit = $('#unit_editdetail2').val();
        let jumlahKali = fac_qty*factor;
        $('#qty_editdetail2').val(jumlahKali);
        
}


function autoFillJumlahQtyKaliAddRowEdit(itung){
        let fac_qty = $('#fac_qty_editdetaill'+itung).val();
        let factor = $('#factor_editdetaill'+itung).val();
        let qty2 = $('#quantity_editdetaill'+itung).val();
        // let unit = $('#unit_addrowEdit'+itung).val();
        let jumlahKali = fac_qty*factor;
        $('#quantity_editdetaill'+itung).val(jumlahKali);
}


function clearFacQtyQuantity(){
   $('#fac_qty_editdetail2').val("");
   $('#qty_editdetail2').val("");  
   autoFillJumlahQtyKaliEdit();
   autoFillJumlahQtyBagiEdit(); 
}

function clear_store_stout(){
    $('#dept_create_stout').val("");
    $('#types_create_stout').val("");
    $('#refs_no_create_stout').val("");
    $('#remark_header_create_stout').val("");
    var table = $('#tbl-detail-stout-create').DataTable();
        table.rows().remove().draw(false);
    $('.addStout').html('Save');
}

// function clear_table_when_input_data_same(){
//     var table = $('#tbl-detail-stout-create').DataTable();
//     table.rows().remove().draw(false);
// }
// function validateItemSame(item){
//     var url = "{{ route('tms.warehouse.stock_out_validate_edit_detail_page', 'item') }}";
//         url = url.replace('item', item);
//     $.ajax({
//         url: url,
//         type: "GET",
//         dataType: "JSON",
//         success: function(data){
//             if (data.check) {
//                 var tableStoutCreate = $('#tbl-detail-stout-create').DataTable();
//                 var counter= tableStoutCreate.rows().count();
//                 Swal.fire({
//                     icon: 'warning',
//                     title: 'Warning',
//                     text: data.error,
//                 });
//                 $('#itemcode'+counter).val("");
//                 $('#part_no'+counter).val("");
//                 $('#descript'+counter).val("");
//                 $('fac_unit'+counter).val("");
//                 $('#fac_qty'+counter).val("");
//                 $('#factor'+counter).val("");
//                 $('#unit'+counter).val("");
//                 $('#quantity'+counter).val("");
//                 $('#remark'+counter).val("");
//             } else {
//                 alert('Not Found');
//             }
//         }
//     })
// }

// function validateItemSameEdit(item){
//     var url = "{{ route('tms.warehouse.stock_out_validate_edit_detail_page', 'item') }}";
//         url = url.replace('item', item);
//     $.ajax({
//         url: url,
//         type: "GET",
//         dataType: "JSON",
//         success: function(data){
//             if (data.check) {
//                 var tableStoutEditDetail = $('#tbl-edit-stout').DataTable();
//                 var counter= tableStoutEditDetail.rows().count();
//                 // alert(counter)
//                 Swal.fire({
//                     icon: 'warning',
//                     title: 'Warning',
//                     text: data.error,
//                 });
//                 $('#itemcode_editdetaill'+counter).val("");
//                 $('#part_no_editdetaill'+counter).val("");
//                 $('#descript_editdetaill'+counter).val("");
//                 $('#fac_unit_editdetaill'+counter).val("");
//                 $('#fac_qty_editdetaill'+counter).val("");
//                 $('#factor_editdetaill'+counter).val("");
//                 $('#unit_editdetaill'+counter).val("");
//                 $('#quantity_editdetaill'+counter).val("");
//                 $('#remark_editdetaill'+counter).val("");
//             } else {
//                 alert('Not Found');
//             }
//         }
//     })
// }

function clear_value_create_page(){
    $('#types_create_stout').val("");
    $('#refs_no_create_stout').val("");
    $('#remark_header_create_stout').val("");
    var table = $('#tbl-detail-stout-create').DataTable();
        table.rows().remove().draw();

}
function clearSearch(){
    $('input[type=search').val("");
    $('#stoutModal').on('shown.bs.modal', function () {
        $('input[type=search').focus();
    })
 
}
// function validateEditDetail(){
//      var tableStoutEditDetail = $('#tbl-edit-stout').DataTable();
//      var counter= tableStoutEditDetail.rows().count();
//      var itemcode = $('#itemcode_editdetaill'+counter).val();
//      alert(itemcode)
//      if (itemcode == "") {
//         Swal.fire({
//             icon: 'warning',
//             title: 'Isi dulu itemcode!',
//         });
//      }
// }
// $(document).on('click','.restore', function(){
function restore(){
$('#editModalStoutRestore').modal('show');
$('.modal-title').text('Restore Item');
var get_outno = document.getElementById('out_no_edit_stout').value;
var route =   "{{ route('tms.warehouse.stock_out_stock_out_view_restore_page', ':id') }}"; 
        route  = route.replace(':id', get_outno);
    $('#tbl-edit-restore').DataTable().clear().destroy();
    $('#tbl-edit-restore').DataTable({
        paging:  false,
        scrollY: '250px',
        scrollCollapse: true, 
        serverSide: true,
        ajax: route,
        columns: [
            {  data: 'itemcode', name: 'itemcode'},
            {  data: 'part_no', name: 'part_no'},
            {  data: 'descript', name: 'descript'},
            {  data: null, 'render': function(data){
                var action= '<a href="#" id="restore" class="btn btn-xs btn-success"  data-id='+data['id_stout']+' class="restore">Restore</a>'+' '+
                            '<a href="#" id="destroypermanen" class="btn btn-xs btn-success" data-id='+data['id_stout']+' class="destroypermanen">Delete Permanen</a>'
                return action
            }}

                
            ],
        
        // processing: true,
        // serverSide: true
    });
// })
}
$(document).on('click','#restore', function(event){
    event.preventDefault()
    var get_id = $(this).attr('data-id');
    Swal.fire({
        title: 'Apakah anda yakin ingin merestore data ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya Restore!'
    }).
    then((willRes) => {
        var route  = "{{ route('tms.warehouse.stock_out_entry_restore_action', ':id') }}";
        route  = route.replace(':id', get_id);
        if(willRes.value){
            $.ajax({
                url: route,
                type: "GET",
                data : {
                    '_method' : 'POST'
                },
                success: function(data){  
                    console.log(data);
                    $('#editModalStoutRestore').modal('hide'); 
                    var tbl2 = $('#tbl-edit-stout').DataTable();
                    tbl2.draw();

                    }, 
                    error: function(){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'Error Hub Admin!',
                        })
                    }
                })
        } else {
            console (`data Stock Out Entry was dismissed by ${willRes.dismiss}`);
        }

        
    })
    

});
</script>
    