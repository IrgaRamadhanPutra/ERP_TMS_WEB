@extends('master')
@section('title', 'TMS | Warehouse - Stock Out Entry')
@section('css')
<!-- DATATABLES -->
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet"  type="text/css" href="{{asset('vendor/datetimepicker/css/bootstrap-datetimepicker.css')}}"/>
<script src="{{asset('vendor/datetimepicker/js/jquery.min.js')}}"></script>
<script src="{{asset('vendor/datetimepicker/js/moment.min.js')}}"></script>
<script src="{{asset('vendor/datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
<style>
    input[type=search]{
        text-transform: uppercase;
    }
   #refs_no_create_stout, 
   #refs_no_edit_stout,
   #remark_header_create_stout, 
   #remark_header_edit_stout{
        text-transform: uppercase;
    }
 
    .edit-modal {
        overflow-y: scroll;
        height:calc(100% - 100px);
    }
    .create-modal {
        overflow-y: scroll;
        height:calc(100% - 100px);
    }
    .edit-modal-detail {
        overflow-y: scroll;
        height:calc(100% - 100px);
    }
    }
    /* input[type=text]{
        text-transform: uppercase;
    } */
</style>
@endsection
@section('content')
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="#">
                <button type="button"  class="btn btn-primary btn-flat btn-sm" id="addModalStout">
                    <i class="ti-plus"></i>  Add New Data
                </button>
               {{--  <button type="button" id="checkStockItem" class="btn btn-flat btn-sm btn-danger">
                    <i class="fa fa-check"></i> Stock
                </button> --}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h4 class="card-header-title">Stock Out Entry</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="datatable datatable-primary">
                                <div class="table-responsive">
                                    <table id="stout-entry-datatables"  class="table table-striped table-hover" style="width:100%">
                                        <thead class="text-center" style="text-transform: uppercase; font-size: 11px;" >
                                            {{-- style="background-color: #D3D3D3" style="font-size: 15px;" --}}
                                            <tr>
                                                <th width="10%">Out No</th>
                                                <th width="10%">Date</th>
                                                <th width="10%">Posted</th>
                                                <th width="10%">Ref No</th>
                                                <th width="10%">PO No</th>
                                                <th width="10%">Staff</th>
                                                <th width="20%">Description I</th>
                                                <th width="20%">ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@include('tms.warehouse.stock-out-entry.modal.create-stout-modal.create')
@include('tms.warehouse.stock-out-entry.modal.pop-up-choiceitemdata.item-modal')
@include('tms.warehouse.stock-out-entry.modal.view-stout-modal.view-stout')
@include('tms.warehouse.stock-out-entry.modal.edit-stout-modal._edit-stout')
@include('tms.warehouse.stock-out-entry.modal.modal-log-stout.un_posted')
@include('tms.warehouse.stock-out-entry.modal.modal-log-stout.view_logstout')
@include('tms.warehouse.stock-out-entry.modal.edit-stout-modal._edit-detail')
@include('tms.warehouse.stock-out-entry.modal.pop-up-choiceitemdata.item-modal2')
@include('tms.warehouse.stock-out-entry.modal.pop-up-choiceitemdata.item-modal3')
@include('tms.warehouse.stock-out-entry.modal.pop-up-choiceitemdata.datatables_wh')
@include('tms.warehouse.stock-out-entry.modal.pop-up-choiceitemdata.datatables_wh_edit')
@include('tms.warehouse.stock-out-entry.modal.modal-log-stout.void')
@include('tms.warehouse.stock-out-entry.modal.replace.restoredata')
@endsection
@section('script')
@include('tms.warehouse.stock-out-entry.ajax')
@endsection

{{-- generate datatable mto-entry --}}
@push('js')
<!-- Datatables -->
<script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function() {

        //get data from datatables
        var table = $('#stout-entry-datatables').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('tms.warehouse.get_stock_out_datatables') }}",
            },
            order: [[ 0, 'desc']],
            responsive: true,
            columns: [
            { data: 'out_no', name: 'out_no' },
            { data: 'written', name: 'written' },
            { data: 'posted', name: 'posted' },
            { data: 'refs_no', name: 'refs_no' },
            { data: 'po_no', name: 'po_no' },
            { data: 'staff', name: 'staff' },
            { data: 'remark_header', name: 'remark_header' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
            ]    
        });

        // get(table)
      
        // get(table)
        // GET ITEM DATA 
        var url = "{{ route('tms.warehouse.get_stock_out_get_choice_data_item_datatables') }}";
        var lookUpdataStout =  $('#lookUpdataStout').DataTable({
            processing: true, 
            serverSide: true, 
            "pagingType": "numbers",
            ajax: url,
            responsive: true,
            "order": [[1, 'asc']],
            columns: [
            { data: 'ITEMCODE', name: 'ITEMCODE' },
            { data: 'PART_NO', name: 'PART_NO' },
            { data: 'DESCRIPT', name: 'DESCRIPT' }
       

            ],
            "bDestroy": true,
            "initComplete": function(settings, json) {
                // $('div.dataTables_filter input').focus();
                $('#lookUpdataStout tbody').on('dblclick', 'tr', function () {
                    var dataArr = [];
                    var rows = $(this);
                    var rowData = lookUpdataStout.rows(rows).data();
                    $.each($(rowData),function(key,value){
                        var jml_row = document.getElementById('jml_row').value;
                        var item = document.getElementById("itemcode" + jml_row).value = value["ITEMCODE"];
                        document.getElementById("part_no"+ jml_row).value = value["PART_NO"];
                        document.getElementById("descript"+ jml_row).value = value["DESCRIPT"];
                        document.getElementById("fac_unit"+ jml_row).value = value["FAC_UNIT"];
                        document.getElementById("unit"+ jml_row).value = value["UNIT"];
                        document.getElementById("factor"+ jml_row).value = value["FACTOR"];
                        // validateItemSame(item)
                        $('#stoutModal').modal('hide');
                        $('.fac_qty').focus();
                        
                        
                    });
                });
              
            },
      
        });
        // var tableSt = $('#tbl-detail-stout').DataTable();
        // var url2 = "{{ route('tms.warehouse.get_stock_out_get_choice_data_item_datatables') }}";
        var lookUpdataStout2 =  $('#lookUpdataStout2').DataTable({
            
            processing: true, 
            serverSide: true, 
            "pagingType": "numbers",
            ajax: url,
            responsive: true,
            
            // "scrollX": true,
            // "scrollY": "500px",
            // "scrollCollapse": true,
            "order": [[1, 'asc']],
            columns: [
            { data: 'ITEMCODE', name: 'ITEMCODE' },
            { data: 'PART_NO', name: 'PART_NO' },
            { data: 'DESCRIPT', name: 'DESCRIPT' }
       

            ],
            "bDestroy": true,
            "initComplete": function(settings, json) {
                // $('div.dataTables_filter input').focus();
                $('#lookUpdataStout2 tbody').on( 'dblclick', 'tr', function () {
                    var dataArr = [];
                    var rows2 = $(this);
                    var rowData2 = lookUpdataStout2.rows(rows2).data();
                    $.each($(rowData2),function(key,value){
                        var table = $('#tbl-edit-stout').DataTable();
                        var itung = table.rows().count();
                        var item = document.getElementById("itemcode_editdetaill"+itung ).value = value["ITEMCODE"];
                        document.getElementById("part_no_editdetaill"+itung).value = value["PART_NO"];
                        document.getElementById("descript_editdetaill"+itung).value = value["DESCRIPT"];
                        document.getElementById("fac_unit_editdetaill"+itung).value = value["FAC_UNIT"];
                        document.getElementById("unit_editdetaill"+itung).value = value["UNIT"];
                        document.getElementById("factor_editdetaill"+itung).value = value["FACTOR"];
                        // validateItemSameEdit(item)
                        // clear_edit_stout_detail(itung)
                        $('#stoutModal2').modal('hide');
                        // $('#quantity_create').autofocus();
                        
                    });
                });
              
            },
      
        });
        // tes(e, lookUpdataStout)
        // get(tableSt)
        var url3 = "{{ route('tms.warehouse.get_stock_out_get_choice_data_item_datatables') }}";
        var lookUpdataStout3 =  $('#lookUpdataStout3').DataTable({
            
            processing: true, 
            serverSide: true, 
            "pagingType": "numbers",
            ajax: url3,
            responsive: true,
            "order": [[1, 'asc']],
            columns: [
            { data: 'ITEMCODE', name: 'ITEMCODE' },
            { data: 'PART_NO', name: 'PART_NO' },
            { data: 'DESCRIPT', name: 'DESCRIPT' }
       

            ],
            "bDestroy": true,
            "initComplete": function(settings, json) {
                // $('div.dataTables_filter input').focus();
                $('#lookUpdataStout3 tbody').on( 'dblclick', 'tr', function () {
                    var dataArr = [];
                    var rows3 = $(this);
                    var rowData3 = lookUpdataStout3.rows(rows3).data();
                    $.each($(rowData3),function(key,value){
             
                        var item = document.getElementById("itemcode_editdetail2").value = value["ITEMCODE"];
                        document.getElementById("part_no_editdetail2").value = value["PART_NO"];
                        document.getElementById("descript_editdetail2").value = value["DESCRIPT"];
                        document.getElementById("fac_unit_editdetail2").value = value["FAC_UNIT"];
                        document.getElementById("unit_editdetail2").value = value["UNIT"];
                        document.getElementById("factor_editdetail2").value = value["FACTOR"];
                        validateItemSame(item)
                        $('#stoutModal3').modal('hide');
                        clearFacQtyQuantity();
                        // $('#quantity_create').autofocus();
                        
                    });
                });
              
            },
      
        });


        var url_select_sys_wh = "{{ route('tms.warehouse.stock_out_entry.stock_out_select_warehouse') }}";
        var lookUpdataStout_wh =  $('#lookUpdataStoutWh').DataTable({ 
            "pagingType": "numbers",
            ajax: url_select_sys_wh,
            responsive: true,
            paging: false,
            "bFilter": false,
            "order": [[1, 'asc']],
            columns: [
            { data: 'warehouse_id', name: 'warehouse_id' },
            { data: 'descript', name: 'descript' }

            ],
            "bDestroy": true,
            "initComplete": function(settings, json) {
                // $('div.dataTables_filter input').focus();
                $('#lookUpdataStoutWh tbody').on('dblclick', 'tr', function () {
                    var dataArrWh = [];
                    var rowsWh = $(this);
                    var rowDataWh = lookUpdataStout_wh.rows(rowsWh).data();
                    $.each($(rowDataWh),function(key,value){
                        document.getElementById("types_create_stout").value = value["warehouse_id"]; 
                        $('#SysWarehouseModal').modal('hide');
                        $('#refs_no_create_stout').focus()
  
                    });
                });
              
            },
      
        });

        //
        var url_select_sys_wh2 = "{{ route('tms.warehouse.stock_out_entry.stock_out_select_warehouse') }}";
        var lookUpdataStout_wh2 =  $('#lookUpdataStoutWh2').DataTable({ 
            "pagingType": "numbers",
            ajax: url_select_sys_wh2,
            responsive: true,
            paging: false,
            "bFilter": false,
            "order": [[1, 'asc']],
            columns: [
            { data: 'warehouse_id', name: 'warehouse_id' },
            { data: 'descript', name: 'descript' }

            ],
            "bDestroy": true,
            "initComplete": function(settings, json) {
                // $('div.dataTables_filter input').focus();
                $('#lookUpdataStoutWh2 tbody').on('dblclick', 'tr', function () {
                    var dataArrWh2 = [];
                    var rowsWh2 = $(this);
                    var rowDataWh2 = lookUpdataStout_wh2.rows(rowsWh2).data();
                    $.each($(rowDataWh2),function(key,value){
                        document.getElementById("types_edit_stout").value = value["warehouse_id"]; 
                        $('#SysWarehouseModal2').modal('hide');
                        editTypes()
                        $('#refs_no_edit_stout').focus();
  
                    });
                });
              
            },
      
        });
     
        $('#tbl-detail-stout-create').DataTable({
            paging: false,
            // scrollY: '250px',
            scrollCollapse: true
        });
    });
   
</script>
@endpush