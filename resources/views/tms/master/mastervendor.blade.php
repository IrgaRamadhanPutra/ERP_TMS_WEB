@extends('master')

@section('title', 'TMS | Master Vendor')

@section('css')

<!-- DATATABLES -->
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/vendor/Datatables/Responsive-2.2.5/css/responsive.dataTables.min.css') }}">

@endsection

@section('content')

<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-10">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-header-title">Master Vendor</h4>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mt-3">
                        <button style="float; left" type="button" class="btn btn-primary btn-round" id="add_vendor" data-toggle="modal" data-target="#modal_entryvendor">Add Vendor</button>     
                    </div>
                        <div class="row mt-3">
                        <div class="col">
                            <div class="data-tables datatable-dark">
                                <table id="datatablevendor" class="table table-striped" style="width:100%">
                                    {{ csrf_field() }}
                                    <thead class="text-center">
                                        <tr>
                                            <th>No</th>
                                            <th>Code</th>
                                            <th>Industry</th>
                                            <th>Company Name</th>
                                            <th>Contact</th>
                                            <th>Address1</th>
                                            <th>Address2</th>
                                            <th>Phone</th>
                                            <th>Fax</th>
                                            <th>HP</th>
                                            <th>Action</th>
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
@endsection
  @include('tms.modals.modal_entryvendor')
  @include('tms.modals.modal_editvendor')
  @include('tms.modals.modal_deletevendor')
@push('js')
<!-- Datatables -->
<script src="{{ asset('vendor/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/Datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/srtdash/js/moment.min.js') }}"></script>
<script>

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

        var table_vendor = $('#datatablevendor').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": "{{ route('tms.master.vendor.getdata')}}", 
            bFilter: true,
            dom: 'Brftip',
            buttons: [],
            columns: [
                {"data" : "id"},
                {"data" : "VENDCODE"},
                {"data" : "INDUSTRY"},
                {"data" : "COMPANY"},
                {"data" : "CONTACT"},
                {"data" : "ADDRESS1"},
                {"data" : "ADDRESS2"},
                {"data" : "PHONE"},
                {"data" : "FAX"}, 
                {"data" : "HP"},
                {"data" : "action", orderable:false, searchable:false}
            ]
        });

       //ADD NEW VENDOR
        $('#add_vendor').click(function () {
            $('#button_action').val("Add");
            $('#vendor_id').val('');
            //$('#addvendor_form').trigger("reset");
            $('.modal-title').html("Add New Vendor");
            $('#modal_entryvendor').modal('show');
        });      

        // BUTTON SAVE AFTER ADD NEW VENDOR
        $('body').on('click', '#save-button', function (event) {
            event.preventDefault()
            var vendcode    = $('#VENDCODE').val();
            var industry    = $('#INDUSTRY').val();
            var company     = $('#COMPANY').val();
            var contact     = $('#CONTACT').val();
            var address1    = $('#ADDRESS1').val();
            var address2    = $('#ADDRESS2').val();
            var phone       = $('#PHONE').val();
            var fax         = $('#FAX').val();
            var hp          = $('#HP').val();
            var email       = $('#EMAIL').val();
            var glap        = $('#GLAP').val();
            var npwp        = $('#NPWP').val();
            var termpay     = $('#TERMOFPAY').val();
            var rate        = $('#TAXRATE').val();

            $.ajax({
                url : "{{ route('tms.master.vendor.postdata') }}",
                type : "POST",
                data : {
                    vendcode: vendcode,
                    industry: industry,
                    company: company,
                    contact: contact,
                    address1: address1,
                    address2: address2,
                    phone: phone,
                    fax: fax,
                    hp: hp,
                    email: email,
                    glap: glap,
                    npwp: npwp,
                    termpay: termpay,
                    rate: rate

                },
                dataType: 'json',
                success: function(data) {
                    $('#addvendor_form').trigger("reset");
                    $('#modal_entryvendor').modal('hide');
                    Swal.fire({
                        position    :'top-end',
                        icon        :'success',
                        title       :'Vendor Has Been Saved',
                        showConfirmButton: false,
                        timer       :1500
                    })
                    postdata() /*route postdata ke datatable */
                },
                error: function(data) {
                    console.log('Error.....');
                }
            });
        });


        //EDIT VENDOR
        $(document).on('click', '#edit_vendor', function() {
            var id = $(this).data("id");
            console.log(id);

            $.get('master/vendor/'+id+'/editdata', function(data) {
                $('#modal_editvendor').modal('show');
                $('#save-edit').val('Edit Vendor');
                $('#VENDCODE-edit').val(data.VENCODE-edit);
                $('#INDUSTRY-edit').val(data.INDUSTRY-edit);
                $('#COMPANY-edit').val(data.COMPANY-edit);
                $('#CONTACT-edit').val(data.CONTACT-edit);
                $('#ADDRESS1-edit').val(data.ADDRESS1-edit);
                $('#ADDRESS2-edit').val(data.ADDRESS2-edit);
                $('#PHONE-edit').val(data.PHONE-edit);
                $('#FAX-edit').val(data.FAX-edit);
                $('#HP-edit').val(data.HP-edit);
                $('#EMAIL-edit').val(data.EMAIL-edit);
                $('#GLAP-edit').val(data.GLAP-edit);
                $('#NPWP-edit').val(data.NPWP-edit);
                $('#TERMOFPAY-edit').val(data.TERMOFPAY-edit);
                $('#TAXRATE-edit').val(data.TAXRATE-edit); 
            })
        });




        /*
        $(document).on('click', '#edit_vendor', function (event) {
            event.preventDefault();
            var id = $(this).data(id);
            console.log(id)

            $.get('master/vendor/'+id+'/editdata', function(data) {
                $('#modal_editvendor').modal('show');
                $('#save-edit').val('Edit Vendor');
                $('#VENDCODE-edit').val(data.data.VENCODE-edit);
                $('#INDUSTRY-edit').val(data.data.INDUSTRY-edit);
                $('#COMPANY-edit').val(data.data.COMPANY-edit);
                $('#CONTACT-edit').val(data.data.CONTACT-edit);
                $('#ADDRESS1-edit').val(data.data.ADDRESS1-edit);
                $('#ADDRESS2-edit').val(data.data.ADDRESS2-edit);
                $('#PHONE-edit').val(data.data.PHONE-edit);
                $('#FAX-edit').val(data.data.FAX-edit);
                $('#HP-edit').val(data.data.HP-edit);
                $('#EMAIL-edit').val(data.data.EMAIL-edit);
                $('#GLAP-edit').val(data.data.GLAP-edit);
                $('#NPWP-edit').val(data.data.NPWP-edit);
                $('#TERMOFPAY-edit').val(data.data.TERMOFPAY-edit);
                $('#TAXRATE-edit').val(data.data.TAXRATE-edit);
            })
        }); */ 

        /*
        function editVendor($id) {
            var route = "{{route('tms.master.vendor.editdata', ':id') }}";
                route = route.replace(':id', id);
            $.ajax({
                url: route,
                methode: 'get',
                dataType: 'json',
                success:function(data){
                    $('#VENDCODE-edit').val(data['header'].VENDCODE);
                    $('#INDUSTRY-edit').val(data['header'].INDUSTRY);
                    $('#COMPANY-edit').val(data['header'].COMPANY);
                    $('#CONTACT-edit').val(data['header'].CONTACT);
                    $('#ADDRESS1-edit').val(data['header'].ADDRESS1);
                    $('#ADDRESS2-edit').val(data['header'].ADDRESS2);
                    $('#PHONE-edit').val(data['header'].PHONE);
                    $('#FAX-edit').val(data['header'].FAX);
                    $('#HP-edit').val(data['header'].HP);
                    $('#EMAIL-edit').val(data['header'].EMAIL);
                    $('#GLAP-edit').val(data['header'].GLAP);
                    $('#NPWP-edit').val(data['header'].NPWP);
                    $('#TERMOFAPAY-edit').val(data['header'].TERMOFPAY);
                    $('#TAXRATE-edit').val(data['header'].TAXRATE);
                }
            })
        }
        */
                

        //BUTTON DELETE VENDOR 
        $(document).on('click', '#del_vendor', function () {
            var $vendcode = $(this).data("id");
            //alert($vendcode);

            if(confirm('Data Vendor dengan nomor ' + $vendcode +' akan dihapus ?')) {
                del_vendor($vendcode);
            }
        });

        function del_vendor($vendcode) {
            var route = "{{ route('tms.master.vendor.deletedata', ':VENDCODE') }}"
            var url = route.replace(':VENDCODE', $vendcode);

            $.ajax({
                url : url,
                type : 'DELETE',
                success:function(data) {
           
                    //all
                    //hahahahaha
                }
            });
        };


        /*
        $(document).on('click', '#del_vendor', function (event) {
            event.preventDefault();
            var id = $(this).data('id');

            if(Swal.fire ({
                title   :'Are You Sure?',
                text    :"It will be permanently delete & can't be recovered",
                icon    :'warning',
                showCancelButton    : true,
                confirmButtonColor  : '#3085d6',
                cancelButtonColor   : '#d33',
                confirmButtonText   : 'Yes, delete it!'
            }).then((result) => {
                        
                $.ajax(
                {
                    url : 'master/vendor/delete/'+id,
                    type: 'DELETE',
                    data: {
                        id: id
                    },
                    success: function (response) {
                        Swal.fire(
                            'Remind!',
                            'Vendor Deleted Successfully!!',
                            'success'
                        )
                        deletedata () // ini bingung nih ngambilnya dari mana 
                    }
                });

            if (result.isConfirmed)
                {
                Swal.fire(
                    'Deleted!',
                    'Vendor Has Been Deleted',
                    'success'
                )
            }
            })) 
            {
                return false;
            }

        }); 
        */ 
        
      
       

       
       
  
    /*Tag ending document ready */    
    });
</script>
@endpush
