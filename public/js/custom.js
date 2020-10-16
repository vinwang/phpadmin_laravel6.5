//全选
layui.use('form', function(){
  var form = layui.form;
  form.on("checkbox(checkall)", function(data){
    if(data.elem.checked){
      $('tbody input.checkbox').not(function(){
        return $(this).attr('disabled');
      }).prop('checked',true);
    }else{
      $('tbody input.checkbox').prop('checked',false);
    }
    form.render('checkbox');
  });
})

//更改数据状态
function changeStatus(uri, token){
  layui.use('form', function(){
    var form = layui.form;
    form.on('switch', function(data){
      var status = 0, id = $(data.elem).attr('data_id');
      if(data.elem.checked){
        status = 1;
      }
      if(!id || !data) return false;
      $.ajax({
        type: "POST",
        url: uri,
        headers: {'X-CSRF-TOKEN': token },
        data: {_method:"PUT", id:id, status:status, type:'status'},
        success: function(result){
          if(result.code > 0){
            if(status){
              $(data.elem).prop("checked", false);
            }
            else{
              $(data.elem).prop("checked", true);
            }
            form.render();
            layer.msg(result.msg, {icon: 5});
            return false;
          }
        }
      });
    });
  });
}

/*删除*/
function info_del(uri, token){
  layer.confirm('确认要删除吗？',function(index){
    $.ajax({
      type: "POST",
      url: uri,
      headers: {'X-CSRF-TOKEN': token},
      data: {_method:"DELETE"},
      success: function(result){
        if(result.code > 0){
          layer.msg(result.msg, {icon: 5});
          return false;
        }
        else{
          location.reload();
        }
      }
    });
  });
}

/* 批量删除 */
function delAll(uri, token) {
  var ids = [];

  $(".layui-form tbody input.checkbox").each(function(index, e){
    if($(this).prop("checked")){
      ids.push($(this).val());
    }
  });
  if(!ids) return false;

  layer.confirm('确认要删除吗？', function(index){
    $.ajax({
      type: "POST",
      url: uri,
      headers: {'X-CSRF-TOKEN': token },
      data: {_method:"DELETE", id:ids},
      success: function(result){
        if(result.code > 0){
          layer.msg(result.msg, {icon: 5});
          return false;
        }
        else{
          location.reload();
        }
      }
    });
  });
}