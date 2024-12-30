@extends('master')
@section('title', 'TMS | Warehouse - MTO Entry')
@section('css')
<!-- DATATABLES -->
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet"  type="text/css" href="{{asset('vendor/datetimepicker/css/bootstrap-datetimepicker.css')}}"/>
<script src="{{asset('vendor/datetimepicker/js/jquery.min.js')}}"></script>
<script src="{{asset('vendor/datetimepicker/js/moment.min.js')}}"></script>
<script src="{{asset('vendor/datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
<style>
  input[type=search]{
        text-transform: uppercase;
    }

    /* input[type=search]{
        text-transform: uppercase;
    } */
   #ref_no_create, 
   #remark_create,
   #ref_no_edit,
   #remark_edit{
        text-transform: uppercase;
    }
 
    .edit-modal {
        overflow-y: scroll;
        height:calc(100% - 100px);
    }
    .create-modal, .view-modal {
        overflow-y: scroll;
        height:calc(100% - 100px);
    }
    .frm_code_,.part_no_,
    .frm_desc_,.frm_unit_,
    .qty_mto_create_,.qty_ng_,
    .wh_,.qty_child_edit_,.qty__,.qty_mto_edit_,.qty_ng_child_edit_,.wh_edit_detail{
        background-color: transparent!important;
        border-color: transparent!important;
        outline: transparent!important;
    }
    
   
</style>
@endsection
@section('content')
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="#">
                <button type="button"  class="btn btn-primary btn-flat btn-sm" id="addModal">
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
                        <h4 class="card-header-title">Many To One Entry</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="datatable datatable-primary">
                                <div class="table-responsive">
                                    <table id="mto-datatables" class="table table-striped table-hover" style="width:100%">
                                        <thead class="text-center" style="text-transform: uppercase; font-size: 11px;" >
                                            <tr>
                                                <th width="10%">MTO No</th>
                                                <th width="10%">Written</th>
                                                <th width="10%">Posted</th>
                                                <th width="10%">Voided</th>
                                                <th width="14%">Item Code</th>
                                                <th width="14%">Ref No</th>
                                                <th width="14%">Remark</th>
                                                <th width="8%">Brch</th>
                                                <th width="30%">ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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
@include('tms.warehouse.mto-entry.modal.modal-log-mto.un_posted')
@include('tms.warehouse.mto-entry.modal.modal-log-mto.mto-log_modal')
@include('tms.warehouse.mto-entry.modal.stock.modal_stock')
@include('tms.warehouse.mto-entry.modal.view-mto-modal._viewmto')
@include('tms.warehouse.mto-entry.modal.edit-mto-modal._edit')
@include('tms.warehouse.mto-entry.modal.create-mto-modal._create')
@include('tms.warehouse.mto-entry.modal.popup-mto-choicedata.popUpMto')
@include('tms.warehouse.mto-entry.modal.popup-mto-choicedata.datatablesItem')
@include('tms.warehouse.mto-entry.modal.popup-mto-choicedata.datatablesItemEdit')
@endsection
@section('script')
@include('tms.warehouse.mto-entry.ajax')
@endsection

{{-- generate datatable mto-entry --}}
@push('js')
<!-- Datatables -->
<script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function() {

        //get data from datatables
        var table = $('#mto-datatables').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('tms.warehouse.mto-entry_datatables') }}",
            },
            order: [[ 0, 'desc']],
            responsive: true,
            // columnDefs: [
            //     {"className": "align-right vertical-center", "targets": 6},
            //     {"className": "align-center vertical-center", "targets": [0, 1, 2, 3, 4, 5]}
            // ],
            columns: [
            { data: 'mto_no', name: 'mto_no' },
            { data: 'written', name: 'written' },
            { data: 'posted', name: 'posted' },
            { data: 'voided', name: 'voided' },
            { data: 'fin_code', name: 'fin_code' },
            { data: 'ref_no', name: 'ref_no' },
            { data: 'remark', name: 'remark' },
            { data: 'branch', name: 'branch' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
            ]    
        });

      
        // get(table)
        // get Datatables choices data from ITEM / CREATE
       
        var url = "{{ route('tms.warehouse.mto-entry_datatables_choice_data') }}";
        $('#lookUpdata').DataTable().column('').search('').draw();
        var lookUpdata =  $('#lookUpdata').DataTable({
            
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
            { data: 'DESCRIPT', name: 'DESCRIPT' },
            { data: 'DESCRIPT1', name: 'DESCRIPT1' },

            ],
            "bDestroy": true,
            "initComplete": function(settings, json) {
                // $('div.dataTables_filter input').focus();
                $('#lookUpdata tbody').on( 'dblclick', 'tr', function () {
                    var dataArr = [];
                    var rows = $(this);
                    var rowData = lookUpdata.rows(rows).data();
                    $.each($(rowData),function(key,value){
                        var item = document.getElementById("itemcode_create").value = value["ITEMCODE"];
                        document.getElementById("part_no_create").value = value["PART_NO"];
                        document.getElementById("descript_create").value = value["DESCRIPT"];
                        document.getElementById("unit_create").value = value["UNIT"];
                        checkBom(item)
                        clearQtyInQtyNg()
                        // validateItemSameMto(item)
                      
                        $('#mtoModal').modal('hide');
                        // $('#quantity_create').autofocus();
                        
                    });
                });
                $('#mtoModalLabel').on('hidden.bs.modal', function () {
                    var itemcode = document.getElementById("itemcode_create").value.trim();
                    if(itemcode === '') {
                        document.getElementById("part_no_create").value = "";
                        $('#part_no_create').focus();
                    }
                });
            },
      
        });
        // lookUpdata.search('').draw();
        // var table = $('#tbl-edit-stout').DataTable({
        //     stateSave: true,
        //     "bDestroy": true,
        //     paging: false,
        //     scrollY: "250px",
        //     scrollCollapse: true
            
        // });
        

        var url2 = "{{ route('tms.warehouse.mto-entry_datatables_choice_data') }}";
        var lookUpdata2 =  $('#lookUpdata2').DataTable({
            
            processing: true, 
            serverSide: true, 
            "pagingType": "numbers",
            ajax: url2,
            responsive: true,
            // "scrollX": true,
            // "scrollY": "500px",
            // "scrollCollapse": true,
            "order": [[1, 'asc']],
            columns: [
            { data: 'ITEMCODE', name: 'ITEMCODE' },
            { data: 'PART_NO', name: 'PART_NO' },
            { data: 'DESCRIPT', name: 'DESCRIPT' },
            { data: 'DESCRIPT1', name: 'DESCRIPT1' },

            ],
            "bDestroy": true,
            "initComplete": function(settings, json) {
                // $('div.dataTables_filter input').focus();
                $('#lookUpdata2 tbody').on( 'dblclick', 'tr', function () {
                    var dataArr2 = [];
                    var rows2 = $(this);
                    var rowData2 = lookUpdata2.rows(rows2).data();
                    $.each($(rowData2),function(key,value){
                        var table = $('#tbl-edit').DataTable();
                        var itung = table.rows().count();
                      
                        document.getElementById("frmCode"+itung).value = value["ITEMCODE"];
                        document.getElementById("part_no_editdetaillpage"+itung).value = value["PART_NO"];
                        document.getElementById("descript_editdetaillpage"+itung).value = value["DESCRIPT"];
                        document.getElementById("unit_editdetaillpage"+itung).value = value["UNIT"];
                        // checkBom(item)
                        // clearQtyInQtyNg()
                        $('#datatablesItem2').modal('hide');
                        
                    });
                });
            },
        });

        ///

        var url3 = "{{ route('tms.warehouse.mto-entry_datatables_choice_data') }}";
        var lookUpdata3 =  $('#lookUpdata3').DataTable({
            
            processing: true, 
            serverSide: true, 
            "pagingType": "numbers",
            ajax: url3,
            responsive: true,
            // "scrollX": true,
            // "scrollY": "500px",
            // "scrollCollapse": true,
            "order": [[1, 'asc']],
            columns: [
            { data: 'ITEMCODE', name: 'ITEMCODE' },
            { data: 'PART_NO', name: 'PART_NO' },
            { data: 'DESCRIPT', name: 'DESCRIPT' },
            { data: 'DESCRIPT1', name: 'DESCRIPT1' },

            ],
            "bDestroy": true,
            "initComplete": function(settings, json) {
                // $('div.dataTables_filter input').focus();
                $('#lookUpdata3 tbody').on( 'dblclick', 'tr', function () {
                    var dataArr3 = [];
                    var rows3 = $(this);
                    var rowData3 = lookUpdata3.rows(rows3).data();
                    $.each($(rowData3),function(key,value){
                        var jml_row = document.getElementById('jmlh_row').value;
                        document.getElementById("frm_code_"+jml_row).value = value["ITEMCODE"];
                        document.getElementById("part_no_detail_"+jml_row).value = value["PART_NO"];
                        document.getElementById("descript_detail_"+jml_row).value = value["DESCRIPT"];
                        document.getElementById("unit_"+jml_row).value = value["UNIT"];
                        // checkBom(item)
                        // clearQtyInQtyNg()
                        $('#datatablesItem').modal('hide');
                        
                    });
                });
            },
        });

        $('#tbl-create-mto').DataTable({
            // "bDestroy": true,
            paging: false,
            // "bFilter": false
        });
    });
</script>
@endpush