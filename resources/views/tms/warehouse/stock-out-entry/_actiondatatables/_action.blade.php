<div style="text-align: center; ">
<a href="#" style="color: rgb(143, 143, 15);" data-toggle="tooltip" row-id="{{ $model->id_stout }}"  data-placement="top"  title="View" class="view"> <i class="ti-eye"></i></a>
<a href="#" style="color: rgb(88, 152, 248);" data-toggle="tooltip" row-id="{{ $model->id_stout }}" data-id="{{ $model->out_no }}" data-target="{{ $model->posted  }}"  data-placement="top" title="Edit" class="edit"> <i class="ti-pencil-alt"></i></a>
<a href="#" style="color: red;" data-toggle="tooltip" row-id="{{ $model->id_stout }}" data-id={{ $model->out_no }} data-target="{{ $model->posted }}"  data-placement="top" title="Void" class=" voided"> <i class="ti-trash"></i></a>
<a href="{{ $url_print }}" target="_blank" style="color: rgb(134, 126, 126);" data-toggle="tooltip" row-id=""  data-placement="top" title="Print" class="print"> <i class="fa fa-print"></i></a>
<a href="#" data-toggle="tooltip" style="color: rgb(36, 100, 184);" row-id="{{ $model->id_stout }}" data-id="{{ $model->out_no }}" data-target="{{$model->posted}}" data-placement="top" title="Post" class=" posted"> <i class="fa fa-paper-plane"></i></a>
<a href="#" data-toggle="tooltip" style="color: rgb(36, 100, 184);" row-id="{{ $model->id_stout }}" data-id="{{ $model->out_no }}"  data-placement="top" title="Log" class=" log"> <i class="ti-share"></i></a>
</div>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});
</script>
    