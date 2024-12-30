//FUNCTION DATATABLE YAJRA
$(document).ready(
    function() {
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
        $('#datatablevendor').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('tms.master.vendor.getdata')}}", 
            bFilter: true,
            destroy: true,
            dom: 'Brftip',
            buttons: [],
            columns: [
                {data:'id', name:'id'},
                {data:'VENDCODE', name:'VENDCODE'},
                {data:'COMPANY', name:'COMPANY'},
                {data:'INDUSTRY', name:'INDUSTRY'},
                {data:'CONTACT', name:'CONTACT'},
                {data:'ADDRESS1', name:'ADDRESS1'},
                {data:'ADDRESS2', name:'ADDRESS2'},
                {data:'ADDRESS3', name:'ADDRESS3'},
                {data:'PHONE', name:'PHONE'},
                {data:'FAX', name:'FAX'},
                {data:'HP', name:'HP'},
                {data:'EMAIL', name:'EMAIL'}
                /*
                {data: null, 'render': function (data) {
                    return  '<a href="/dashboard/edit/ '+ data['id']+'"><button type="button" onclick="buttonEdit" class="btn btn-info btn-xs mb-3"><i class="fa fa-edit"></i></button></a> <button type="button" onclick="buttonDel('+ data['id']+')" class="btn btn-danger btn-xs mb-3"><i class="fa fa-trash"></i></button> ';
                               
                  }}
                  */
            ]
        });
    }
);

/*
//FUNCTION CONFIRMATION BUTTON DELETE
function buttonDel($id) {
    if(confirm("Hapus Item Ini ?")){
        $.ajax({
        url: '/delete/'+$id,
        type: 'delete',
        success:function(response) {
               alert('Item Berhasil Dihapus');
               window.location="dashboard";
          },
          error: function (xhr, status, error){
              alert(status);
              alert(xhr.responseText);
          }

        })
    };     
};
*/




      