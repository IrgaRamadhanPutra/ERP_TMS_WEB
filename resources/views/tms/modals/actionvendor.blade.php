<div style=" text-align: center; ">
    <a href="#" data-toggle="tooltip" style="color: indigo;" data-id="'.$row->id.'" id="view_vendor" title="View"> <i class="fa fa-eye viewVendor"></i></a>
    <a href="#" data-toggle="tooltip" style="color:green;" data-id="'.$row->id.'" id="edit_vendor" title="Edit"> <i class="fa fa-edit editVendor"></i></a>
    <a href="#" data-toggle="tooltip" style="color: red;" data-id="'.$row->id.'" id="del_vendor" title="Delete"> <i class="fa fa-trash-o deleteVendor"></i></a>
    <!--<a href="#" data-toggle="tooltip" style="color:darkblue;" data-id="'.$row->id.'" id="log_vendor" title="Delete"> <i class="fa fa-info logVendor"></i></a>-->
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

    